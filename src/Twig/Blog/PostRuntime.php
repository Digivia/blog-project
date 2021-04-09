<?php

declare(strict_types=1);

namespace App\Twig\Blog;

use App\Builder\Post\PostDtoBuilder;
use App\DTO\Post\PostDto;
use App\Repository\PostRepositoryInterface;
use Twig\Extension\RuntimeExtensionInterface;

class PostRuntime implements RuntimeExtensionInterface
{
    private PostRepositoryInterface $postRepository;
    private PostDtoBuilder $builder;

    public function __construct(PostRepositoryInterface $postRepository, PostDtoBuilder $builder)
    {
        $this->postRepository = $postRepository;
        $this->builder = $builder;
    }

    public function getPost(int $postId): ?PostDto
    {
        $post = $this->postRepository->findOneBy(['id' => $postId]);
        return $this->builder->createPostDto($post->getId());
    }
}
