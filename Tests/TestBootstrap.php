<?php

namespace Drupal\Core\Tests;

use Drupal\Core\AbstractBootstrap;

class TestBootstrap extends AbstractBootstrap
{
    private $phases;
    private $skipped;

    public function __construct()
    {
        $this->phases = array(
            DRUPAL_BOOTSTRAP_CONFIGURATION => function () {},
            DRUPAL_BOOTSTRAP_PAGE_CACHE => function () {},
            DRUPAL_BOOTSTRAP_DATABASE => function () use (&$skipped) { $skipped = true; },
            DRUPAL_BOOTSTRAP_VARIABLES => function () {},
            DRUPAL_BOOTSTRAP_SESSION => function () {},
            DRUPAL_BOOTSTRAP_PAGE_HEADER => function () {},
            DRUPAL_BOOTSTRAP_LANGUAGE => function () {},
            DRUPAL_BOOTSTRAP_FULL => function () {},
        );
        $this->skipped = &$skipped;
    }

    /**
     * @return mixed
     */
    public function getSkipped()
    {
        return $this->skipped;
    }

    /**
     * @param $phase
     * @return mixed
     */
    protected function call($phase)
    {
        $this->phases[$phase]();
    }
}
