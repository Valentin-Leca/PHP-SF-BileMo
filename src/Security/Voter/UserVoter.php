<?php

namespace App\Security\Voter;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter {

    const VIEW = 'VIEW';
    const UPDATE = 'UPDATE';
    const DELETE = 'DELETE';


    protected function supports(string $attribute, mixed $subject): bool {

        if (!in_array($attribute, [self::VIEW, self::UPDATE, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {

        $customer = $token->getUser();

        if (!$customer instanceof Customer) {
            return false;
        }

        $user = $subject;

        return match ($attribute) {
          self::VIEW => $this->canView($customer, $user),
          self::UPDATE, self::DELETE => $this->canEditOrDelete($customer, $user),
          default => throw new \LogicException('This code should not be reached!')
        };


    }

    private function canView(Customer $customer, User $user): bool {

        // if they can edit, they can view
        if ($this->canEditOrDelete($customer, $user)) {
            return true;
        }

        // the Post object could have, for example, a method `isPrivate()`
        return false;
    }

    private function canEditOrDelete(Customer $customer, User $user): bool {

        // this assumes that the Post object has a `getOwner()` method
        return $user->getCustomer() === $customer;
    }
}
