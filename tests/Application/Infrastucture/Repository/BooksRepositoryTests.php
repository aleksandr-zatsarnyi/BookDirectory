<?php

namespace App\Tests\Application\Infrastucture\Repository;

use App\Application\Domain\Entity\Books;
use App\Application\Infrastructure\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BooksRepositoryTests extends KernelTestCase {

    private $entityManager;
    private $bookRepository;

    protected function setUp(): void {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bookRepository = $this->entityManager
            ->getRepository(Books::class);
    }
    public function testSave() {
        $user = new Books('title', 'desc', 'url', ['author'], '2017');
        $this->bookRepository->save($user);

        $actualUser = $this->bookRepository->findByTitle($user->getTitle());
        $this->assertEquals($actualUser, $user);
    }
}