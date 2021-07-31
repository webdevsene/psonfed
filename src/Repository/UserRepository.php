<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * This custom Doctrine repository is empty because so far we don't need any custom
 * method to query for application user information. But it's always a good practice
 * to define a custom repository that will be used when the application grows.
 *
 * See https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return int|mixed|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countAllUser()
    {
        $queryBuilder = $this->createQueryBuilder('a');
        //$queryBuilder->select('COUNT(a.id) as value');
        $queryBuilder->select('COUNT(a.id)');
        //return $queryBuilder->getQuery()->getOneOrNullResult();
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
