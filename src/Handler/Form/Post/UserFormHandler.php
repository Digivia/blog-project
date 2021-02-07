<?php

declare(strict_types=1);

namespace App\Handler\Form\Post;

use App\Entity\User\User;
use App\Exception\BadEntityFormHandler;
use App\Form\User\UserType;
use App\Gateway\GatewayInterface;
use App\Repository\UserRepositoryInterface;
use Digivia\FormHandler\Handler\AbstractHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserFormHandler
 * @package App\Handler\Form\Post
 */
final class UserFormHandler extends AbstractHandler
{
    private FlashBagInterface   $flashMessage;
    private TranslatorInterface $translator;
    private GatewayInterface    $userRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        SessionInterface $session,
        TranslatorInterface $translator,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->flashMessage    = $session->getFlashBag();
        $this->translator      = $translator;
        $this->userRepository  = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param mixed $data
     * @param array $options
     * @throws BadEntityFormHandler
     */
    protected function process($data, array $options): void
    {
        if (!$data instanceof User) {
            throw new BadEntityFormHandler("Only " . User::class . " entity is allowed here");
        }
        // Encrypt Password if needed
        if (null !== $data->getPlainPassword() && strlen($data->getPlainPassword())) {
            $data->setPassword($this->passwordEncoder->encodePassword($data, $data->getPlainPassword()));
        }
        // Save data
        $this->userRepository->save($data);
        // Creation or edition ?
        $creation = $options['creation'] ?? false;
        // Add flash message
        $message = $creation ? 'action.add.success' : 'action.edit.success';
        $this->flashMessage->add(
            'success',
            $this->translator->trans($message, [], 'user')
        );
    }

    protected function provideFormTypeClassName(): string
    {
        return UserType::class;
    }
}
