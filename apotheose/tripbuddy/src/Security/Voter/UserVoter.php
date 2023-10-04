<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // Vérifier si cet attribut est pris en charge par le voteur.
        return $attribute === 'ROLE_USER';
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        // Vérifier le rôle de l'utilisateur.
        return in_array('ROLE_USER', $token->getRoleNames());
    }
}