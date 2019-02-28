<?php


namespace Jobleads\Service;


use Jobleads\Repository\TranslateRepository;

class Translate
{
    /** @var TranslateRepository */
    private $translateRepository;

    /** @var string */
    private $defaultLocale;

    /** @var array[] */
    private $translations;

    public function __construct(TranslateRepository $translateRepository, $defaultLocale)
    {
        $this->translateRepository = $translateRepository;
        $this->defaultLocale       = $defaultLocale;

        $this->initialize();
    }


    public function translate($string, $locale = null): string
    {
        $locale = $locale ?: $this->defaultLocale;

        return $this->translations[$locale][$string] ?? $string;
    }

    private function initialize()
    {
        $this->translations = $this->translateRepository->fetchAllAsKeyValue();
    }
}