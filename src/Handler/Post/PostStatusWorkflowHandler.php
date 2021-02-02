<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Entity\Blog\Post;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PostStatusWorkflowHandler
 * @package App\Handler\Post
 */
class PostStatusWorkflowHandler
{
    private WorkflowInterface      $blogPublishStateMachine;
    private FlashBagInterface      $flashMessage;
    private EntityManagerInterface $entityManager;
    private TranslatorInterface $translator;

    public function __construct(
        WorkflowInterface $blogPublishStateMachine,
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        TranslatorInterface $translator
    )
    {
        $this->blogPublishStateMachine = $blogPublishStateMachine;
        $this->flashMessage            = $session->getFlashBag();
        $this->entityManager           = $entityManager;
        $this->translator              = $translator;
    }

    /**
     * Workflow publish
     * @param Post $post
     * @return bool
     */
    public function publish(Post $post): bool
    {
        if (!$this->blogPublishStateMachine->can($post, 'publish')) {
            $this->addError('publish');
            return false;
        }
        $update = $this->blogPublishStateMachine->apply($post, 'publish');
        if ($update) {
            $post->setPublishedAt(new DateTime());
            $this->entityManager->flush();
            $this->addSuccess('publish');
            return true;
        } else {
            $this->addError('publish');
            return false;
        }
    }

    /**
     * Workflow modify
     * @param Post $post
     * @return bool
     */
    public function modify(Post $post): bool
    {
        if (!$this->blogPublishStateMachine->can($post, 'modify')) {
            $this->addError('draft');
            return false;
        }
        $update = $this->blogPublishStateMachine->apply($post, 'modify');
        if ($update) {
            $post->setPublishedAt(null);
            $this->entityManager->flush();
            $this->addSuccess('draft');
            return true;
        } else {
            $this->addError('draft');
            return false;
        }
    }

    /**
     * Workflow delete
     * @param Post $post
     * @return bool
     */
    public function delete(Post $post): bool
    {
        if (!$this->blogPublishStateMachine->can($post, 'delete')) {
            $this->addError('trash');
            return false;
        }
        $update = $this->blogPublishStateMachine->apply($post, 'delete');
        if ($update) {
            $post->setPublishedAt(null);
            $this->entityManager->flush();
            $this->addSuccess('trash');
            return true;
        } else {
            $this->addError('trash');
            return false;
        }
    }

    /**
     * Workflow undelete to publish
     * @param Post $post
     * @return bool
     */
    public function undeleteToPublish(Post $post): bool
    {
        if (!$this->blogPublishStateMachine->can($post, 'undeletetopublish')) {
            $this->addError('publish');
            return false;
        }
        $update = $this->blogPublishStateMachine->apply($post, 'undeletetopublish');
        if ($update) {
            $post->setPublishedAt(new DateTime());
            $this->entityManager->flush();
            $this->addSuccess('publish');
            return true;
        } else {
            $this->addError('publish');
            return false;
        }
    }

    /**
     * Workflow undelete to draft
     * @param Post $post
     * @return bool
     */
    public function undeleteToDraft(Post $post): bool
    {
        if (!$this->blogPublishStateMachine->can($post, 'undeletetodraft')) {
            $this->addError('draft');
            return false;
        }
        $update = $this->blogPublishStateMachine->apply($post, 'undeletetodraft');
        if ($update) {
            $post->setPublishedAt(null);
            $this->entityManager->flush();
            $this->addSuccess('draft');
            return true;
        } else {
            $this->addError('draft');
            return false;
        }
    }

    private function addError(string $action)
    {
        $errorMessage = $this->translator->trans("post.{$action}.error", [], 'workflow');
        $this->flashMessage->add('error', $errorMessage);
    }

    private function addSuccess(string $action)
    {
        $successMessage = $this->translator->trans("post.{$action}.success", [], 'workflow');
        $this->flashMessage->add('success', $successMessage);
    }
}
