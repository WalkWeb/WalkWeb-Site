<?php

declare(strict_types=1);

namespace App\Handler\Image;

use App\Domain\Image\ImageFactory;
use App\Domain\Image\ImageRepository;
use App\Handler\AbstractHandler;
use Exception;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Loader\LoaderImage;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class UploadImageHandler extends AbstractHandler
{
    public const NO_UPLOAD_SPACE = 'Превышен лимит места на диске';

    private LoaderImage $loaderImage;
    private ImageRepository $imageRepository;

    public function __construct(
        Container $container,
        ?LoaderImage $loaderImage = null,
        ?ImageRepository $imageRepository = null
    ) {
        parent::__construct($container);
        $this->loaderImage = $loaderImage ?? new LoaderImage($this->container);
        $this->imageRepository = $imageRepository ?? new ImageRepository($this->container);
    }

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

        try {
            $uploadFile = $this->loaderImage->load($request->getFiles());
            $image = ImageFactory::createNew($uploadFile, $user);

            $this->imageRepository->add($image);

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
