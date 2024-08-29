<?php

declare(strict_types=1);

namespace App\Handler\Comment;

use App\Domain\Account\AccountRepository;
use App\Domain\Account\Energy\EnergyRepository;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentFactory;
use App\Domain\Comment\CommentInterface;
use App\Domain\Comment\CommentRepository;
use App\Domain\Comment\DTO\CreateCommentRequestFactory;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use Exception;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreateCommentHandler extends AbstractHandler
{
    public const UNKNOWN_POST = 'Unknown post slug';

    /**
     * TODO Проверка кармы
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->container->exist('user')) {
            return $this->json(['success' => false, 'error' => self::NO_AUTH]);
        }

        try {
            $user = $this->getUser();

            if ($user->getEnergy()->getEnergy() < CommentInterface::CREATE_ENERGY_COST) {
                return $this->json([
                    'success' => false,
                    'error'   => sprintf(CommentException::NO_CREATE_ENERGY, CommentInterface::CREATE_ENERGY_COST, $user->getEnergy()->getEnergy()),
                ]);
            }

            $dto = CreateCommentRequestFactory::create($request->getBody());

            $postRepository = new PostRepository($this->container);

            $postId = $postRepository->getIdBySlug($dto->getPostSlug());

            if (!$postId) {
                return $this->json(['success' => false, 'error' => self::UNKNOWN_POST]);
            }

            $energyRepository = new EnergyRepository($this->container);
            $commentRepository = new CommentRepository($this->container);
            $mainRepository = new MainCharacterRepository($this->container);
            $accountRepository = new AccountRepository($this->container);

            $comment = CommentFactory::createNew($postId, $dto->getMessage(), $user);
            $commentRepository->add($comment);

            $user->getEnergy()->editEnergy(-CommentInterface::CREATE_ENERGY_COST);
            $energyRepository->save($user->getEnergy());

            $user->getLevel()->addExp(CommentInterface::CREATE_EXP);
            $mainRepository->save($user->getMainCharacterId(), $user->getLevel());

            $postRepository->increaseCommentsCount($postId);
            $accountRepository->increaseCommentCount($user->getId());

            // TODO Увеличение количества комментариев у сообщества, если оно есть

            return $this->json([
                'success'    => true,
                'message'    => $comment->getMessage(),
                'avatar'     => $user->getAvatar(),
                'name'       => $user->getName(),
                'level'      => $user->getLevel()->getLevel(),
                'exp_at_lvl' => $user->getLevel()->getExpAtLevel(),
                'exp_to_lvl' => $user->getLevel()->getExpToLevel(),
                'exp_width'  => $user->getLevel()->getExpBarWeight(),
            ]);

        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
