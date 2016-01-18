<?php

require_once __DIR__ . '/Author.php';
require_once __DIR__ . '/Book.php';

// Use the composer autoloader

require __DIR__ . '/../vendor/autoload.php';

/**
 *  Create DI container
 */

$di = new \SimpleDI\SimpleDI();

// Add the dependency closures

$di->add('author', function($name) {
    return new Author($name);
});

$di->add('book', function($name) use ($di) {
    return new Book($di->getAuthor($name));
});

/**
 * Main application demo class
 */
class Main
{
    private $di;

    public function __construct(\SimpleDI\SimpleDI $di)
    {
        $this->di = $di;
    }

    public function run()
    {
        $book = $this->di->getBook('test');

        echo "Book created\n";
        echo "\n\n";

        echo var_dump($book);

        echo "\n\n";
        echo "\nClasses:\n";

        echo $book->getClass();
        echo "\n";
        echo $book->getAuthor()->getClass();

        echo "\n\n";
        echo "Author name: {$book->getAuthorName()}\n";
        echo "Finish\n\n";
    }
}

/**
 * Program
 *
 * The programm class dependency is the SimpleDI container.
 * The container holds the object creation code. Inside "run" the dynamic
 * method "getBook" is called. This method is defined by adding "book" to the
 * SimpleDI object.
 * The method call executes the added closure code. Also the closure references
 * the SimpleDI object by using the "use" statement. This is necessary to create
 * the Book dependency Author.
 */

$main = new Main($di);
$main->run();
