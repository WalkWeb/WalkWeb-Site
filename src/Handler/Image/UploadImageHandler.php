<?php

declare(strict_types=1);

namespace App\Handler\Image;

use App\Domain\Image\ImageFactory;
use App\Domain\Image\ImageRepository;
use App\Handler\AbstractHandler;
use Exception;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Loader\LoaderImage;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class UploadImageHandler extends AbstractHandler
{
    public const NO_UPLOAD_SPACE = 'Превышен лимит места на диске';

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => self::NO_AUTH]);
        }

        $user = $this->getUser();

        if ($user->getUpload()->getUpload() >= $user->getUpload()->getUploadMax()) {
            return $this->json(['success' => false, 'error' => self::NO_UPLOAD_SPACE]);
        }

        $loader = new LoaderImage($this->container);

        try {
            $uploadFile = $loader->load($request->getFiles());
            $image = ImageFactory::createNew($uploadFile, $user);

            $repository = new ImageRepository($this->container);
            $repository->add($image);

            // TODO Увеличение занятого места у пользователя

            return $this->json([
                'success'    => true,
                'file_path'  => $image->getFilePath(),
                'name'       => $image->getName(),
                'width'      => $image->getWidth(),
                'height'     => $image->getHeight(),
                'upload'     => $user->getUpload()->getUpload(),
                'upload_max' => $user->getUpload()->getUploadMax(),
            ]);
        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
