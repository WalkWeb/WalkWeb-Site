<?php

declare(strict_types=1);

namespace App\Handler\Account\Notice;

use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeRepository;
use App\Domain\Auth\AuthException;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\PaginationTrait;

class AccountNoticePageHandler extends AbstractHandler
{
    use PaginationTrait;

    private const PER_PAGE = 10;

    private NoticeRepository $noticeRepository;

    public function __construct(Container $container, ?NoticeRepository $noticeRepository = null)
    {
        parent::__construct($container);
        $this->noticeRepository = $noticeRepository ?? new NoticeRepository($this->container);
    }

    /**
     * TODO Перенести в Profile
     *
     * Print notice list page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     * @throws NoticeException
     * @throws AuthException
     */
    public function __invoke(Request $request): Response
    {
        if ($loginResponse = $this->checkAuth($request)) {
            return $loginResponse;
        }

        $user = $this->getUser();
        $page = $request->page;
        $offset = ($page - 1) * self::PER_PAGE;
        $notices = $this->noticeRepository->getAll($user->getId(), $offset, self::PER_PAGE);

        if ($page > 0 && $notices->getTotal() > 0 && $page > ceil($notices->getTotal() / self::PER_PAGE)) {
            // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
            return $this->render('errors/custom_404', ['error' => 'Страница не найдена'], Response::NOT_FOUND);
        }

        return $this->render('account/notice', [
            'notices'    => $notices,
            'total'      => $notices->getTotal(),
            'pagination' => $this->getPages($notices->getTotal(), $page, '/notices/', self::PER_PAGE),
        ]);
    }
}
