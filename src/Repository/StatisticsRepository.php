<?php


namespace Jobleads\Repository;


use PDO;

class StatisticsRepository
{
    const BOOK_STATISTICS_QUERY = <<<SQL
INSERT INTO `%s` (`total_number_of_jobs`) VALUE (:number_of_books) ON DUPLICATE KEY UPDATE `total_number_of_jobs` = :number_of_books
SQL;
    const AUTHOR_STATISTICS_QUERY = <<<SQL
INSERT INTO `%s` (`author`, `number_of_books`) VALUE (:author, :number_of_books) ON DUPLICATE UPDATE KEY `number_of_books` = :number_of_books
SQL;

    /** @var PDO */
    private $connection;

    /** @var string */
    private $authorTableName = 'statistics_author';

    /** @var string */
    private $bookStatisticsTableName = 'book_statistics';

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function setTotalNumberOfBooks(int $numberOfBooks)
    {
        $stmt = $this->connection->prepare(sprintf(self::BOOK_STATISTICS_QUERY, $this->bookStatisticsTableName));
        $stmt->execute([$numberOfBooks]);
    }

    public function setNumberOfBooksForAuthor(string $author, int $numberOfBooksForAuthor)
    {
        $stmt = $this->connection->prepare(sprintf(self::AUTHOR_STATISTICS_QUERY, $this->authorTableName));

        $stmt->execute(['author' => $author, 'number_of_books' => $numberOfBooksForAuthor]);
    }
}