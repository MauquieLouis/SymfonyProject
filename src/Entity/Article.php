<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $heartCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ImageFileName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $checked;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getHeartCount(): ?int
    {
        return $this->heartCount;
    }

    public function setHeartCount(int $heartCount): self
    {
        $this->heartCount = $heartCount;

        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->ImageFileName;
    }

    public function setImageFileName(?string $ImageFileName): self
    {
        $this->ImageFileName = $ImageFileName;

        return $this;
    }
    public function getImagePath()
    {
        return '' .$this->getImageFilename();
    }
    public function incrementHeartCount(): self
    {
        $this->heartCount = $this->heartCount + 1;
        
        return $this;
    }

    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (stripos($this->getTitle(), 'Le Titre') !== false) {
            $context->buildViolation('Le titre n\'est pas un super titre')
            ->atPath('title')
            ->addViolation();
        }
    }

    public function getChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(?bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }

}
