<?php

declare(strict_types=1);

namespace App\Domains\Media\Actions;

use App\Domains\Media\Models\Media;
use App\Domains\Media\Repositories\Contracts\MediaRepositoryInterface;
use App\Shared\Exceptions\BusinessException;

class ConfirmUploadAction
{
    public function __construct(
        protected MediaRepositoryInterface $repository
    ) {}

    /**
     * Mark pending upload as complete.
     *
     * @throws BusinessException
     */
    public function execute(string $mediaId): Media
    {
        /** @var Media $media */
        $media = $this->repository->findOrFail($mediaId);

        if ($media->custom_properties['status'] !== 'pending_upload') {
            throw new BusinessException('This media file is not pending an upload.', 400);
        }

        $customProps = $media->custom_properties;
        $customProps['status'] = 'ready';

        $this->repository->update($mediaId, [
            'custom_properties' => $customProps,
        ]);

        return $media->fresh();
    }
}
