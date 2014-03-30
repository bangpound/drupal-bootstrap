<?php

namespace Drupal\Core\Tests;

use Drupal\Core\Bootstrap;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    private $cwd;

    public function setUp()
    {
        define('DRUPAL_ROOT', realpath(getcwd() .'/vendor/drupal/drupal'));
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
    }

    public function testBootstrap()
    {
        $bootstrap = new Bootstrap();

        $stored_phase = drupal_bootstrap(NULL, TRUE, $bootstrap);
        $this->assertEquals(-1, $stored_phase);

        $stored_phase = drupal_bootstrap(DRUPAL_BOOTSTRAP_CONFIGURATION);
        $this->assertEquals(0, $stored_phase);

        $stored_phase = drupal_bootstrap(DRUPAL_BOOTSTRAP_PAGE_CACHE);
        $this->assertEquals(1, $stored_phase);

        // Tests after this phase fail because there is no connection
        // to a database.
   }
}
