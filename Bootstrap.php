<?php

namespace Drupal\Core;

/**
 * Class Bootstrap
 * @package Drupal\Core
 */
class Bootstrap extends AbstractBootstrap
{
    /**
     * @var \Pimple
     */
    private $c;

    public function __construct($values = array())
    {
        if (empty($values)) {
            $values = BootstrapPhases::all();
        }
        array_walk($values, function ($callback) {
            return \Pimple::share($callback);
        }, $values);
        $this->c = new \Pimple($values);
    }

    /**
     * Calls the bootstrap phase.
     *
     * This method can be overridden to support events in subclasses.
     *
     * @param  null       $phase Phase
     * @return mixed|void
     */
    protected function call($phase = NULL)
    {
        $this->c[$phase];
    }

    /**
     * @return array An array of phase names
     */
    protected function getPhases()
    {
        return $this->c->keys();
    }
}
