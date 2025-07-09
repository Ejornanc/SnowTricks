<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isVerified()) {
            // Lève une exception si le compte n'est pas activé
            throw new CustomUserMessageAccountStatusException('Votre compte n\'a pas encore été validé.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Pas besoin ici
    }
}
