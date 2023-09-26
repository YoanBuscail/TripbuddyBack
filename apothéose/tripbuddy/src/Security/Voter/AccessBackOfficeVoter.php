<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class AccessBackOfficeVoter extends Voter
{
    // Ceci est le ou l'une des actions que traite le voteur
    public const ACCESS = "BACK_OFFICE_ACCESS";

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        // ! un true ici déclenchera la méthode VoteOnAttribute, un false ignorera la suite du code
        return in_array($attribute, [self::ACCESS]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ACCESS:

            // je récupère l'heure
            $now = new \DateTimeImmutable("now", new \DateTimeZone("Europe/Paris"));
            $hour = $now->format("H");

            // je conditionne l'accès selon l'heure
            if($hour >= 20 || $hour < 9){
                // je bloque
                return false;
            }else{
                return true;
            }
                break;
        }

        return false;
    }
}