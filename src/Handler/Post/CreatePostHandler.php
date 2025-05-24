<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Account\AccountRepository;
use App\Domain\Account\Energy\EnergyRepository;
use App\Domain\Account\MainCharacter\MainCharacterRepository;
use App\Domain\Post\DTO\CreatePostRequestFactory;
use App\Domain\Post\PostException;
use App\Domain\Post\PostFactory;
use App\Domain\Post\PostInterface;
use App\Domain\Post\PostRepository;
use App\Domain\Post\Tag\TagRepository;
use App\Handler\AbstractHandler;
use Exception;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreatePostHandler extends AbstractHandler
{
    private TagRepository $tagRepository;
    private PostRepository $postRepository;
    private EnergyRepository $energyRepository;
    private MainCharacterRepository $mainRepository;
    private AccountRepository $accountRepository;

    public function __construct(
        Container $container,
        ?TagRepository $tagRepository = null,
        ?PostRepository $postRepository = null,
        ?EnergyRepository $energyRepository = null,
        ?MainCharacterRepository $mainRepository = null,
        ?AccountRepository $accountRepository = null
    ) {
        parent::__construct($container);
        $this->tagRepository = $tagRepository ?? new TagRepository($this->container);
        $this->postRepository = $postRepository ?? new PostRepository($this->container, $this->tagRepository);
        $this->energyRepository = $energyRepository ?? new EnergyRepository($this->container);
        $this->mainRepository = $mainRepository ?? new MainCharacterRepository($this->container);
        $this->accountRepository = $accountRepository ?? new AccountRepository($this->container);
    }

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

            if ($user->getEnergy()->getEnergy() < PostInterface::CREATE_ENERGY_COST) {
                return $this->json([
                    'success' => false,
                    'error'   => sprintf(PostException::NO_CREATE_ENERGY, PostInterface::CREATE_ENERGY_COST, $user->getEnergy()->getEnergy()),
                ]);
            }

            $data = $request->getBody();

            $dto = CreatePostRequestFactory::create($data, $user);

            $tags = $this->tagRepository->saveCollection($dto);
            $post = PostFactory::createNew($dto, $tags, $request->slug);
            $this->postRepository->add($post);

            $user->getEnergy()->editEnergy(-PostInterface::CREATE_ENERGY_COST);
            $this->energyRepository->save($user->getEnergy());

            $user->getLevel()->addExp(PostInterface::CREATE_EXP);
            $this->mainRepository->save($user->getMainCharacterId(), $user->getLevel());

            $this->accountRepository->increasePostCount($user->getId());

            return $this->json(['success' => true, 'slug' => $post->getSlug()]);

        } catch (Exception $e) {
            return $this->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
