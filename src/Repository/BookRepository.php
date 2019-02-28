<?php


namespace Jobleads\Repository;


use Jobleads\Model\Book;
use PDO;

class BookRepository
{
    /** @var PDO */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @return Book[]
     */
    public function findAll(): array
    {
        $books = [];
        $stmt  = $this->connection->query('SELECT * FROM Book');

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $book) {
            $books[] = new Book($book['title'], $book['isbn'], $book['author']);
        }

        return $books;
    }

    public function getTotalNumberOfBooks(): int
    {
        $stmt = $this->connection->query('SELECT COUNT(*) FROM Book');

        return (int) $stmt->fetchColumn(0);
    }

    /**
     * @return string[]
     */
    public function getAuthors(): array
    {
        $authors = [];
        $stmt    = $this->connection->query('SELECT DISTINCT author FROM Book ORDER BY author ASC');

        foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $row) {
            $authors[] = $row[0];
        }

        return $authors;
    }

    public function getNumberOfBooksForAuthor(string $author): int
    {
        $stmt = $this->connection->prepare(<<<SQL
SELECT COUNT(*) 
FROM Book 
WHERE author = ?
SQL
        );
        $stmt->execute([$author]);

        return (int) $stmt->fetchColumn(0);
    }
}