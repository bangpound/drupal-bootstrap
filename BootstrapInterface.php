<?php

namespace Drupal\Core;

/**
 * Interface BootstrapInterface
 * @package Drupal\Core
 */
interface BootstrapInterface
{
    /**
     * @param $current_phase
     */
    public function __invoke($current_phase);
}
