<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
    }

    public function findAllDone()
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isDone = true')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
    }

    public function findAllInProgress()
    {
        $qb = $this->createQueryBuilder('t');

        return $qb
            ->andWhere('t.isDone = false')
            ->andWhere('t.assignedTo != :val')
            ->setParameter('val', 'null')
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
    }

    public function findAllTodo()
    {

        $qb = $this->createQueryBuilder('t');

        return $this->createQueryBuilder('t')
            ->where('t.isDone = false')
            ->andWhere($qb->expr()->isNull('t.assignedTo'))
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery();
    }

}
