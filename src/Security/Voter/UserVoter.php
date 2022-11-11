<?php

namespace App\Security\Voter;

use App\Entity\Customer;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter {
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool {

        return in_array($attribute, ['VIEW', 'EDIT', 'DELETE'])
            && $subject instanceof Customer;

    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {

        dd($subject);

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_CUSTOMER')) {
            return true;
        }

        switch ($attribute) {
            case 'VIEW':
            case 'EDIT':
            case 'DELETE':
                if ($user === $subject->getUser()) {
                    return true;
                }
            break;
        }

        return false;
    }
}
