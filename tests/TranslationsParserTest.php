<?php

namespace InWeb\TranslationsParser\Tests;

use TranslationsParser;

class TranslationsParserTest extends TestCase
{
    /** @test */
    public function can_parse_from_string()
    {
        $string = "<div>@lang('Phrase1') __('Phrase2') @trans('Phrase3') this.lang('Phrase4') Lang::get(\"Phrase5\")<div>";

        $phrases = TranslationsParser::parse($string);

        $this->assertEquals([
            'Phrase1',
            'Phrase2',
            'Phrase3',
            'Phrase4',
            'Phrase5'
        ], $phrases);
    }

    /** @test */
    public function can_parse_from_dir()
    {
        $phrases = TranslationsParser::parse([$this->viewsDir]);

        sort($phrases);

        $this->assertEquals([
            'Phrase1',
            'Phrase2',
            'Phrase3',
            'Phrase4',
            'Phrase5',
            'Phrase6'
        ], array_values($phrases));
    }

    /** @test */
    public function cant_parse_excluded()
    {
        $phrases = TranslationsParser::parse([$this->viewsDir], [$this->viewsDir . '/file.blade.php']);

        sort($phrases);

        $this->assertEquals([
            'Phrase3',
            'Phrase4',
            'Phrase5',
            'Phrase6'
        ], array_values($phrases));
    }

    /** @test */
    public function can_get_parsed_phrases()
    {
        $file = $this->langDir . '/en.json';

        $expected = [
            'Phrase1' => '',
            'Phrase2' => ''
        ];

        \File::put($file, json_encode($expected));

        $phrases = TranslationsParser::getParsed('en');

        $this->assertEquals($expected, $phrases);
    }

    /** @test */
    public function can_save_empty_phrases()
    {
        $expected = [
            'Phrase1' => '',
            'Phrase2' => ''
        ];

        TranslationsParser::save('en', array_keys($expected));
        $phrases = TranslationsParser::getParsed('en');

        $this->assertEquals($expected, $phrases);
    }

    /** @test */
    public function can_save_phrases_with_values()
    {
        $expected = [
            'Phrase1' => 'Value1',
            'Phrase2' => 'Value2'
        ];

        TranslationsParser::save('en', $expected);
        $phrases = TranslationsParser::getParsed('en');

        $this->assertEquals($expected, $phrases);
    }

    /** @test */
    public function can_override_only_empty_phrases()
    {
        $exists = [
            'Phrase1' => 'Value 1',
            'Phrase2' => ''
        ];

        TranslationsParser::save('en', $exists, true);

        $new = [
            'Phrase1' => 'New Value',
            'Phrase2' => 'New Value 2',
            'Phrase3' => 'New Value 3'
        ];

        TranslationsParser::save('en', $new);

        $phrases = TranslationsParser::getParsed('en');

        $this->assertEquals([
            'Phrase1' => 'Value 1',
            'Phrase2' => 'New Value 2',
            'Phrase3' => 'New Value 3'
        ], $phrases);
    }

    public function can_save_only_one_translation()
    {
        TranslationsParser::save('en', [
            'Phrase1' => 'Value 1',
            'Phrase2' => 'Value 2'
        ], true);

        TranslationsParser::saveOne('en', 'Phrase2', 'New Value');

        $phrases = TranslationsParser::getParsed('en');

        $this->assertEquals([
            'Phrase1' => 'Value 1',
            'Phrase2' => 'New Value'
        ], $phrases);
    }
}
