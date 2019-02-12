<?php

namespace App\EventSubscriber;

use App\Entity\UserEntity;
use App\Entity\UserPermissionGroupEntity;
use App\Repository\UserPermissionGroupEntityRepository;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RedirectAfterRegistrationSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private $router;
    private $groupEntityRepository;
    private $userManager;
    public function __construct(RouterInterface $router, UserManagerInterface $userManager, UserPermissionGroupEntityRepository $groupEntityRepository)
    {
        $this->router = $router;
        $this->groupEntityRepository = $groupEntityRepository;
        $this->userManager = $userManager;
    }

    public function onFosUserRegistrationSuccess(FormEvent $event)
    {
        /** @var UserEntity $user **/
        $user = $event->getForm()->getData();
        $userGroupEntity = $this->groupEntityRepository->findOneBy(array("groupAlias" => "user"));
        $user->setUserPermissionGroupEntity($userGroupEntity);
        $this->userManager->updateUser($user);
        // main is your firewall's name
        $url = $this->getTargetPath($event->getRequest()->getSession(), 'main');

        if (!$url) {
            $url = $this->router->generate('front_index');
        }

        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
           FOSUserEvents::REGISTRATION_SUCCESS => 'onFosUserRegistrationSuccess',
        ];
    }
}
