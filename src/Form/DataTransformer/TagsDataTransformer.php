<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 */
class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * Tag repository.
     *
     * @var TagRepository|null
     */
    private $repository = null;

    /**
     * TagsDataTransformer constructor.
     *
     * @param TagRepository $repository Tag repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of tags to string of names.
     *
     * @param Collection $tags Tags entity collection
     *
     * @return string Result
     */
    public function transform($tags): string
    {
        if (null == $tags) {
            return '';
        }

        $tagNames = [];

        foreach ($tags as $tag) {
            $tagNames[] = $tag->getName();
        }

        return implode(', ', $tagNames);
    }

    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $value String of tag names
     *
     * @return array Result
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \ReflectionException
     */
    public function reverseTransform($value): array
    {
        $tagNames = explode(',', $value);

        /** @var Tag[] $tags */
        $tags = [];

        foreach ($tagNames as $tagName) {
            if ('' !== trim($tagName)) {
                /** @var Tag $tag */
                $tag = $this->repository->findOneBy(['name' => trim($tagName)]);

                if (null === $tag) {
                    $tag = new Tag();
                    $tag->setName(trim($tagName));
                    $this->repository->save($tag);
                }

                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
