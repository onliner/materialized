Onliner Materialized
---------------

Materialize DB integration layer.

[![Version][version-badge]][version-link]
[![Total Downloads][downloads-badge]][downloads-link]
[![Php][php-badge]][php-link]
[![License][license-badge]](LICENSE)
[![Build Status][build-badge]][build-link]

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require onliner/materialized:^0.0.1
```

or add this code line to the `require` section of your `composer.json` file:

```
"onliner/materialized": "^0.0.1"
```

Usage
-----

```php
use Onliner\Materialized\Connection;
use Onliner\Materialized\Statement\CreateConnection;
use Onliner\Materialized\Statement\CreateSource;

$connection = Connection::open('pgsl://localhost:6575');

$connection->execute(CreateConnection::kafka('kafka', 'redpanda:9092'));
$connection->execute(CreateSource::kafka('my_source', 'kafka', 'my_topic'));

// Just work with any other PDOStatement
$statement = $connection->fetch('SELECT * FROM my_source');
```

License
-------

Released under the [MIT license](LICENSE).


[version-badge]:    https://img.shields.io/packagist/v/onliner/materialized.svg
[version-link]:     https://packagist.org/packages/onliner/materialized
[downloads-link]:   https://packagist.org/packages/onliner/materialized
[downloads-badge]:  https://poser.pugx.org/onliner/materialized/downloads.png
[php-badge]:        https://img.shields.io/badge/php-7.2+-brightgreen.svg
[php-link]:         https://www.php.net/
[license-badge]:    https://img.shields.io/badge/license-MIT-brightgreen.svg
[build-link]:       https://github.com/onliner/materialized/actions?workflow=test
[build-badge]:      https://github.com/onliner/materialized/workflows/test/badge.svg
