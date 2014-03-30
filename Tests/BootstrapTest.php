<?php

namespace Drupal\Core\Tests;

/**
 * Class BootstrapTest
 * @package Drupal\Core\Tests
 */
class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    private $object;
    private $site;

    /**
     * @see http://heyrocker.com/content/installing-drupal-7-non-interactively
     */
    public function setUp()
    {
        define('DRUPAL_ROOT', realpath(getcwd() .'/vendor/drupal/drupal'));
        require_once DRUPAL_ROOT . '/includes/bootstrap.inc';

        $this->object = new TestBootstrap();

        drupal_bootstrap(NULL, TRUE, $this->object);
    }

    public function testBootstrap()
    {
        $stored_phase = drupal_bootstrap();
        $this->assertEquals(-1, $stored_phase, 'Stored phase starts as -1');

        $stored_phase = drupal_bootstrap(DRUPAL_BOOTSTRAP_CONFIGURATION);
        $this->assertEquals(0, $stored_phase, 'Configuration phase returns 0');

        $stored_phase = drupal_bootstrap(DRUPAL_BOOTSTRAP_PAGE_CACHE);
        $this->assertEquals(1, $stored_phase, 'Configuration phase returns 1');

        $this->assertNull($this->object->getSkipped(), 'Closure sets value when phase 2 is called implcitly');

        $stored_phase = drupal_bootstrap(DRUPAL_BOOTSTRAP_VARIABLES);
        $this->assertNotEquals(2, $stored_phase, 'Stored phase never equals 2.');
        $this->assertEquals(3, $stored_phase);
        $this->assertTrue($this->object->getSkipped(), 'Proof the 2 phase was executed.');

        $stored_phase = drupal_bootstrap(DRUPAL_BOOTSTRAP_CONFIGURATION);
        $this->assertNotEquals(0, $stored_phase);
        $this->assertEquals(3, $stored_phase);

        $stored_phase = drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        $this->assertEquals(7, $stored_phase);
    }
}
