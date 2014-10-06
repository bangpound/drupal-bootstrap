<?php

namespace Drupal\Core;

/**
 * Interface BootstrapInterface
 * @package Drupal\Core
 */
interface BootstrapInterface
{
    /**
     * @param  null  $phase
     * @param  bool  $new_phase
     * @return mixed
     */
    public function __invoke($phase = null, $new_phase = true);
}
