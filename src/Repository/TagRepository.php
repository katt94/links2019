<?php
/**
 * Tag repository file.
 */

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use ReflectionClass;
use ReflectionException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TagRepository.
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    /**
     * TagRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Save record.
     *
     * @param Tag $tag
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function save(Tag $tag): void
    {
        $this->_em->persist($tag);
        $this->removeDuplicates($this->_em->getUnitOfWork()->getScheduledEntityInsertions());
        $this->_em->flush($tag);
    }

    /**
     * Delete record.
     *
     * @param Tag $tag
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Tag $tag): void
    {
        $this->_em->remove($tag);
        $this->_em->flush($tag);
    }

    /**
     * Remove duplicates from tags.
     *
     *
     * @param mixed $insertions
     *
     * @throws ORMException
     * @throws ReflectionException
     */
    protected function removeDuplicates($insertions)
    {
        foreach ($insertions as $key => $insertion) {
            $shortClassName = (new ReflectionClass($insertion))->getShortName();

            /** @var mixed $insertions */
            foreach ($insertions as $possibleOtherKey => $otherInsertion) {
                $shortOtherClassName = (new ReflectionClass($insertion))->getShortName();

                if ($shortClassName === $shortOtherClassName
                    && $insertion->getId() === $otherInsertion->getId()
                    && $key !== $possibleOtherKey
                ) {
                    $this->_em->remove($otherInsertion);
                }
            }
        }
    }

    // /**
    //  * @return Tag[] Returns an array of Tag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
