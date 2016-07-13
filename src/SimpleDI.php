<?php namespace SimpleDI;

use SimpleDI\Exception\SimpleDIException;
use SimpleHash\Hash;
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
class SimpleDI
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
    protected $getterList = array();

    /**
     * Object storage
     *
     * @var array
     */
    protected $objectList = array();

    /**
     * Processflag to store the dependency objects
     *
     * @var boolean
     */
    protected $getStored = false;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Sets the closure and the getter mapping
     *
     * @param   string          $name           Class name
     * @param   Closure         $closure        Closure function: Code to create
     *                                          the object instance.
     * @return  SimpleDI
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
     * @param   string          $name           Class name
     * @return  SimpleDI
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
     * @param   string          $name           Class name
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
     *
     * @return  SimpleDI
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
     * @param   string          $name           Getter function name
     * @param   array           $args           Construction arguments
     * @return  false|mixed
     * @throws  SimpleDIException
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
     * Executes the stored closure from the container. The method returns the
     * closure result or false in the closure execution fails.
     * If the "getStored" flag is set, the method checks the object list for a
     * exsisting instance. Otherwise the created instance will be stored for
     * further use.
     *
     * @param   string          $name           Class name
     * @param   array           $args           Construction arguments
     * @return  boolean|mixed
     */
    private function callClosure($name, $args)
    {
        // Check if the current object is stored

        $hash = $this->generateStorageHash($name, $args);

        if ($this->getStored === true && $this->isStored($hash)) {
            $this->resetStored();
            return $this->objectList[$hash];
        }

        // Call the closure and store the object

        $result = call_user_func_array($this->closures[$name], $args);

        // Store the instance in current object list

        if (is_object($result) && $this->getStored === true) {
            $this->resetStored();
            $this->objectList[$hash] = $result;
        }

        // Return the closure result

        return $result;
    }

    /**
     * Generates a hash to store the specific object
     *
     * @param   string          $name           Class name
     * @param   array           $args           Arguments
     * @return  string
     */
    private function generateStorageHash($name, $args) {

        $hash = Hash::Sha1($name . '_' . serialize($args));
        return $hash->getHashString();
    }

    /**
     * Checks if the current requested object is stored in the object list.
     *
     * @param   string          $hash           Class name
     * @return  mixed
     */
    private function isStored($hash)
    {
        if (empty($hash)) {
            return false;
        }

        return isset($this->objectList[$hash]);
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
     * @param   string          $name           Class name
     * @return  string
     */
    private function buildGetterName($name)
    {
        return 'get' . ucfirst($name);
    }

    /**
     * Adds the getter function mapping to the getter list.
     *
     * @param   string          $name           Class name
     * @return  void
     */
    private function addGetter($name)
    {
        $this->getterList[$this->buildGetterName($name)] = $name;
    }

    /**
     * Removes the getter from the mapping list.
     *
     * @param   string          $name           Class name
     * @return  void
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
     * @param   string          $getter         Getter function name
     * @return  string
     * @throws  SimpleDIException
     */
    private function dispatchGetter($getter)
    {
        if (! isset($this->getterList[$getter])) {
            throw SimpleDIException::make('Undefined dependency "' . $getter . '"!');
        }

        return $this->getterList[$getter];
    }
}
