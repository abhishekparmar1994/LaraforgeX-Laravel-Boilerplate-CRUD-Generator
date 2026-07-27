<?php

declare(strict_types=1);

namespace App\Domains\Media\Controllers;

use App\Domains\Media\Actions\ConfirmUploadAction;
use App\Domains\Media\Actions\GenerateUploadPresignedUrlAction;
use App\Domains\Media\Models\Media;
use App\Domains\Media\Repositories\Contracts\MediaRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __construct(
        protected MediaRepositoryInterface $repository
    ) {}

    /**
     * List folder contents (subfolders and files).
     */
    public function index(Request $request): JsonResponse
    {
        $folderId = $request->query('folder_id');
        $userId = $request->user()->id;

        $contents = $this->repository->getFolderContents($folderId, $userId);

        return response()->json([
            'success' => true,
            'data' => [
                'folders' => $contents['folders'],
                'files' => $contents['files'],
            ]
        ]);
    }

    /**
     * Create a new folder.
     */
    public function createFolder(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'string', 'exists:media_folders,id'],
        ]);

        $folder = $this->repository->createFolder(
            name: $request->input('name'),
            parentId: $request->input('parent_id'),
            userId: $request->user()->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Folder created successfully.',
            'data' => $folder
        ], 201);
    }

    /**
     * Request a presigned URL for direct-to-cloud upload.
     */
    public function generatePresignedUrl(Request $request, GenerateUploadPresignedUrlAction $action): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string'],
            'size' => ['required', 'integer', 'min:1'],
            'folder_id' => ['nullable', 'string', 'exists:media_folders,id'],
        ]);

        $result = $action->execute(
            name: $request->input('name'),
            mimeType: $request->input('mime_type'),
            size: $request->input('size'),
            folderId: $request->input('folder_id'),
            userId: $request->user()->id
        );

        return response()->json([
            'success' => true,
            'data' => [
                'media' => $result['media'],
                'upload_url' => $result['upload_url'],
                'headers' => $result['headers'],
            ]
        ]);
    }

    /**
     * Confirm a completed file upload.
     */
    public function confirmUpload(string $id, ConfirmUploadAction $action): JsonResponse
    {
        $media = $action->execute($id);

        return response()->json([
            'success' => true,
            'message' => 'Media file upload confirmed.',
            'data' => $media
        ]);
    }

    /**
     * Local fallback upload endpoint (for development/testing).
     */
    public function localUpload(string $id, Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        /** @var Media $media */
        $media = $this->repository->findOrFail($id);

        // Save file locally to match the pre-registered path
        $file = $request->file('file');
        
        Storage::disk('local')->putFileAs(
            pathinfo($media->path, PATHINFO_DIRNAME),
            $file,
            $media->file_name
        );

        $customProps = $media->custom_properties;
        $customProps['status'] = 'ready';

        $this->repository->update($id, [
            'custom_properties' => $customProps,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully locally.',
            'data' => $media->fresh()
        ]);
    }

    /**
     * Delete a media file.
     */
    public function destroy(string $id): JsonResponse
    {
        $media = $this->repository->find($id);

        if (!$media) {
            return response()->json([
                'success' => false,
                'message' => 'Media file not found.'
            ], 404);
        }

        try {
            Storage::disk($media->disk)->delete($media->path);
        } catch (\Throwable $e) {
            // Ignore missing driver or connection exceptions
        }

        $this->repository->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Media file deleted successfully.'
        ]);
    }
}
