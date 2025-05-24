<?php

declare(strict_types=1);

namespace App\Handler\Account\Notice;

use App\Domain\Account\Notice\NoticeRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class NoticeCloseAllHandler extends AbstractHandler
{
    private NoticeRepository $noticeRepository;

    public function __construct(Container $container, ?NoticeRepository $noticeRepository = null)
    {
        parent::__construct($container);
        $this->noticeRepository = $noticeRepository ?? new NoticeRepository($this->container);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => 'Вы не авторизованы']);
        }

        $this->noticeRepository->closeAllByAccountId($this->getUser()->getId());

        return $this->json(['success' => true]);
    }
}
