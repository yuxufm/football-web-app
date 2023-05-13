<?php

namespace App\Repository;

use App\Entity\Players;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Players>
 *
 * @method Players|null find($id, $lockMode = null, $lockVersion = null)
 * @method Players|null findOneBy(array $criteria, array $orderBy = null)
 * @method Players[]    findAll()
 * @method Players[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Players::class);
    }

    public function save(Players $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Players $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function paginate($page = 1, $limit = 5, $teamId, $filterBy = 'team'): Paginator
    {
        if ($filterBy === 'team') {
            $query = $this->createQueryBuilder('p')
                ->orderBy('p.created_at', 'DESC')
                ->andWhere('p.team = :teamId')
                ->setParameter('teamId', $teamId)
                ->getQuery();
        } else if ($filterBy === 'player_transfers') {
            $query = $this->createQueryBuilder('p')
                ->orderBy('p.created_at', 'DESC')
                ->andWhere('p.team != :teamId')
                ->andWhere('p.is_open_for_transfer = 1')
                ->setParameter('teamId', $teamId)
                ->getQuery();
        }


        $paginator = new Paginator($query);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    public function countPlayersExcludingTeam($teamId)
    {
        $count = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->andWhere('p.team != :teamId')
            ->andWhere('p.is_open_for_transfer = 1')
            ->setParameter('teamId', $teamId)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $count;
    }

    //    /**
    //     * @return Players[] Returns an array of Players objects
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

    //    public function findOneBySomeField($value): ?Players
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
