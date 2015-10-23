<h1 align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</h1>

> PlumDate integrates Doctrine into Plum. Plum is a data processing pipeline for PHP.

[![Build Status](https://travis-ci.org/plumphp/plum-doctrine.svg)](https://travis-ci.org/plumphp/plum-doctrine)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plumphp/plum-doctrine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-doctrine/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/plumphp/plum-doctrine/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-doctrine/?branch=master)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Installation
------------

You can install PlumDoctrine using [Composer](http://getcomposer.org).

```shell
$ composer require plumphp/plum-doctrine
```


Usage
-----

Please refer to the [Plum documentation](https://github.com/plumphp/plum/blob/master/docs/index.md) for more
information.

**Doctrine ORM**

- [`EntityWriter`](#entitywriter-for-doctrine-rom)

### `EntityWriter` for Doctrine ORM

`Plum\PlumDoctrine\ORM\EntityWriter` persists entities using an instance of `Doctrine\ORM\EntityManagerInterface`. It
supports batch operations with a configurable flush interval.

```php
use Plum\PlumDoctrine\ORM\EntityWriter;

$writer = new EntityWriter($entityManager);
$writer->prepare();
$writer->writeItem($user1); // persist, but no flush
$writer->writeItem($user2); // persist, but no flush
$writer->finish(); // flush
```

If you are persisting too many entities for one flush at the end you can set the `flushInterval` option to flush after
writing every `x` entities.

```php
use Plum\PlumDoctrine\ORM\EntityWriter;

$writer = new EntityWriter($entityManager, ['flushInterval' => 3);
$writer->prepare();
$writer->writeItem($user1); // persist, but no flush
$writer->writeItem($user2); // persist, but no flush
$writer->writeItem($user3); // persist and flush
$writer->writeItem($user4); // persist, but no flush
$writer->finish(); // flush
```

Setting the `flushInverval` option to `null`, which is also the default value, flushes the transaction only when
calling `finish()`. If no items are written using `writeItem()` the writer will never call `flush()`.


Change Log
----------

*No version released yet.*


License
-------

The MIT license applies to plumphp/plum-doctrine. For the full copyright and license information,
please view the LICENSE file distributed with this source code.
