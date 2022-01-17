<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
    }

    /**
     * Check si user est validé
     *
     * @param UserInterface $user
     *
     * @return void
     *
     * @throws AccountUnconfirmedException
     */
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        // user account is no activated, the user may be notified
        if (!$user->getIsActivated()) {
            throw new AccountExpiredException('Veuillez valider votre compte avant de vous connecter.');
        }
    }
}
