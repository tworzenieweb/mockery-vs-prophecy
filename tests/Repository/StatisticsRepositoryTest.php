<?php

namespace Jobleads\Repository;


use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class StatisticsRepositoryTest extends TestCase
{
    const QUERY_FOR_AUTHOR_STATISTICS = '(`author`, `number_of_books`) VALUE (:author, :number_of_books) ON DUPLICATE UPDATE KEY `number_of_books` = :number_of_books';
    const QUERY_FOR_BOOK_STATISTICS = '(`total_number_of_jobs`) VALUE (:number_of_books) ON DUPLICATE KEY UPDATE `total_number_of_jobs` = :number_of_books';

    /**
     * @dataProvider providerForSetNumberOfBooksForAuthor
     * @param callable $preconditions
     */
    public function testSetNumberOfBooksForAuthor(callable $preconditions)
    {
        $params     = $preconditions($this);
        $repository = new StatisticsRepository($params[PDO::class]);
        $repository->setNumberOfBooksForAuthor($params['author'], $params['numberOfBooks']);
    }

    /**
     * @dataProvider providerForSetTotalNumberOfBooks
     * @param callable $preconditions
     */
    public function testSetTotalNumberOfBooks(callable $preconditions)
    {
        $params = $preconditions($this);

        $repository = new StatisticsRepository($params[PDO::class]);
        $repository->setTotalNumberOfBooks($params['totalNumberOfBooks']);
    }

    public function providerForSetTotalNumberOfBooks()
    {
        yield [
            function (StatisticsRepositoryTest $test) {
                $numberOfBooks = 5;
                $statement     = $test->assertArgumentsForTotalNumberOfBooksStatementShouldBeProvided($numberOfBooks);
                $connection    = $test->assertRightQueryForSetTotalNumberOfBooksShouldBeCalled($statement);

                return [
                    PDO::class           => $connection,
                    'totalNumberOfBooks' => $numberOfBooks
                ];
            }
        ];
    }

    public function providerForSetNumberOfBooksForAuthor()
    {
        yield [
            function (StatisticsRepositoryTest $test) {
                $numberOfBooks = 5;
                $author        = 'Max Musterman';
                $expectedQuery = Argument::containingString(StatisticsRepositoryTest::QUERY_FOR_AUTHOR_STATISTICS);
                $connection    = $test->prophesize(PDO::class);
                $statement     = $test->prophesize(PDOStatement::class);

                $statement->execute(['author' => $author, 'number_of_books' => $numberOfBooks])->shouldBeCalled();
                $connection->prepare($expectedQuery)->willReturn($statement->reveal());

                return [
                    PDO::class      => $connection->reveal(),
                    'author'        => $author,
                    'numberOfBooks' => $numberOfBooks
                ];
            }
        ];
    }

    private function assertArgumentsForTotalNumberOfBooksStatementShouldBeProvided(int $numberOfBooks): PDOStatement
    {
        $statement = $this->prophesize(PDOStatement::class);
        $statement->execute([$numberOfBooks])->shouldBeCalled();

        return $statement->reveal();
    }

    private function assertRightQueryForSetTotalNumberOfBooksShouldBeCalled(PDOStatement $statement): PDO
    {
        $connection    = $this->prophesize(PDO::class);
        $expectedQuery = Argument::containingString(StatisticsRepositoryTest::QUERY_FOR_BOOK_STATISTICS);
        $connection->prepare($expectedQuery)->willReturn($statement);

        return $connection->reveal();
    }
}
