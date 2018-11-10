<?php

namespace InWeb\TranslationsParser\Tests;

use TranslationsParser;

class TranslationsParseCommandTest extends TestCase
{
    /** @test */
    public function can_parse_and_save_translations()
    {
        \File::cleanDirectory($this->langDir);

        \Artisan::call('translations:parse');

        $expected = [
            'Phrase1' => '',
            'Phrase2' => '',
            'Phrase3' => '',
            'Phrase4' => ''
        ];

        $locales = config('translations-parser.locales');

        foreach ($locales as $locale) {
            $parsed = TranslationsParser::getParsed($locale);
            asort($parsed);

            $this->assertEquals($expected, $parsed);
        }
    }

    /** @test */
    public function can_override_only_empty_phrases()
    {
        $exists = [
            'Phrase1' => 'Value 1',
            'Phrase2' => ''
        ];

        TranslationsParser::save('en', $exists, true);

        \Artisan::call('translations:parse');

        $phrases = TranslationsParser::getParsed('en');

        $this->assertEquals([
            'Phrase1' => 'Value 1',
            'Phrase2' => '',
            'Phrase3' => '',
            'Phrase4' => ''
        ], $phrases);

        \Artisan::call('translations:parse', ['--force' => true]);

        $phrases = TranslationsParser::getParsed('en');

        $this->assertEquals([
            'Phrase1' => '',
            'Phrase2' => '',
            'Phrase3' => '',
            'Phrase4' => ''
        ], $phrases);
    }
}
