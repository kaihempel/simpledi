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
        //$mock->shouldReceive('getSerialized');

        return $mock;
    }
}