<?php

namespace App\Api\Post\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Blog\Post;
use App\Repository\PostRepositoryInterface;

class PostDataPersister implements ContextAwareDataPersisterInterface
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Post;
    }

    public function persist($data, array $context = [])
    {
        $this->postRepository->save($data);
        return $data;
    }

    public function remove($data, array $context = []): void
    {
        $this->postRepository->remove($data);
    }
}
