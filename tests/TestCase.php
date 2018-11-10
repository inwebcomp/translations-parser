<?php

namespace InWeb\TranslationsParser\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use \Tests\CreatesApplication;

    public $langDir = __DIR__ . '/lang';
    public $viewsDir = __DIR__ . '/views';

    protected function setUp()
    {
        parent::setUp();

        $this->mockConfig();
    }

    public function mockConfig()
    {
        \Config::set('translations-parser', [
            'locales'     => ['ru', 'en'],
            'directories' => [$this->viewsDir],
            'excluded'     => [$this->viewsDir . '/file.vue'],
            'lang_files_directory' => $this->langDir
        ]);
    }
}
