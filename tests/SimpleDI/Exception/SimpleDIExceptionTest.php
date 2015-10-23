<?php namespace SimpleDI\Exception;

use PHPUnit_Framework_TestCase;

/**
 * Basic exception test
 *
 * @package    SimpleDI
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class DbrouterExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testNewException() {

        $ex = new SimpleDIException('Special exception');

        $this->assertInstanceOf('SimpleDI\Exception\SimpleDIException', $ex);
        $this->assertEquals('Special exception', $ex->getMessage());
    }

    public function testMake() {

        $ex = DbrouterException::make();

        $this->assertInstanceOf('SimpleDI\Exception\SimpleDIException', $ex);
        $this->assertEquals('Unknown exception', $ex->getMessage());
    }
}