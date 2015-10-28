<?php namespace SimpleDI;

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
    public function testNewDI()
    {
        $di = new SimpleDI();

        $this->assertInstanceOf('\SimpleDI\SimpleDI', $di);
    }


    public function testAdd()
    {
        $di = new SimpleDI();

        $this->assertInstanceOf('\SimpleDI\SimpleDI', $di);
        $this->expectOutputString('test');

        $di->add('test', function(){echo 'test';});
        $di->getTest();

    }

    /**
     * @expectedException \SimpleDI\Exception\SimpleDIException
     * @expectedExceptionMessage Undefined dependency "getTest"!
     */
    public function testRemove()
    {
        $di = new SimpleDI();

        $this->assertInstanceOf('\SimpleDI\SimpleDI', $di);
        $this->expectOutputString('test');

        $di->add('test', function(){echo 'test';});
        $di->getTest();

        $di->remove('test');
        $di->getTest();

    }

    /**
     * @expectedException \SimpleDI\Exception\SimpleDIException
     * @expectedExceptionMessage Undefined method called!
     */
    public function testUndefinedMethod()
    {
        $di = new SimpleDI();
        $di->testUndefined();
    }

    public function testStored()
    {
        $di = new SimpleDI();

        $this->assertInstanceOf('\SimpleDI\SimpleDI', $di);

        $di->add('test', function(){return new \stdClass();});
        $di->getStored();
        $this->assertInstanceOf('\stdClass', $di->getTest());

        $di->getStored();
        $this->assertInstanceOf('\stdClass', $di->getTest());
    }
}