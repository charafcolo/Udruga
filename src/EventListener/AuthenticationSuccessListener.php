<?php 

namespace App\EventListener;

use App\Entity\Association;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;


Class AuthenticationSuccessListener 
{

    /**
 * @param AuthenticationSuccessEvent $event
 */
public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
{
    $data = $event->getData();
    /**
     * @var User $user
     */
    $user = $event->getUser();

    if (!$user instanceof UserInterface) {
        return;
    }

    $data['user'] = array(
        'id' => $user->getId(),
        'roles' => $user->getRoles(),
        'firstname' => $user->getFirstName(),
        'lastname' => $user->getLastName(),
        'email' => $user->getEmail(),
        'roles' => $user->getRoles(),
        'events' => $user->getEvents(),
        'association' => $user->getAssociation(),

    );

    $event->setData($data);
}


}