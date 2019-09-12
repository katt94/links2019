<?php
/**
 * Link repository file.
 */

namespace App\Repository;

use App\Entity\Link;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class LinkRepository.
 *
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    /**
     * LinkRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * Save record.
     *
     * @param Link $link
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Link $link): void
    {
        $this->_em->persist($link);
        $this->_em->flush($link);
    }

    /**
     * Delete record.
     *
     * @param Link $link
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Link $link): void
    {
        $this->_em->remove($link);
        $this->_em->flush($link);
    }

    // /**
    //  * @return Link[] Returns an array of Link objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Link
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
