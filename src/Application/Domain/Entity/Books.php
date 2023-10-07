<?php

namespace App\Application\Domain\Entity;

use App\Shared\Service\UlidService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function setImagePath(?string $imagePath): void {
        $this->imagePath = $imagePath;
    }

    public function setAuthors(Collection $authors): void {
        $this->authors = $authors;
    }

    public function setPublicationDate(string $publicationDate): void {
        $this->publicationDate = $publicationDate;
    }

    public function addAuthor(Authors $author): void {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->addBook($this);
        }
    }

    public function clearAuthors(): void {
        $this->authors->clear();
    }
}