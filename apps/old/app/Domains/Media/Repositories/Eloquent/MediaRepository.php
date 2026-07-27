<?php

declare(strict_types=1);

namespace App\Domains\Media\Repositories\Eloquent;

use App\Domains\Media\Models\Media;
use App\Domains\Media\Models\MediaFolder;
use App\Domains\Media\Repositories\Contracts\MediaRepositoryInterface;
use App\Shared\Services\BaseRepository;

class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    protected function model(): string
    {
        return Media::class;
    }

    /**
     * Get folders and media files inside a folder for a user.
     */
    public function getFolderContents(?string $folderId, ?string $userId): array
    {
        $folderQuery = MediaFolder::query()
            ->where('parent_id', $folderId);

        $fileQuery = $this->model->newQuery()
            ->where('folder_id', $folderId);

        if ($userId !== null) {
            $folderQuery->where('user_id', $userId);
            $fileQuery->where('user_id', $userId);
        }

        return [
            'folders' => $folderQuery->get(),
            'files' => $fileQuery->get(),
        ];
    }

    /**
     * Create a new folder.
     */
    public function createFolder(string $name, ?string $parentId, string $userId): MediaFolder
    {
        /** @var MediaFolder */
        return MediaFolder::create([
            'name' => $name,
            'parent_id' => $parentId,
            'user_id' => $userId,
        ]);
    }
}