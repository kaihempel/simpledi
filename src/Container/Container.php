<?php namespace SimpleDI\Container;

use SimpleDI\Exception\Container\ContainerException;
use SimpleDI\Loader\Loader;
use Closure;

/**
 * Container for the dependency construction code
 *
 * @package    SimpleDI
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class Container implements ContainerInterface
{
    /**
     * Closure array
     *
     * @var array
     */
    protected $closures = array();

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Adds one closure to the container
     *
     * @param   string          $name               Identifier
     * @param   Closure         $closure            Closure function for instance creation
     * @return  ContainerInterface
     */
    public function add($name, Closure $closure)
    {
        if (isset($this->closures[$name])) {
            throw ContainerException::make('"' . $name . '" already exists!');
        }

        $this->closures[$name] = $closure;

        return $this;
    }

    /**
     * Removes one closure from current container
     *
     * @param   string          $name               Identifier
     * @return  ContainerInterface
     */
    public function remove($name)
    {
        if ($this->exists($name)) {
            unset($this->closures[$name]);
        }

        return $this;
    }

    /**
     * Checks if one entry exists
     *
     * @param   string          $name               Identifier
     * @return  boolean
     */
    public function exists($name)
    {
        return isset($this->closures[$name]);
    }

    /**
     * Checks if the current container is empty
     *
     * @return  boolean
     */
    public function isEmpty()
    {
        return empty($this->closures);
    }

    /**
     * Executes the closure
     *
     * @param   string          $name               Identifier
     * @param   array           $args               Optional: Closure arguments
     * @return  null|mixed
     */
    public function __call($name, $args = array())
    {
        if ( ! $this->exists($name)) {
            throw ContainerException::make($name . ' doesn\'t exist!');
        }

        return call_user_func_array($this->closures[$name], $args);
    }
}