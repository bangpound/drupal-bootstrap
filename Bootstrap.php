<?php

namespace Drupal\Core;

use Pimple\Container;

/**
 * Class Bootstrap
 * @package Drupal\Core
 */
class Bootstrap extends AbstractBootstrap
{
    /**
     * @var Container
     */
    protected $c;

    public function __construct($values = array())
    {
        if (empty($values)) {
            $values = BootstrapPhases::all();
        }
        $this->c = new Container($values);
    }

    /**
     * Calls the bootstrap phase.
     *
     * This method can be overridden to support events in subclasses.
     *
     * @param  null       $phase Phase
     * @return mixed|void
     */
    protected function call($phase = null)
    {
        $this->c[$phase];
    }
}
