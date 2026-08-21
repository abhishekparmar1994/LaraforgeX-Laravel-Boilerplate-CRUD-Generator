<?php

declare(strict_types=1);

namespace App\Domains\Media\Repositories\Contracts;

use App\Domains\Media\Models\MediaFolder;
use App\Shared\Contracts\RepositoryInterface;

interface MediaRepositoryInterface extends RepositoryInterface
{
    /**
     * Get folders and media files inside a folder for a user.
     *
     * @return array{folders: \Illuminate\Database\Eloquent\Collection, files: \Illuminate\Database\Eloquent\Collection}
     */
    public function getFolderContents(?string $folderId, ?string $userId): array;

    /**
     * Create a new folder.
     */
    public function createFolder(string $name, ?string $parentId, string $userId): MediaFolder;
}