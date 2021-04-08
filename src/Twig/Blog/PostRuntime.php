<?php

declare(strict_types=1);

namespace App\Twig\Blog;

use App\Repository\PostRepositoryInterface;
use Twig\Extension\RuntimeExtensionInterface;

class PostRuntime implements RuntimeExtensionInterface
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getPost(int $postId)
    {
        $post = $this->postRepository->findOneBy(['id' => $postId]);
        return $post;
    }
}
