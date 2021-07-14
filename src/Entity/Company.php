<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @ORM\Table(
 *     uniqueConstraints={
 *        @ORM\UniqueConstraint(name="company_user", columns={"name", "user_id"})
 * })
 * @Vich\Uploadable
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Amenity::class, inversedBy="company")
     */
    private $amenities;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="companies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $MondayStart;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $MondayEnd;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $TuesdayStart;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $TuesdayEnd;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $WednesdayStart;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $WednesdayEnd;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $ThursdayStart;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $ThursdayEnd;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $FridayStart;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $FridayEnd;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $SaturdayStart;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $SaturdayEnd;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $SundayStart;

    /**
     * @ORM\Column(type="string", length=5, nullable=false)
     */
    private $SundayEnd;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="listing_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\OneToOne(targetEntity=ImageGroup::class, cascade={"persist", "remove"})
     */
    private $imagegroup;

    /**
     * @ORM\OneToMany(targetEntity=Listing::class, mappedBy="company")
     */
    private $listings;

    public function __construct()
    {
        $this->amenities = new ArrayCollection();
        $this->listings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Amenity[]
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(Amenity $amenity): self
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities[] = $amenity;
            $amenity->setCompany($this);
        }

        return $this;
    }

    public function removeAmenity(Amenity $amenity): self
    {
        if ($this->amenities->removeElement($amenity)) {
            // set the owning side to null (unless already changed)
            if ($amenity->getCompany() === $this) {
                $amenity->setCompany(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMondayStart(): ?string
    {
        return $this->MondayStart;
    }

    public function setMondayStart(string $MondayStart): self
    {
        $this->MondayStart = $MondayStart;

        return $this;
    }

    public function getMondayEnd(): ?string
    {
        return $this->MondayEnd;
    }

    public function setMondayEnd(string $MondayEnd): self
    {
        $this->MondayEnd = $MondayEnd;

        return $this;
    }

    public function getTuesdayStart(): ?string
    {
        return $this->TuesdayStart;
    }

    public function setTuesdayStart(string $TuesdayStart): self
    {
        $this->TuesdayStart = $TuesdayStart;

        return $this;
    }

    public function getTuesdayEnd(): ?string
    {
        return $this->TuesdayEnd;
    }

    public function setTuesdayEnd(string $TuesdayEnd): self
    {
        $this->TuesdayEnd = $TuesdayEnd;

        return $this;
    }

    public function getWednesdayStart(): ?string
    {
        return $this->WednesdayStart;
    }

    public function setWednesdayStart(string $WednesdayStart): self
    {
        $this->WednesdayStart = $WednesdayStart;

        return $this;
    }

    public function getWednesdayEnd(): ?string
    {
        return $this->WednesdayEnd;
    }

    public function setWednesdayEnd(string $WednesdayEnd): self
    {
        $this->WednesdayEnd = $WednesdayEnd;

        return $this;
    }

    public function getThursdayStart(): ?string
    {
        return $this->ThursdayStart;
    }

    public function setThursdayStart(string $ThursdayStart): self
    {
        $this->ThursdayStart = $ThursdayStart;

        return $this;
    }

    public function getThursdayEnd(): ?string
    {
        return $this->ThursdayEnd;
    }

    public function setThursdayEnd(string $ThursdayEnd): self
    {
        $this->ThursdayEnd = $ThursdayEnd;

        return $this;
    }

    public function getFridayStart(): ?string
    {
        return $this->FridayStart;
    }

    public function setFridayStart(string $FridayStart): self
    {
        $this->FridayStart = $FridayStart;

        return $this;
    }

    public function getFridayEnd(): ?string
    {
        return $this->FridayEnd;
    }

    public function setFridayEnd(?string $FridayEnd): self
    {
        $this->FridayEnd = $FridayEnd;

        return $this;
    }

    public function getSaturdayStart(): ?string
    {
        return $this->SaturdayStart;
    }

    public function setSaturdayStart(?string $SaturdayStart): self
    {
        $this->SaturdayStart = $SaturdayStart;

        return $this;
    }

    public function getSaturdayEnd(): ?string
    {
        return $this->SaturdayEnd;
    }

    public function setSaturdayEnd(?string $SaturdayEnd): self
    {
        $this->SaturdayEnd = $SaturdayEnd;

        return $this;
    }

    public function getSundayStart(): ?string
    {
        return $this->SundayStart;
    }

    public function setSundayStart(?string $SundayStart): self
    {
        $this->SundayStart = $SundayStart;

        return $this;
    }

    public function getSundayEnd(): ?string
    {
        return $this->SundayEnd;
    }

    public function setSundayEnd(?string $SundayEnd): self
    {
        $this->SundayEnd = $SundayEnd;

        return $this;
    }

    public function getImagegroup(): ?ImageGroup
    {
        return $this->imagegroup;
    }

    public function setImagegroup(?ImageGroup $imagegroup): self
    {
        $this->imagegroup = $imagegroup;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string|null $imageName
     */
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    /**
     * @return Collection|Listing[]
     */
    public function getListings(): Collection
    {
        return $this->listings;
    }

    public function addListing(Listing $listing): self
    {
        if (!$this->listings->contains($listing)) {
            $this->listings[] = $listing;
            $listing->setCompany($this);
        }

        return $this;
    }

    public function removeListing(Listing $listing): self
    {
        if ($this->listings->removeElement($listing)) {
            // set the owning side to null (unless already changed)
            if ($listing->getCompany() === $this) {
                $listing->setCompany(null);
            }
        }

        return $this;
    }
}
