<?php

namespace App\Entity;

use App\Repository\ImageGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImageGroupRepository::class)
 * @Vich\Uploadable
 */
class ImageGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image1;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image1")
     */
    private $file1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image2;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image2")
     */
    private $file2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image3;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image3")
     */
    private $file3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image4;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image4")
     */
    private $file4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image5;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image5")
     */
    private $file5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image6;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image6")
     */
    private $file6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image7;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image7")
     */
    private $file7;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image8;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image8")
     */
    private $file8;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image9;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="gallery_listing", fileNameProperty="image9")
     */
    private $file9;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): self
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(?string $image2): self
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->image3;
    }

    public function setImage3(?string $image3): self
    {
        $this->image3 = $image3;

        return $this;
    }

    public function getImage4(): ?string
    {
        return $this->image4;
    }

    public function setImage4(?string $image4): self
    {
        $this->image4 = $image4;

        return $this;
    }

    public function getImage5(): ?string
    {
        return $this->image5;
    }

    public function setImage5(?string $image5): self
    {
        $this->image5 = $image5;

        return $this;
    }

    public function getImage6(): ?string
    {
        return $this->image6;
    }

    public function setImage6(?string $image6): self
    {
        $this->image6 = $image6;

        return $this;
    }

    public function getImage7(): ?string
    {
        return $this->image7;
    }

    public function setImage7(?string $image7): self
    {
        $this->image7 = $image7;

        return $this;
    }

    public function getImage8(): ?string
    {
        return $this->image8;
    }

    public function setImage8(?string $image8): self
    {
        $this->image8 = $image8;

        return $this;
    }

    public function getImage9(): ?string
    {
        return $this->image9;
    }

    public function setImage9(?string $image9): self
    {
        $this->image9 = $image9;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile1(): ?File
    {
        return $this->file1;
    }

    /**
     * @param File|null $file1
     */
    public function setFile1(?File $file1): void
    {
        $this->file1 = $file1;
    }

    /**
     * @return File|null
     */
    public function getFile2(): ?File
    {
        return $this->file2;
    }

    /**
     * @param File|null $file2
     */
    public function setFile2(?File $file2): void
    {
        $this->file2 = $file2;
    }

    /**
     * @return File|null
     */
    public function getFile3(): ?File
    {
        return $this->file3;
    }

    /**
     * @param File|null $file3
     */
    public function setFile3(?File $file3): void
    {
        $this->file3 = $file3;
    }

    /**
     * @return File|null
     */
    public function getFile4(): ?File
    {
        return $this->file4;
    }

    /**
     * @param File|null $file4
     */
    public function setFile4(?File $file4): void
    {
        $this->file4 = $file4;
    }

    /**
     * @return File|null
     */
    public function getFile5(): ?File
    {
        return $this->file5;
    }

    /**
     * @param File|null $file5
     */
    public function setFile5(?File $file5): void
    {
        $this->file5 = $file5;
    }

    /**
     * @return File|null
     */
    public function getFile6(): ?File
    {
        return $this->file6;
    }

    /**
     * @param File|null $file6
     */
    public function setFile6(?File $file6): void
    {
        $this->file6 = $file6;
    }

    /**
     * @return File|null
     */
    public function getFile7(): ?File
    {
        return $this->file7;
    }

    /**
     * @param File|null $file7
     */
    public function setFile7(?File $file7): void
    {
        $this->file7 = $file7;
    }

    /**
     * @return File|null
     */
    public function getFile8(): ?File
    {
        return $this->file8;
    }

    /**
     * @param File|null $file8
     */
    public function setFile8(?File $file8): void
    {
        $this->file8 = $file8;
    }

    /**
     * @return File|null
     */
    public function getFile9(): ?File
    {
        return $this->file9;
    }

    /**
     * @param File|null $file9
     */
    public function setFile9(?File $file9): void
    {
        $this->file9 = $file9;
    }
}
