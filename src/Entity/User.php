<?php
/**
 * User file.
 */

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
@ORM\UniqueConstraint(
 *              name="email_idx",
 *              columns={"email"},
 *          )
 *     }
 * )
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, Serializable, EquatableInterface
{
    /**
     * Role user.
     *
     * @constant string ROLE_USER
     */
    const ROLE_USER = 'ROLE_USER';

    /**
     * Role link editor.
     *
     * @constant string ROLE_LINK_EDITOR
     */
    const ROLE_LINK_EDITOR = 'ROLE_LINK_EDITOR';

    /**
     * Role admin.
     *
     * @constant string ROLE_ADMIN
     */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true}
     *     )
     */
    private $id;

    /**
     * E-mail.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128, unique=true)
     *
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * Password.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * Roles.
     *
     * @var array
     *
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * First name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=50, name="first_name")
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255"
     * )
     */
    private $firstName;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Link", mappedBy="user")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $links;

    /**
     * @return string|null
     */
    public function __toString(): string
    {
        return $this->getEmail();
    }

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->links = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;

        // upewniamy się, że user ma prznajmniej rolę ROLE_USER
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles): self
    {
        // make sure admin have not got link editor privilege
        if (in_array('ROLE_LINK_EDITOR', $roles)
        && in_array('ROLE_ADMIN', $roles)) {
            unset($roles['ROLE_LINK_EDITOR']);
        }

        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return User
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // nie potrzebne gdy używamy bcrypt
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return User
     *
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
        // to jest miejsce gdzie kasujemy dane wrażliwe z modelu User
        // $this->fooBar = null;
        $this->password = null;

        return $this;
    }

    /**
     * String representation of object.
     *
     * @see https://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     *
     * @since 5.1.0
     */
    public function serialize()
    {
        $serialized = serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);

        return $serialized;
    }

    /**
     * Constructs the object.
     *
     * @see https://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password
            )
            = unserialize($serialized);
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($this->email !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function roleAdmin()
    {
        return self::ROLE_ADMIN;
    }

    /**
     * @return string
     */
    public function roleUser()
    {
        return self::ROLE_USER;
    }

    /**
     * @return string
     */
    public function roleLinkEditor()
    {
        return self::ROLE_LINK_EDITOR;
    }

    /**
     * @return Collection|Link[]
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    /**
     * @param Link $link
     *
     * @return User
     */
    public function addLink(Link $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setUser($this);
        }

        return $this;
    }

    /**
     * @param Link $link
     *
     * @return User
     */
    public function removeLink(Link $link): self
    {
        if ($this->links->contains($link)) {
            $this->links->removeElement($link);
            // set the owning side to null (unless already changed)
            if ($link->getUser() === $this) {
                $link->setUser(null);
            }
        }

        return $this;
    }
}
