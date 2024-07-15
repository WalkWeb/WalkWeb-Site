<?php

declare(strict_types=1);

namespace App\Handler\Account\Notice;

use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeRepository;
use App\Domain\Auth\AuthException;
use App\Domain\Auth\AuthInterface;
use WalkWeb\NW\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\PaginationTrait;

class AccountNoticePageHandler extends AbstractHandler
{
    use PaginationTrait;

    /**
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
        /** @var AuthInterface $user */
        $user = $this->container->getUser();
        $repository = new NoticeRepository($this->container);
        $page = $request->page;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $total = $repository->getTotal($user->getId());

        if ($page > 0 && $total > 0 && $page > ceil($total / $perPage)) {
            // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
            return $this->render('errors/custom_404', ['error' => 'Page not found'], Response::NOT_FOUND);
        }

        return $this->render('account/notice', [
            'notices'    => $repository->getAll($user->getId(), $offset, $perPage),
            'pagination' => $this->getPages($total, $page, '/notices/', $perPage),
        ]);
    }
}
