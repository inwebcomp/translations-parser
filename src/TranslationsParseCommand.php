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
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(): int
    {
        $config = config('translations-parser');
        $parser = new Parser();

        $phrases = $parser->parseFromDirectory($config['directories'], $config['excluded']);

        foreach ($config['locales'] as $locale) {
            $parser->save($locale, $phrases, $this->option('force'));
        }

        $this->getOutput()->success("Translations have been parsed");

        return self::SUCCESS;
    }
}
