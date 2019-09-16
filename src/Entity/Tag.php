<?php
/**
 * Tag entity file.
 */

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(
 *     name="tag",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="tag_name_idx",
 *              columns={"name"},
 *          )
 *     }
 * )
 */
class Tag
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Created at.
     *
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime", name="created_at")
     *
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * Updated at.
     *
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime", name="updated_at")
     *
     * @Assert\DateTime
     */
    private $updatedAt;

    /**
     * Name.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Links relation.
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Link", mappedBy="tags")
     */
    private $links;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
        $this->links = new ArrayCollection();
    }

    /**
     * Return id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Return name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name value.
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return created at.
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set created at value.
     *
     * @param DateTimeInterface $createdAt
     *
     * @return Tag
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Return updated at.
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set updated at value.
     *
     * @param DateTimeInterface $updatedAt
     *
     * @return Tag
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Return links.
     *
     * @return Collection|Link[]
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    /**
     * Add link to collection.
     *
     * @param Link $link
     *
     * @return Tag
     */
    public function addLink(Link $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->addTag($this);
        }

        return $this;
    }

    /**
     * Remove link from collection.
     *
     * @param Link $link
     *
     * @return Tag
     */
    public function removeLink(Link $link): self
    {
        if ($this->links->contains($link)) {
            $this->links->removeElement($link);
            $link->removeTag($this);
        }

        return $this;
    }
}
