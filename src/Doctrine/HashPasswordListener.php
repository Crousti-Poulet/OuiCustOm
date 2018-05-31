<?php

namespace App\Doctrine;


use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordListener implements EventSubscriber
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User){
            return;
        }
        $this->encodePassword($entity);
    }

     public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User || $entity->getPlainPassword() === ''){
            return;
        }
        $this->encodePassword($entity);
    }

    


    public function encodePassword(User $user)
    {
        if(!empty($user->getPlainPassword())){
            $encoded = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
        }
    }
}