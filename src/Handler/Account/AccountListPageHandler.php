<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Domain\Account\Collection\AccountListRepository;
use App\Handler\AbstractHandler;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\PaginationTrait;

class AccountListPageHandler extends AbstractHandler
{
    use PaginationTrait;

    private const PER_PAGE = 10;

    private AccountListRepository $accountListRepository;

    public function __construct(Container $container, ?AccountListRepository $accountListRepository = null)
    {
        parent::__construct($container);
        $this->accountListRepository = $accountListRepository ?? new AccountListRepository($this->container);
    }

    /**
     * Print list account page
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $page = $request->page;
        $offset = ($page - 1) * self::PER_PAGE;
        $total = $this->accountListRepository->getTotal();

        if ($page > 0 && $total > 0 && $page > ceil($total / self::PER_PAGE)) {
            // TODO Нужно доработать ошибку во фреймворке и заменить на renderErrorPage()
            return $this->render('errors/custom_404', ['error' => 'Страница не найдена'], Response::NOT_FOUND);
        }

        return $this->render('account/list', [
            'accounts'   => $this->accountListRepository->getAll($offset, self::PER_PAGE),
            'total'      => $total,
            'pagination' => $this->getPages($total, $page, '/users/', self::PER_PAGE),
        ]);
    }
}
