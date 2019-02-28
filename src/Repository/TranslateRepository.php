<?php


namespace Jobleads\Repository;


use PDO;

class TranslateRepository
{
    const TABLE_NAME = 'translations';

    /** @var PDO */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }


    public function fetchAllAsKeyValue()
    {
        $stmt = $this->connection->prepare(
            sprintf('SELECT `translation_key`, `translation_value`, `locale` FROM %s', self::TABLE_NAME)
        );

        $stmt->execute();

        $translations = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $locale                                 = $row['locale'];
            $translationKey                         = $row['translation_key'];
            $translationValue                       = $row['translation_value'];
            $translations[$locale][$translationKey] = $translationValue;
        }

        return $translations;
    }
}