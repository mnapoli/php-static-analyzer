# My Awesome Project

PHP static analyzer

[![Build Status](https://img.shields.io/travis/mnapoli/php-static-analyzer.svg?style=flat-square)](https://travis-ci.org/mnapoli/php-static-analyzer)
[![Coverage Status](https://img.shields.io/coveralls/mnapoli/php-static-analyzer/master.svg?style=flat-square)](https://coveralls.io/r/mnapoli/php-static-analyzer?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/mnapoli/php-static-analyzer.svg?style=flat-square)](https://scrutinizer-ci.com/g/mnapoli/php-static-analyzer/?branch=master)
[![Latest Version](https://img.shields.io/github/release/mnapoli/php-static-analyzer.svg?style=flat-square)](https://packagist.org/packages/mnapoli/php-static-analyzer)
[![Total Downloads](https://img.shields.io/packagist/dt/mnapoli/php-static-analyzer.svg?style=flat-square)](https://packagist.org/packages/mnapoli/php-static-analyzer)

## Why?

PHP static analysis.

## Installation

```
composer require mnapoli/php-static-analyzer
```

## Usage

```php
$analyzer = new PhpAnalyzer\Analyzer();

$analysis = $analyzer->analyze($dir);

$query = new MethodCallQuery('Foo\Bar', 'doSomething');
$nodes = $analysis->find($query);

foreach ($nodes as $node) {
    echo $node->getFile();
    echo $node->getLine();
    echo $node->getStartOffset();
    echo $node->getEndOffset();
}
```

## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.

## License

This project is released under the MIT license.
