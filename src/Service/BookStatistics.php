<?php


namespace Jobleads\Service;


use Jobleads\Repository\{BookRepository, StatisticsRepository};

class BookStatistics
{
    /** @var BookRepository */
    private $bookRepository;

    /** @var StatisticsRepository */
    private $statisticsRepository;

    public function __construct(BookRepository $bookRepository, StatisticsRepository $statisticsRepository)
    {
        $this->bookRepository       = $bookRepository;
        $this->statisticsRepository = $statisticsRepository;
    }

    public function process()
    {
        $numberOfBooks = $this->bookRepository->getTotalNumberOfBooks();
        $this->statisticsRepository->setTotalNumberOfBooks($numberOfBooks);

        foreach ($this->bookRepository->getAuthors() as $author) {
            $numberOfBooksForAuthor = $this->bookRepository->getNumberOfBooksForAuthor($author);
            $this->statisticsRepository->setNumberOfBooksForAuthor($author, $numberOfBooksForAuthor);
        }
    }
}