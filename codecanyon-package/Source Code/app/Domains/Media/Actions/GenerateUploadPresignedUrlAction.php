<?php

declare(strict_types=1);

namespace App\Domains\Media\Actions;

use App\Domains\Media\Models\Media;
use App\Domains\Media\Repositories\Contracts\MediaRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateUploadPresignedUrlAction
{
    public function __construct(
        protected MediaRepositoryInterface $repository
    ) {
    }

    /**
     * Generate a presigned upload URL and initialize the database record.
     *
     * @return array{media: Media, upload_url: string, headers: array}
     */
    public function execute(string $name, string $mimeType, int $size, ?string $folderId, string $userId): array
    {
        $disk = config('filesystems.default', 'local');
        $uuid = (string) Str::uuid();

        // Construct path prefix
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $fileName = $uuid . '.' . ($extension ?: 'bin');
        $path = "media/{$userId}/" . date('Y/m/d') . '/' . $fileName;

        $uploadUrl = '';
        $headers = [];

        if ($disk === 'local' || $disk === 'public') {
            // For local development, we point to our local upload route
            $uploadUrl = config('app.url') . "/api/v1/media/local-upload/{$uuid}";
        } elseif ($disk === 'gcs') {
            // Generate Google Cloud Storage (GCS) Signed PUT URL
            /** @var \Google\Cloud\Storage\StorageClient $client */
            $client = Storage::disk($disk)->getClient();
            $bucketName = config("filesystems.disks.{$disk}.bucket");
            $bucket = $client->bucket($bucketName);
            $object = $bucket->object($path);

            $uploadUrl = $object->signedUrl(now()->addMinutes(20), [
                'method' => 'PUT',
                'contentType' => $mimeType,
                'version' => 'v4',
            ]);

            $headers = [
                'Content-Type' => $mimeType,
            ];
        } else {
            // Generate S3/R2 Presigned PutObject URL
            /** @var \Aws\S3\S3Client $client */
            $client = Storage::disk($disk)->getClient();
            $bucket = config("filesystems.disks.{$disk}.bucket");

            $command = $client->getCommand('PutObject', [
                'Bucket' => $bucket,
                'Key' => $path,
                'ContentType' => $mimeType,
            ]);

            $request = $client->createPresignedRequest($command, '+20 minutes');
            $uploadUrl = (string) $request->getUri();

            // Collect required headers (like Content-Type) if needed
            $headers = $request->getHeaders();
        }

        /** @var Media $media */
        $media = $this->repository->create([
            'id' => $uuid,
            'folder_id' => $folderId,
            'name' => $name,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'disk' => $disk,
            'path' => $path,
            'size' => $size,
            'user_id' => $userId,
            'custom_properties' => [
                'status' => 'pending_upload',
            ],
        ]);

        return [
            'media' => $media,
            'upload_url' => $uploadUrl,
            'headers' => $headers,
        ];
    }
}
