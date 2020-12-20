<?php

namespace App\Handler\Form\Category;

use App\Entity\Blog\Category;
use App\Exception\BadEntityFormHandler;
use App\Form\Blog\CategoryType;
use Digivia\FormHandler\Handler\AbstractHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryFormHandler extends AbstractHandler
{
    private EntityManagerInterface $entityManager;
    private FlashBagInterface      $flashMessage;
    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage  = $session->getFlashBag();
        $this->translator = $translator;
    }

    protected function process($data, array $options): void
    {
        if (!$data instanceof Category) {
            throw new BadEntityFormHandler("Only " . Category::class . " entity is allowed here");
        }
        // Save data
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        // Creation or edition ?
        $creation = $options['creation'] ?? false;
        // Add flash message
        $message = $creation ? 'action.add.success' : 'action.edit.success';
        $this->flashMessage->add(
            'success',
            $this->translator->trans($message, [], 'category')
        );
    }

    protected function provideFormTypeClassName(): string
    {
        return CategoryType::class;
    }
}
