<?php


namespace App\Builder\Post;


use App\DTO\Post\PostDto;
use App\Repository\PostRepositoryInterface;

class PostDtoBuilder
{
    public const OPTIONS = [
      'title_length' => 25,
      'excerpt_length' => 100,
    ];

    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function createPostDto(int $postId, array $options = self::OPTIONS): ?PostDto
    {
        $options = $options + self::OPTIONS;
        $post = $this->postRepository->findOneBy(['id' => $postId]);
        if (null === $post) {
            return null;
        }
        $postDto = new PostDto();
        $postDto->title = substr($post->getTitle(), 0, $options['title_length']);
        $postDto->excerpt = substr($post->getDigest(), 0, $options['excerpt_length']);
        $postDto->publishedAt = $post->getPublishedAt();
        $postDto->categories = $post->getCategories();

        return $postDto;
    }
}
