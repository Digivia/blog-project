<?php


namespace App\Builder\Post;


use App\DTO\Post\PostDto;
use App\Repository\PostRepositoryInterface;

class PostDtoBuilder
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function createPostDto(int $postId): ?PostDto
    {
        $post = $this->postRepository->findOneBy(['id' => $postId]);
    }
}
