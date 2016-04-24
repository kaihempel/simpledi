# simpledi

Simple PHP dependency injection bundle

[![Build Status](https://travis-ci.org/kaihempel/simpledi.svg?branch=master)](https://travis-ci.org/kaihempel/simpledi)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kaihempel/simpledi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kaihempel/simpledi/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/kaihempel/simpledi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kaihempel/simpledi/?branch=master)

This dependency injection container based on the idea to add the object construction code as closure. So every dependency can build with less convention restrictions.

Installing simpledi via Composer.

```json
  "require": {
    "kaihempel/simpledi": "1.0.*"
  }
```

Create the dependency injection container instance:

```php
  $di = new \SimpleDI\SimpleDI();
```

Adding closures for instance creation:

```php
  $di->add('author', function($name) {
    return new Author($name);
  });
```

After adding the closure with the name "autor", the closure can be executed by calling the magic get method:

```php
  $di->getAuthor($name)
```

To create instance with further dependencys, the dependency injection container can be commited to the closure by using the "use" keyword:

```php
  $di->add('book', function($name) use ($di) {
    return new Book($di->getAuthor($name));
  });
```

Like the description above, the "Book" instance will be initialized with a new author instance.

The container has a storage to save created objects. This feature have to be enabled by calling the "getStored()" method before calling the closure code:

```php
  $di->getStored()
     ->getBook($name);
```

If no Book instance is stored, the closure execution saves the new instance in the storage.
