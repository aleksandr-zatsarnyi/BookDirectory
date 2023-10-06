<?php

namespace App\Application\Domain\Entity;

use App\Shared\Service\UlidService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Authors {
    private string $id;
    private string $firstName;
    private ?string $secondName;
    private string $lastName;
    private Collection $booksss;

    /**
     * @param string $firstName
     * @param string $secondName
     * @param string $lastName
     */
    public function __construct(string $firstName, string $secondName, string $lastName) {
        $this->id = UlidService::generate();
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->lastName = $lastName;
        $this->books = new ArrayCollection();
    }

    public function getId(): string {
        return $this->id;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getSecondName(): ?string {
        return $this->secondName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function addBook(Books $book): void {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->addAuthor($this);
        }
    }

    public function postLoad(): void {
        $this->books = new ArrayCollection();
    }

    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    public function setSecondName(?string $secondName): void {
        $this->secondName = $secondName;
    }

    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    public function setBooks(Collection $books): void {
        $this->books = $books;
    }

    public function getBooks(): Collection {
        return $this->books;
    }
}