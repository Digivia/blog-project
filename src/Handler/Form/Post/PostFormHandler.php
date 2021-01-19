<?php

declare(strict_types=1);

namespace App\Handler\Form\Post;

use App\Entity\Blog\Post;
use App\Exception\BadEntityFormHandler;
use App\Form\Blog\PostType;
use App\Gateway\GatewayInterface;
use App\Repository\PostRepositoryInterface;
use Digivia\FormHandler\Handler\AbstractHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PostFormHandler
 * @package App\Handler\Form\Post
 */
final class PostFormHandler extends AbstractHandler
{
    private FlashBagInterface   $flashMessage;
    private TranslatorInterface $translator;
    private GatewayInterface    $postRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        SessionInterface $session,
        TranslatorInterface $translator
    )
    {
        $this->flashMessage   = $session->getFlashBag();
        $this->translator     = $translator;
        $this->postRepository = $postRepository;
    }

    /**
     * @param mixed $data
     * @param array $options
     * @throws BadEntityFormHandler
     */
    protected function process($data, array $options): void
    {
        if (!$data instanceof Post) {
            throw new BadEntityFormHandler("Only " . Post::class . " entity is allowed here");
        }
        // Save data
        $this->postRepository->save($data);
        // Creation or edition ?
        $creation = $options['creation'] ?? false;
        // Add flash message
        $message = $creation ? 'action.add.success' : 'action.edit.success';
        $this->flashMessage->add(
            'success',
            $this->translator->trans($message, [], 'post')
        );
    }

    protected function provideFormTypeClassName(): string
    {
        return PostType::class;
    }
}
