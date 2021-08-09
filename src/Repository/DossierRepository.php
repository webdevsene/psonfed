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
     * Recherche les dossiers sur liste en fonction du formulaire     *
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
        }

        return $query->getQuery()
                     ->getResult();

    }    
    
    /**
     * @return Dossier[]
     */
    public function findBySearchQuery(string $query, int $limit = Paginator::PAGE_SIZE): array
    {
        $searchTerms = $this->extractSearchTerms($query);
        
        if (0 === \count($searchTerms)) {
            return [];
        }
        
        $queryBuilder = $this->createQueryBuilder('p');
        
        foreach ($searchTerms as $key => $term) {
            $queryBuilder
            ->orWhere('p.titre LIKE :t_'.$key)
            ->setParameter('t_'.$key, '%'.$term.'%')
            ;
        }
        
        return $queryBuilder
        ->orderBy('p.createdAt', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
    }
    
    /**
     * Transforms the search string into an array of search terms.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $searchQuery = u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim();
        $terms = array_unique($searchQuery->split(' '));

        // ignore the search terms that are too short
        return array_filter($terms, static function ($term) {
            return 2 <= $term->length();
        });
    }

    /**
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllDossier()
    {
        return $this->createQueryBuilder('a')
                    ->select('COUNT(a.id)')
                    ->getQuery()
                    ->getSingleScalarResult()
        ;

        // $queryBuilder = $this->createQueryBuilder('a');
        //$queryBuilder->select('COUNT(a.id) as value');
        // $queryBuilder->select('COUNT(a.id)');
        //return $queryBuilder->getQuery()->getOneOrNullResult();
        // return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    /*  public function findOneByTitre($value): ?Dossier
    {
        return $this->createQueryBuilder('d')
        ->andWhere('d.exampleField = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    } */

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
}