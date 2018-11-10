Parse your files and pull translatable phrases. 

## Installation

You can install the package via composer:

```bash
composer require inwebcomp/translations-parser
```

## Usage

### Artisan commands

Parse translations with artisan command
``` bash
php artisan translations:parse
```

Force parse translations. **Old translations will be removed**
``` bash
php artisan translations:parse --force
```



### Methods

Get phrases from string
``` php
TranslationsParser::parse('<div>@lang("Phrase")</div>');
// ['Phrase']
```

Get phrases from files in directories
``` php
TranslationsParser::parse([
    resouce_path('views'),
    resouce_path('js')
]);
// ['Phrase 1', 'Phrase 2', ...]
```

Can exclude directories or files from list
``` php
TranslationsParser::parse([
    resouce_path('views')
], [
    resouce_path('views/layouts') // Exclude 'views/layouts' directory
]);
```

Get phrases in _{locale}.json_ file
``` php
TranslationsParser::getParsed('en');
```

Save phrases to .json file
``` php
TranslationsParser::save('en', [
    'Phrase'
]);

// Or with values
TranslationsParser::save('en', [
    'Phrase' => 'Value'
]);
```

By default, phrases would **not be overwritten**
It will be overwritten only if there is no phrase in your .json file, or phrase translation is empty

Set last parameter to true to force writing in .json. **You will lose old data**
``` php
TranslationsParser::save('en', [
    'Phrase' => 'Value'
], true);
```

Save one phrase translation
``` php
TranslationsParser::translate('en', 'Phrase', 'Translation');
```

### Testing

``` bash
composer test
```

## Credits

- [Alexander Topalo](https://github.com/Escral)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
