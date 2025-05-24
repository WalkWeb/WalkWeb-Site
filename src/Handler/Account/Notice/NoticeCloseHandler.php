<?php

declare(strict_types=1);

namespace App\Handler\Account\Notice;

use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class NoticeCloseHandler extends AbstractHandler
{
    private NoticeRepository $noticeRepository;

    public function __construct(Container $container, ?NoticeRepository $noticeRepository = null)
    {
        parent::__construct($container);
        $this->noticeRepository = $noticeRepository ?? new NoticeRepository($this->container);
    }

    /**
     * Отмечает уведомление показанным, чтобы оно больше не отображалось для пользователя
     *
     * @param Request $request
     * @return Response
     * @throws AppException|
     * @throws NoticeException
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => 'Вы не авторизованы']);
        }

        $user = $this->getUser();

        try {
            $notice = $this->noticeRepository->get($request->id);
        } catch (AppException $e) {
            return $this->json(['success' => false, 'error' => 'Уведомление не найдено']);
        }

        if ($user->getId() !== $notice->getAccountId()) {
            return $this->json(['success' => false, 'error' => 'Вы обращаетесь к чужому уведомлению']);
        }

        $notice->close();
        $this->noticeRepository->close($notice);

        return $this->json(['success' => true]);
    }
}
