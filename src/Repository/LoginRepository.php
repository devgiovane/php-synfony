<?php


namespace App\Repository;


use App\Entity\Login;
use Doctrine\ORM\Query;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class LoginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Login::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByEmailOrUsername($value): ?Login
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('l')
            ->from(Login::class, 'l')
            ->where('l.username = :value')
            ->orWhere('l.email = :value')
            ->setParameter('value', $value);

        $q = $qb->getQuery();
        return $q->getOneOrNullResult(Query::HYDRATE_OBJECT);
    }
}
