<?php

namespace App\Repository;

use App\Entity\PlayerTransfers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerTransfers>
 *
 * @method PlayerTransfers|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerTransfers|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerTransfers[]    findAll()
 * @method PlayerTransfers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerTransfersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerTransfers::class);
    }

    public function save(PlayerTransfers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PlayerTransfers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PlayerTransfers[] Returns an array of PlayerTransfers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PlayerTransfers
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
