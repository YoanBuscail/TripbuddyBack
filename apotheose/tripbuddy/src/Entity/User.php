<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ email ne peut pas être vide.")
     * @Assert\Email(message="Veuillez entrer une adresse email valide.")
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ prénom ne peut pas être vide.")
     * @Assert\Length(min=2, max=255, minMessage="Le prénom doit comporter au moins {{ limit }} caractères.", maxMessage="Le prénom ne peut pas dépasser {{ limit }} caractères.")
     * @Groups({"user"})
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ nom de famille ne peut pas être vide.")
     * @Assert\Length(min=2, max=255, minMessage="Le nom de famille doit comporter au moins {{ limit }} caractères.", maxMessage="Le nom de famille ne peut pas dépasser {{ limit }} caractères.")
     * @Groups({"user"})
     */
    private $lastname;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le champ mot de passe ne peut pas être vide.")
     * @Assert\Length(min=6, minMessage="Le mot de passe doit comporter au moins {{ limit }} caractères.")
     */
    private $password;
    
    /**
     * @ORM\Column(type="json")
     * @Groups({"user"})
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Itinerary::class, mappedBy="user")
     * @Groups({"user"})
     */
    private $itinerary;

    public function __construct()
    {
        $this->itinerary = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

     /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Itinerary>
     */
    public function getItinerary(): Collection
    {
        return $this->itinerary;
    }

    public function addItinerary(Itinerary $itinerary): self
    {
        if (!$this->itinerary->contains($itinerary)) {
            $this->itinerary[] = $itinerary;
            $itinerary->setUser($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): self
    {
        if ($this->itinerary->removeElement($itinerary)) {
            // set the owning side to null (unless already changed)
            if ($itinerary->getUser() === $this) {
                $itinerary->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}