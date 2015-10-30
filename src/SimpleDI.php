<?php namespace SimpleDI;

use SimpleDI\Container\Container;
use SimpleDI\Exception\SimpleDIException;
use Closure;

/**
 * This is the main application
 *
 * @package    SimpleDI
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
final class SimpleDI
{
    /**
     * Closure array
     *
     * @var array
     */
    protected $closures = array();

    /**
     * Getter list
     *
     * @var array
     */
    private $getterList = array();

    /**
     * Object storage
     *
     * @var array
     */
    private $objectList = array();

    /**
     * Processflag to store the dependency objects
     *
     * @var boolean
     */
    private $getStored = false;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Sets the closure and the getter mapping
     *
     * @param type $name
     * @param Closure $closure
     * @return SimpleDI
     */
    public function add($name, Closure $closure)
    {
        if (isset($this->closures[$name])) {
            throw SimpleDIException::make('"' . $name . '" already exists!');
        }

        $this->addGetter($name);
        $this->closures[$name] = $closure;

        return $this;
    }

    /**
     * Removes the closure and getter mapping.
     *
     * @param string $name
     * @return SimpleDI
     */
    public function remove($name)
    {
        $this->removeGetter($name);

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
     * Sets the getStored flag to true.
     * The next closure call will first check the local object list.
     * If no object is stored the result will be saved in the object list.
     * @return SimpleDI
     */
    public function getStored()
    {
        $this->getStored = true;

        return $this;
    }

    /**
     * Calls the container __call method.
     * This methods is the central closure call.
     *
     * @param string $name
     * @param array $args
     * @return null|mixed
     * @throws SimpleDIException
     */
    public function __call($name, $args = array())
    {
        if (substr($name, 0, 3) != 'get') {
            throw SimpleDIException::make('Undefined method called!');
        }

        $name = $this->dispatchGetter($name);

        return $this->callClosure($name, $args);
    }

    /**
     * Executes the stored closure from the container
     *
     * @param string $name
     * @param array $args
     * @return null|mixed
     */
    private function callClosure($name, $args)
    {
        // Check if the current object is stored

        if ($this->getStored === true && $this->isStored($name)) {
            $this->resetStored();
            return $this->objectList[$name];
        }

        // Call the closure and store the object

        $result = call_user_func_array($this->closures[$name], $args);

        if (is_object($result) && $this->getStored === true) {
            $this->resetStored();
            $this->objectList[$name] = $result;
        }

        return $result;
    }

    /**
     * Checks if the current requested object is stored in the object list.
     *
     * @param string $name
     * @return mixed
     */
    private function isStored($name)
    {
        return isset($this->objectList[$name]);
    }

    /**
     * Resets the stored flag.
     * This should only enabled for the next operation!
     *
     * @return void
     */
    private function resetStored()
    {
        $this->getStored = false;
    }

    /**
     * Returns the getter name
     *
     * @param string $name
     * @return string
     */
    private function buildGetterName($name)
    {
        return 'get' . ucfirst($name);
    }

    /**
     * Adds the getter function mapping to the getter list.
     *
     * @param string $name
     * @return void
     */
    private function addGetter($name)
    {
        $this->getterList[$this->buildGetterName($name)] = $name;
    }

    /**
     * Removes the getter from the mapping list.
     *
     * @param string $name
     * @return void
     */
    private function removeGetter($name)
    {
        $getter = $this->buildGetterName($name);

        if (isset($this->getterList[$getter])) {
            unset($this->getterList[$getter]);
        }
    }

    /**
     * Returns the mapped container method name.
     *
     * @param string $getter
     * @return string
     * @throws SimpleDIException
     */
    private function dispatchGetter($getter)
    {
        if ( ! isset($this->getterList[$getter])) {
            throw SimpleDIException::make('Undefined dependency "' . $getter . '"!');
        }

        return $this->getterList[$getter];
    }
}