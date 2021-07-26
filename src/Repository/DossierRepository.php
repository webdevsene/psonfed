<?php

namespace App\Repository;

use App\Entity\Dossier;
use App\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use function Symfony\Component\String\u;

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
     *
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

    /* public function findLatest(int $page = 1, Tag $tag = null): Paginator
    {
        $qb = $this->createQueryBuilder('d')
            ->addSelect('a', 't')
            ->innerJoin('d.author', 'a')
            ->leftJoin('p.tags', 't')
            ->where('p.publishedAt <= :now')
            ->orderBy('p.publishedAt', 'DESC')
            ->setParameter('now', new \DateTime())
        ;

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                ->setParameter('tag', $tag);
        }

        return (new Paginator($qb))->paginate($page);
    } */


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

    
   /*  public function findOneByTitre($value): ?Dossier
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    } */
}
