# Slytherin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Yet Another Extensible PHP Library/Framework.

## Install

Via Composer

``` bash
$ composer require rougin/slytherin --no-dev
```

`--no-dev` must be included to exclude the libraries that are used only for testing.

## Usage

``` php
$components = new Rougin\Slytherin\Components;

$components
    ->setContainer(new Acme\IoC\Container)
    ->setDispatcher(new Acme\Dispatching\Dispatcher)
    ->setDebugger(new Acme\Debug\Debugger)
    ->setHttp(new Acme\Http\Request, new Acme\Http\Response);

$application = new Rougin\Slytherin\Application($components);

$application->run();
```

Regarding the ```$components``` above, you need to select a library of your choice and implement it with a provided interface in order for it to be integrated in Slytherin. More information about this can be found in the [Using Interfaces](https://github.com/rougin/slytherin/wiki/Using-Interfaces) section in the wiki.

### Libraries

Slytherin also provide sample implementations on each component (dependency injector, HTTP, etc.) that are built on top of other existing libraries. You will need to install their respective dependencies first before you can use it directly.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/slytherin.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/slytherin/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/slytherin.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/slytherin.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/slytherin.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rougin/slytherin
[link-travis]: https://travis-ci.org/rougin/slytherin
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/slytherin/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/slytherin
[link-downloads]: https://packagist.org/packages/rougin/slytherin
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors
