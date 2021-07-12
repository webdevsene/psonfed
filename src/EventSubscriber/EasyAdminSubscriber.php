<?php

# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use DateTime;
use App\Entity\User;
use App\Entity\Dossier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    # private $slugger;
    #private $dossier;

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        # $this->slugger = $slugger;
        #$this->dossier = $dossier;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setDossierSlugAndDateAndUser'],
            BeforeEntityPersistedEvent::class => ['setUserPasswordAndRolesHasher']
        ];
    }

    public function setDossierSlugAndDateAndUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Dossier)) {
            return;
        }

        //possibilité de tester sur l'instanceof User

        /*$slug = $this->slugger->slugify($entity->getTitle());
        $entity->setSlug($slug); */

        $now = new DateTime('now'); 
        $entity->setCreatedAt($now);

        #$dossier = $this->dossier->getCreatedAt(); 
        #$entity->setCreatedAt($dossier);
    }

    /**
     * on souhaite definir dans ce qui suit une methode 
     * pour hasher le mot_de_pass de l'utilisateur avant/après persistance en BD
     */
    public function setUserPasswordAndRolesHasher(BeforeEntityPersistedEvent $event){

        $entity_user = $event->getEntityInstance();

        if (!($entity_user instanceof User)) {
            return;
        }

        #$plainPassword = $entity_user->getPlainPassword();
        // on recuper le plainPassword dans le vrai password
        $entity_user->setPassword($this->passwordEncoder->hashPassword($entity_user, $entity_user->getPlainPassword()));
        $entity_user->setPassword($entity_user->getPlainPassword());

        // persister une role utilisateur
        $role [] = ['ROLE_MANAGER'];
        $entity_user->setRoles($entity_user->getRoles());

    }
}
?>