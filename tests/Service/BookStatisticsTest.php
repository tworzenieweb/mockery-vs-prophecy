<?php

namespace Jobleads\Service;


use Jobleads\Repository\BookRepository;
use Jobleads\Repository\StatisticsRepository;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass BookStatistics
 */
class BookStatisticsTest extends TestCase
{
    const NUMBER_OF_BOOKS = 5;
    const EXAMPLE_AUTHOR = 'Max Musterman';
    const ANOTHER_AUTHOR =  'Bob Tester';
    const NUMBER_OF_BOOKS_FOR_BOB = 7;

    /**
     * @covers ::process
     * @dataProvider providerForProcess
     * @param callable $preconditions
     */
    public function testProcess(
        callable $preconditions
    ) {
        $bookStatistics = new BookStatistics(...$preconditions($this));
        $bookStatistics->process();
    }

    public function providerForProcess()
    {
        yield 'green path' => [
            function (BookStatisticsTest $test) {
                return [
                    $test->getBookRepositoryForGreenPath(),
                    $test->getStatisticsRepositoryForGreenPath()
                ];
            }
        ];
    }

    private function getBookRepositoryForGreenPath(): BookRepository
    {
        $bookRepository = $this->prophesize(BookRepository::class);
        $bookRepository->getTotalNumberOfBooks()->willReturn(self::NUMBER_OF_BOOKS);
        $bookRepository->getAuthors()->willReturn([self::EXAMPLE_AUTHOR, self::ANOTHER_AUTHOR]);
        $bookRepository->getNumberOfBooksForAuthor(self::EXAMPLE_AUTHOR)->willReturn(self::NUMBER_OF_BOOKS);
        $bookRepository->getNumberOfBooksForAuthor(self::ANOTHER_AUTHOR)->willReturn(self::NUMBER_OF_BOOKS_FOR_BOB);

        return $bookRepository->reveal();
    }

    private function getStatisticsRepositoryForGreenPath(): StatisticsRepository
    {
        $statisticsRepository = $this->prophesize(StatisticsRepository::class);
        $statisticsRepository->setTotalNumberOfBooks(self::NUMBER_OF_BOOKS)->shouldBeCalled();
        $statisticsRepository->setNumberOfBooksForAuthor(self::EXAMPLE_AUTHOR, self::NUMBER_OF_BOOKS)->shouldBeCalled();
        $statisticsRepository->setNumberOfBooksForAuthor(self::ANOTHER_AUTHOR, self::NUMBER_OF_BOOKS_FOR_BOB)->shouldBeCalled();

        return $statisticsRepository->reveal();
    }
}
