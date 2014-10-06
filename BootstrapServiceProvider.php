<?php

namespace Drupal\Core;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class BootstrapPhases
 * @package Drupal\Core
 */
class BootstrapServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[DRUPAL_BOOTSTRAP_CONFIGURATION] = function () {
            _drupal_bootstrap_configuration();
        };
        $pimple[DRUPAL_BOOTSTRAP_PAGE_CACHE] = function () {
            _drupal_bootstrap_page_cache();
        };
        $pimple[DRUPAL_BOOTSTRAP_DATABASE] = function () {
            _drupal_bootstrap_database();
        };
        $pimple[DRUPAL_BOOTSTRAP_VARIABLES] = function () {
            _drupal_bootstrap_variables();
        };
        $pimple[DRUPAL_BOOTSTRAP_SESSION] = function () {
            require_once DRUPAL_ROOT.'/'.variable_get('session_inc', 'includes/session.inc');
            drupal_session_initialize();
        };
        $pimple[DRUPAL_BOOTSTRAP_PAGE_HEADER] = function () {
            _drupal_bootstrap_page_header();
        };
        $pimple[DRUPAL_BOOTSTRAP_LANGUAGE] = function () {
            drupal_language_initialize();
        };
        $pimple[DRUPAL_BOOTSTRAP_FULL] = function () {
            require_once DRUPAL_ROOT.'/includes/common.inc';
            _drupal_bootstrap_full();
        };
    }
}
