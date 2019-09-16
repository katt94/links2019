<?php
/**
 * Counter entity file.
 */

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Counter.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CounterRepository")
 * @ORM\Table(name="counter")
 */
class Counter
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
     * Link relation.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Link", inversedBy="counters")
     */
    private $link;

    /**
     * Return id.
     *
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return Counter
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
     * @return Counter
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Return link.
     *
     * @return Link|null
     */
    public function getLink(): ?Link
    {
        return $this->link;
    }

    /**
     * Set link value.
     *
     * @param Link|null $link
     *
     * @return Counter
     */
    public function setLink(?Link $link): self
    {
        $this->link = $link;

        return $this;
    }
}
