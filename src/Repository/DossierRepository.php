<?php

namespace App\Repository;

use App\Entity\Dossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossier[]    findAll()
 * @method Dossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossier::class);
    }

    /**
     * Recherche les dossiers sur liste en fonction du formulaire
     *
     * @return void
     */
    public function search($mots)
    {        
        $query = null;
        if ($mots != null) {
            
            #$query->where('d.active = 1');
            
            $query = $this->createQueryBuilder('d')
                          ->andWhere('MATCH_AGAINST(d.titre, d.analyse) AGAINST(:mots boolean)>0')
                          ->setParameter('mots', $mots)
            ;
            /* $query->andWhere('MATCH_AGAINST(d.titre, d.analyse) AGAINST
            (:mots boolean)>0')
                ->setParameters('mots', $mots)
            ; */
        }

        return $query->getQuery()
                     ->getResult();

    }

    // /**
    //  * @return Dossier[] Returns an array of Dossier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dossier
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
