<?php

namespace InWeb\TranslationsParser;

use Illuminate\Console\Command;

class TranslationsParseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:parse {-f|--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates JSON files with translations';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = config('translations-parser');

        $phrases = \TranslationsParser::parseFromDirectory($config['directories'], $config['excluded']);

        foreach ($config['locales'] as $locale) {
            \TranslationsParser::save($locale, $phrases, $this->option('force'));
        }
    }
}
