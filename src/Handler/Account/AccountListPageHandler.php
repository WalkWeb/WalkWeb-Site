<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\Collection\AccountListRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\PaginationTrait;

class AccountListPageHandler extends AbstractHandler
{
    use PaginationTrait;

    private const PER_PAGE = 10;

    /**
     * Print list account page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     * @throws AccountException
     */
    public function __invoke(Request $request): Response
    {
        $repository = new AccountListRepository($this->container);

        $page = $request->page;
        $offset = ($page - 1) * self::PER_PAGE;
        $total = $repository->getTotal();

        if ($page > 0 && $total > 0 && $page > ceil($total / self::PER_PAGE)) {
            // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
            return $this->render('errors/custom_404', ['error' => 'Страница не найдена'], Response::NOT_FOUND);
        }

        return $this->render('account/list', [
            'accounts'   => $repository->getAll($offset, self::PER_PAGE),
            'total'      => $repository->getTotal(),
            'pagination' => $this->getPages($total, $page, '/users/', self::PER_PAGE),
        ]);
    }
}
