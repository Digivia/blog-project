<?php

declare(strict_types=1);

namespace App\Handler\Form\Category;

use App\Entity\Blog\Category;
use App\Exception\BadEntityFormHandler;
use App\Form\Blog\CategoryType;
use App\Gateway\GatewayInterface;
use App\Repository\CategoryRepositoryInterface;
use Digivia\FormHandler\Handler\AbstractHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CategoryFormHandler
 * @package App\Handler\Form\Category
 */
final class CategoryFormHandler extends AbstractHandler
{
    private FlashBagInterface      $flashMessage;
    private TranslatorInterface $translator;
    private GatewayInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->flashMessage  = $session->getFlashBag();
        $this->translator = $translator;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param mixed $data
     * @param array $options
     * @throws BadEntityFormHandler
     */
    protected function process($data, array $options): void
    {
        if (!$data instanceof Category) {
            throw new BadEntityFormHandler("Only " . Category::class . " entity is allowed here");
        }
        // Save data
        $this->categoryRepository->save($data);
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
