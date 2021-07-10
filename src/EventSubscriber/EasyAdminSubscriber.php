<?php

# src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Dossier;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    # private $slugger;
    #private $dossier;

    public function __construct()
    {
        # $this->slugger = $slugger;
        #$this->dossier = $dossier;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setDossierSlugAndDateAndUser'],
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
    public function setUserPasswordAndHash(){}
}
?>