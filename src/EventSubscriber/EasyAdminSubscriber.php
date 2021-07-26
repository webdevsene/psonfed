<?php

# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use DateTime;
use App\Entity\User;
use App\Entity\Dossier;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    # private $slugger;

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        # $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setDossierSlugAndDateAndUser'],
            BeforeEntityPersistedEvent::class => ['setUserPasswordAndRoles']
        ];
    }

    public function setDossierSlugAndDateAndUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Dossier)) {
            return;
        }

        /*$slug = $this->slugger->slugify($entity->getTitle());
        $entity->setSlug($slug); */
        
        $now = new DateTime('now'); 
        $entity->setCreatedAt($now);
        
    }
    
    /**
     * on souhaite definir dans ce qui suit une methode 
     * pour hasher le mot_de_pass de l'utilisateur avant/après persistance en BD
     */
    public function setUserPasswordAndRoles(BeforeEntityPersistedEvent $event){
        
        $entity_user = $event->getEntityInstance();
        
        if (!($entity_user instanceof User)) {
            return;
        }
        
        // as default context pw is @Enga2013@ and role user is ROLE_USER
        $entity_user->setPassword($this->passwordEncoder->hashPassword($entity_user,$entity_user->getPassword()));

    }
}
?>