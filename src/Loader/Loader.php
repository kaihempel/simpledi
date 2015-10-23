<?php namespace SimpleDI\Loader;

class Loader implements LoaderInterface
{
    /**
     *
     * @var type
     */
    protected $path = '';

    /**
     *
     * @param type $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     *
     * @param \SimpleDI\Loader\Container $container
     * @return type
     */
    public function load(Container $container)
    {
        if ( ! $container->isEmpty()) {
            return;
        }

        $tmpfname = tempnam($this->path, "SimpleDI");
        file_put_contents($tmpfname, $container->getSerialized());

        require $tmpfname;
    }
}
