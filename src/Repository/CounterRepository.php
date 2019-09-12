<?php
/**
 * Counter repository file.
 */

namespace App\Repository;

use App\Entity\Counter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CounterRepository.
 *
 * @method Counter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Counter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Counter[]    findAll()
 * @method Counter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CounterRepository extends ServiceEntityRepository
{
    /**
     * CounterRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Counter::class);
    }

    /**
     * Save record.
     *
     * @param Counter $counter
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Counter $counter): void
    {
        $this->_em->persist($counter);
        $this->_em->flush($counter);
    }

    /**
     * Delete record.
     *
     * @param Counter $counter
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Counter $counter): void
    {
        $this->_em->remove($counter);
        $this->_em->flush($counter);
    }

    // /**
    //  * @return Counter[] Returns an array of Counter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Counter
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
