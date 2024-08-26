<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Domain\Auth\AuthInterface;
use App\Domain\Comment\CommentCollection;
use App\Domain\Comment\CommentRepository;
use App\Domain\Community\BlankCommunity;
use App\Domain\Community\CommunityInterface;
use App\Domain\Community\CommunityRepository;
use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use App\Domain\Post\PostRepository;
use App\Handler\AbstractHandler;
use DateTimeInterface;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;
use WalkWeb\NW\Traits\DateTrait;

class PostPageHandler extends AbstractHandler
{
    use DateTrait;

    /**
     * @param Request $request
     * @return Response
     * @throws AppException
     */
    public function __invoke(Request $request): Response
    {
        $this->layoutUrl = 'layout/index.php';

        $repository = new PostRepository($this->container);
        $user = $this->container->exist('user') ? $this->getUser() : null;
        $post = $repository->get($request->getAttribute('slug'), $user);

        if (!$post) {
            return $this->render(
                'errors/custom_404',
                ['error' => 'Пост не найден'],
                Response::NOT_FOUND
            );
        }

        return $this->render('post/index', [
            'post'      => $post,
            'comments'  => $this->getComments($post, $user),
            'auth'      => $this->container->exist('user'),
            'community' => $this->getCommunity($post),
        ]);
    }

    /**
     * @param PostInterface $post
     * @return CommunityInterface|null
     * @throws AppException
     */
    public function getCommunity(PostInterface $post): CommunityInterface
    {
        if ($post->getCommunitySlug()) {
            $communityRepository = new CommunityRepository($this->container);
            $community = $communityRepository->get($post->getCommunitySlug());

            if (!$community) {
                throw new AppException(PostException::INVALID_COMMUNITY . $post->getCommunitySlug());
            }

            return $community;
        }

        return new BlankCommunity();
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function getCreatedAtEasyData(DateTimeInterface $date): string
    {
        return self::getElapsedTime($date);
    }

    /**
     * @param PostInterface $post
     * @param AuthInterface|null $user
     * @return CommentCollection
     * @throws AppException
     */
    private function getComments(PostInterface $post, ?AuthInterface $user): CommentCollection
    {
        if ($post->getCommentsCount() > 0) {
            $commentRepository = new CommentRepository($this->container);
            $comments = $commentRepository->getByPost($post->getId(), $user);
            $comments->revert();

            return $comments;
        }

        return new CommentCollection();
    }
}
