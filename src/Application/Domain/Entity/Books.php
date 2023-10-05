<?php

namespace App\Application\Domain\Entity;

use App\Shared\Service\UlidService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="books")
 */
class Books {
    private string $id;

    private string $title;

    private ?string $description;

    private ?string $imagePath;

    private Collection $authors;

    private string $publicationDate;

    /**
     * @param string $title
     * @param string $description
     * @param string $imagePath
     * @param string $publicationDate
     */
    public function __construct(string $title, string $description, string $imagePath, string $publicationDate) {
        $this->id = UlidService::generate();
        $this->title = $title;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->publicationDate = $publicationDate;
        $this->authors = new ArrayCollection();
    }

    public function getId(): string {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getImagePath(): ?string {
        return $this->imagePath;
    }

    public function getAuthors(): Collection {
        return $this->authors;
    }

    public function getPublicationDate(): string {
        return $this->publicationDate;
    }

    public function addAuthor(Authors $author): void {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }
    }
}