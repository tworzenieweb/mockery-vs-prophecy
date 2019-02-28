<?php

namespace Jobleads\Repository;


use Jobleads\Model\Book;
use Mockery as M;
use Mockery\MockInterface;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Jobleads\Repository\BookRepository
 */
class BookRepositoryTest extends TestCase
{
    /**
     * @covers ::findAll
     * @dataProvider providerFindAll
     * @param PDO $pdo
     * @param array $expectedResults
     */
    public function testFindAll(PDO $pdo, array $expectedResults)
    {
        $repository    = new BookRepository($pdo);
        $actualResults = $repository->findAll();

        static::assertEquals($expectedResults, $actualResults);
    }

    /**
     * @covers ::getAuthors
     * @dataProvider providerGetAuthors
     * @param PDO $pdo
     * @param array $expectedResults
     */
    public function testGetAuthors(PDO $pdo, array $expectedResults)
    {
        $repository    = new BookRepository($pdo);
        $actualResults = $repository->getAuthors();

        static::assertEquals($expectedResults, $actualResults);
    }

    /**
     * @covers ::getNumberOfBooksForAuthor
     * @dataProvider providerGetNumberOfBooksForAuthor
     * @param PDO $pdo
     * @param string $author
     * @param int $expectedNumberOfBooks
     */
    public function testGetNumberOfBooksForAuthor(PDO $pdo, string $author, int $expectedNumberOfBooks)
    {
        $repository             = new BookRepository($pdo);
        $numberOfBooksForAuthor = $repository->getNumberOfBooksForAuthor($author);

        static::assertEquals($expectedNumberOfBooks, $numberOfBooksForAuthor);
    }

    public function providerFindAll()
    {
        $book = new Book('Foo', 'AD12345', 'John Doe');
        $pdo = M::mock(
            PDO::class,
            [
                'query->fetchAll' => [
                    ['title' => 'Foo', 'isbn' => 'AD12345', 'author' => 'John Doe']
                ]
            ]
        );

        yield 'It should receive one book when object' => [$pdo, [$book]];

        $pdo = M::mock(
            PDO::class,
            [
                'query->fetchAll' => []
            ]
        );

        yield 'When there are no results the return value is empty array' => [$pdo, []];
    }

    public function providerGetAuthors()
    {
        $pdo = M::mock(
            PDO::class,
            [
                'query->fetchAll' => [
                    ['John Doe'],
                    ['Max Musterman'],
                ]
            ]
        );

        yield 'Green path' => [$pdo, ['John Doe', 'Max Musterman']];

        $pdo = M::mock(
            PDO::class,
            [
                'query->fetchAll' => []
            ]
        );

        yield 'Empty results' => [$pdo, []];
    }

    public function providerGetNumberOfBooksForAuthor()
    {
        yield 'Green path' => [
            $this->buildMockQueryForCount(5),
            "Max Musterman",
            5
        ];

        yield 'Zero count' => [
            $this->buildMockQueryForCount(0),
            "Max Musterman",
            0
        ];
    }


    public function buildMockQueryForCount(string $countToReturn): MockInterface
    {
        $stmt = M::mock(PDOStatement::class);
        $stmt->shouldReceive('execute');
        $stmt->shouldReceive('fetchColumn')->andReturn($countToReturn);

        $pdo = M::mock(
            PDO::class,
            ['prepare' => $stmt,]
        );

        return $pdo;
    }
}
