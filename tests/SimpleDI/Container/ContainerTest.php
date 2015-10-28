<?php namespace SimpleDI\Container;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url path item test
 *
 * @package    SimpleDI
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class ContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Creates the container mock
     *
     * @return Mock
     */
    protected function getLoaderMock()
    {
        $mock = m::mock('SimpleDI\Loader\Loader');

        return $mock;
    }

    public function testNewContainer()
    {
        $container = new Container($this->getLoaderMock());

        $this->assertInstanceOf('SimpleDi\Container\Container', $container);
    }

    public function testIsEmpty()
    {
        $container = new Container($this->getLoaderMock());

        $this->assertInstanceOf('SimpleDi\Container\Container', $container);
        $this->assertTrue($container->isEmpty());

        $container->add('test', function() {echo 'test';});

        $this->assertFalse($container->isEmpty());

        $container->remove('test');

        $this->assertTrue($container->isEmpty());
    }

    public function testAdd()
    {
        $container = new Container($this->getLoaderMock());
        $container->add('test', function() {echo 'test';});

        $this->assertTrue($container->exists('test'));
    }

    /**
     * @expectedException \SimpleDI\Exception\Container\ContainerException
     * @expectedExceptionMessage "test" already exists!
     */
    public function testAddExistsException()
    {
        $container = new Container($this->getLoaderMock());
        $container->add('test', function() {echo 'test';});

        $this->assertTrue($container->exists('test'));

        $container->add('test', function() {echo 'already exists';});
    }

    public function testExists()
    {
        $container = new Container($this->getLoaderMock());
        $container->add('test', function() {echo 'test';});

        $this->assertTrue($container->exists('test'));
    }

    public function testRemove()
    {
        $container = new Container($this->getLoaderMock());
        $container->add('test', function() {echo 'test';});

        $this->assertTrue($container->exists('test'));

        $container->remove('test');

        $this->assertFalse($container->exists('test'));
    }

    public function testCall()
    {
        $container = new Container($this->getLoaderMock());
        $container->add('test', function() {echo 'test';});

        $this->assertTrue($container->exists('test'));
        $this->expectOutputString('test');

        $container->test();
    }

    /**
     * @expectedException \SimpleDI\Exception\Container\ContainerException
     */
    public function testCallException()
    {
        $container = new Container($this->getLoaderMock());
        $container->add('test', function() {echo 'test';});

        $this->assertTrue($container->exists('test'));

        $container->undefined();
    }

}