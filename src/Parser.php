<?php

namespace InWeb\TranslationsParser;

use File;
use Symfony\Component\Finder\Finder;

class Parser
{
    /**
     * @param string $source
     * @param bool $withGroups
     * @return array
     */
    public function parseFromString($source, $withGroups = true)
    {
        $functions = [
            'lang',
            'trans',
            'trans_choice',
            'Lang::get',
            'Lang::choice',
            'Lang::trans',
            'Lang::transChoice',
            '@lang',
            '@choice',
            '__'
        ];

        $stringPattern =
            '(' . implode('|', $functions) . ')' .
            "\(" .
            "(?P<quote>['\"])" .
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)" .
            "\k{quote}" .
            "[\),]";

        $stringKeys = [];

        if (preg_match_all("/$stringPattern/siU", $source, $matches)) {
            foreach ($matches['string'] as $key) {
                if (! $withGroups and preg_match("/(^(([a-zA-Z0-9_-]+)::)*[a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU", $key, $groupMatches)) {
                    continue;
                }
                $stringKeys[] = $key;
            }
        }

        return array_unique($stringKeys);
    }

    /**
     * @param array|string $directories
     * @param array $exclude
     * @param string $pattern A pattern (a regexp, a glob, or a string)
     * @param bool $withGroups
     * @return array
     */
    public function parseFromDirectory($directories, $exclude = [], $pattern = '/\.(php|js|vue)$/', $withGroups = true)
    {
        $finder = new Finder();

        $finder->in($directories)->exclude($exclude)->name($pattern)->files();

        $stringKeys = [];

        foreach ($finder as $file) {
            if (in_array($file, $exclude))
                continue;

            $stringKeys = array_merge($stringKeys, $this->parseFromString($file->getContents(), $withGroups));
        }

        return $stringKeys;
    }

    /**
     * @param string|array $source
     * @param array $exclude
     * @param bool $withGroups
     * @return array
     */
    public function parse($source, $exclude = [], $withGroups = true)
    {
        if (is_array($source))
            $phrases = $this->parseFromDirectory($source, $exclude, null, $withGroups);
        else
            $phrases = $this->parseFromString($source);

        return $phrases;
    }

    /**
     * @param string $locale
     * @return array|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getParsed($locale)
    {
        $langDir = config('translations-parser.lang_files_directory');

        $file = $langDir . '/' . $locale . '.json';

        if (! File::exists($file))
            return [];

        $phrases = json_decode(File::get($file), true);

        return $phrases;
    }

    /**
     * @param string $locale
     * @param array $phrases
     * @param bool $force
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function save($locale, $phrases, $force = false)
    {
        $file = config('translations-parser.lang_files_directory') . '/' . $locale . '.json';

        if (! $this->isAssoc($phrases)) {
            $phrases = array_map(function () {
                return '';
            }, array_flip($phrases));
        }

        if (! $force) {
            $parsed = $this->getParsed($locale);

            foreach ($phrases as $original => $phrase) {
                if (isset($parsed[$original]) and $parsed[$original] != '')
                    $phrases[$original] = $parsed[$original];
            }
        }

        return \File::put($file, json_encode($phrases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    protected function isAssoc(array $arr)
    {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * @param string $locale
     * @param string $original
     * @param string $translation
     * @return int
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function translate($locale, $original, $translation)
    {
        $file = config('translations-parser.lang_files_directory') . '/' . $locale . '.json';

        $phrases = $this->getParsed($locale);

        $phrases[$original] = $translation;

        return \File::put($file, json_encode($phrases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
