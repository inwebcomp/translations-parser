<?php

namespace InWeb\TranslationsParser;

use Illuminate\Support\Facades\Facade;

class TranslationsParserFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'translations-parser';
    }
}
