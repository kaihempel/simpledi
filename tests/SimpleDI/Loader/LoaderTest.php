<?php namespace SimpleDI\Loader;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Loader test
 *
 * @package    SimpleDI
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class LoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Creates the container mock
     *
     * @return Mock
     */
    protected function getContainerMock()
    {
        $mock = m::mock('SimpleDI\Container\Container');
        $mock->shouldReceive('getSerialized');

        return $mock;
    }

    /**
     * @expectedException \SimpleDI\Exception\Loader\LoaderException
     */
    public function testNewLoaderException()
    {
        $loader = new Loader('/wrong/path/');
    }

    public function testNewLoader()
    {
        $loader = new Loader(__DIR__ . '/../../../build/');

        $this->assertInstanceOf('SimpleDI\Loader\Loader', $loader);
    }
}