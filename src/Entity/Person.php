<?php

namespace App\Entity;

use App\Utils\Phone;
use Cocur\Slugify\Slugify;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    public const GENDER = [
        NULL => "",
        1 => "Femme",
        2 => "Homme",
        3 => "Non renseigné"
    ];

    public const NATIONALITY = [
        NULL => "",
        1 => "France",
        2 => "Union-Européenne",
        3 => "Hors-UE",
        4 => "Apatride",
        5 => "Non renseignée"
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)   
     * @Assert\Length(max=50,maxMessage="Le nom est trop long (50 caractères max.).")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message = "Le nom ne peut être vide.")
     * @Assert\Length(max=50,maxMessage="Le prénom est trop long (50 caractères max).")     
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(max=50,maxMessage="Le nom d'usage est trop long (50 caractères max).")     
     */
    private $usename;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthdate;

    private $age;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $nationality;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Regex(pattern="^0[1-9]([-._/ ]?[0-9]{2}){4}$^", match=true, message="Le numéro de téléphone est incorrect.")
     */
    private $phone1;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Regex(pattern="^0[1-9]([-._/ ]?[0-9]{2}){4}$^", match=true, message="Le numéro de téléphone est incorrect.")
     */
    private $phone2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Email(message="L'adresse email n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="people")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="peopleUpdated")
     */
    private $updatedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RolePerson", mappedBy="person", orphanRemoval=true, cascade={"persist"})
     */
    private $rolesPerson;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->rolesPerson = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSlug(): string
    {
        $slugify = new Slugify();
        
        return $slugify->slugify($this->firstname . "-" . $this->lastname); 
    }

    public function getUsename(): ?string
    {
        return $this->usename;
    }

    public function setUsename(string $usename): self
    {
        $this->usename = $usename;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getAge(): ?int
    {
        if ($this->birthdate) {
            $now   = new \DateTime();
            return $this->birthdate->diff($now)->y;
        } else {
            return NULL;
        }
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGenderType() 
    {
        return self::GENDER[$this->gender];
    }
    
    public function getNationality(): ?int
    {
        return $this->nationality;
    }

    public function setNationality(?int $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getNationalityType() 
    {
        return self::NATIONALITY[$this->nationality];
    }

    public function getPhone1(): ?string
    {
        return Phone::getPhoneFormat($this->phone1);
    }

    public function setPhone1(?string $phone1): self
    {
        $this->phone1 = Phone::formatPhone($phone1);

        return $this;
    }

    public function getPhone2(): ?string
    {
        return Phone::getPhoneFormat($this->phone2);
    }

    public function setPhone2(?string $phone2): self
    {
        $this->phone2 = Phone::formatPhone($phone2);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @return Collection|RolePerson[]
     */
    public function getRolesPerson(): Collection
    {
        return $this->rolesPerson;
    }

    public function addRolesPerson(RolePerson $rolesPerson): self
    {
        if (!$this->rolesPerson->contains($rolesPerson)) {
            $this->rolesPerson[] = $rolesPerson;
            $rolesPerson->setPerson($this);
        }

        return $this;
    }

    public function removeRolesPerson(RolePerson $rolesPerson): self
    {
        if ($this->rolesPerson->contains($rolesPerson)) {
            $this->rolesPerson->removeElement($rolesPerson);
            // set the owning side to null (unless already changed)
            if ($rolesPerson->getPerson() === $this) {
                $rolesPerson->setPerson(null);
            }
        }
        return $this;
    }
}
