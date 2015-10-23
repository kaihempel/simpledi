<?php namespace SimpleDI\Container;

use Closure;

/**
 * Container interface definition
 *
 * @package    SimpleDI
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
interface ContainerInterface
{
    /**
     * Adds one closure to the container
     *
     * @param   string          $name               Identifier
     * @param   Closure         $closure            Closure function for instance creation
     * @return  ContainerInterface
     */
    public function add($name, Closure $closure);

    /**
     * Removes one closure from current container
     *
     * @param   string          $name               Identifier
     * @return  ContainerInterface
     */
    public function remove($name);

    /**
     * Checks if one entry exists
     *
     * @param   string          $name               Identifier
     * @return  boolean
     */
    public function exists($name);

    /**
     * Checks if the current container is empty
     *
     * @return  boolean
     */
    public function isEmpty();

    /**
     * Returns the closures as serialzie string
     *
     * @return  string
     */
    public function getSerialized();

    /**
     * Initialized the container content from serialized string
     *
     * @param   string          $serialized         Serialized string with the closures
     * @return  ContainerInterface
     */
    public function initFromSerialized($serialized);

    /**
     * Executes the closure
     *
     * @param   string          $name               Identifier
     * @param   array           $args               Optional: Closure arguments
     * @return  null|mixed
     */
    public function __call($name, $args = array());

}