<?php
/**
 * Link file.
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
 * Class Link.
 *
 * @ORM\Entity(repositoryClass="App\Repository\LinkRepository")
 * @ORM\Table(name="link")
 */
class Link
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
     * User relation.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="links")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Url.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * Hash.
     *
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * Tags relation.
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="links",
     *     cascade={"persist"},
     *     indexBy="id",
     *     fetch="EAGER"
     * )
     * @ORM\JoinTable(name="link_tag")
     */
    private $tags;

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
     * Counters relation.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Counter", mappedBy="link", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $counters;

    /**
     * Link constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->counters = new ArrayCollection();
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
     * Return user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set user value.
     *
     * @param User|null $user
     *
     * @return Link
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Return url.
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * Set url value.
     *
     * @param string $url
     *
     * @return Link
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Return hash.
     *
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * Set hash value.
     *
     * @param string $hash
     *
     * @return Link
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Return tags.
     *
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag to collection.
     *
     * @param Tag $tag
     *
     * @return Link
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Remove tag from collection.
     *
     * @param Tag $tag
     *
     * @return Link
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

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
     * @return Link
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
     * @return Link
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Return counters.
     *
     * @return Collection|Counter[]
     */
    public function getCounters(): Collection
    {
        return $this->counters;
    }

    /**
     * Add counter to collection.
     *
     * @param Counter $counter
     *
     * @return Link
     */
    public function addCounter(Counter $counter): self
    {
        if (!$this->counters->contains($counter)) {
            $this->counters[] = $counter;
            $counter->setLink($this);
        }

        return $this;
    }

    /**
     * Remove counter from collection.
     *
     * @param Counter $counter
     *
     * @return Link
     */
    public function removeCounter(Counter $counter): self
    {
        if ($this->counters->contains($counter)) {
            $this->counters->removeElement($counter);
            // set the owning side to null (unless already changed)
            if ($counter->getLink() === $this) {
                $counter->setLink(null);
            }
        }

        return $this;
    }
}
