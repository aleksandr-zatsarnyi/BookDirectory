<?php

namespace App\Application\Domain\Entity;

use App\Shared\Service\UlidService;

class Books {
    private string $id;

    private string $title;

    private ?string $description;

    private ?string $imagePath;

    private array $authors;

    private string $publicationDate;

    /**
     * @param string $title
     * @param string $description
     * @param string $imagePath
     * @param array $authors
     * @param string $publicationDate
     */
    public function __construct(string $title, string $description, string $imagePath, array $authors, string $publicationDate) {
        $this->id = UlidService::generate();
        $this->title = $title;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->authors = $authors;
        $this->publicationDate = $publicationDate;
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

    public function getAuthors(): array {
        return $this->authors;
    }

    public function getPublicationDate(): string {
        return $this->publicationDate;
    }
}