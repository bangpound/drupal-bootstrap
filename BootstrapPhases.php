<?php

namespace Drupal\Core;

/**
 * Class BootstrapPhases
 * @package Drupal\Core
 */
class BootstrapPhases
{
    public static function get()
    {
        return array(
            // DRUPAL_BOOTSTRAP_CONFIGURATION
            0 =>
                function () {
                    _drupal_bootstrap_configuration();
                },

            // DRUPAL_BOOTSTRAP_PAGE_CACHE
            1 =>
                function () {
                    _drupal_bootstrap_page_cache();
                },

            // DRUPAL_BOOTSTRAP_DATABASE
            2 =>
                function () {
                    _drupal_bootstrap_database();
                },

            // DRUPAL_BOOTSTRAP_VARIABLES
            3 =>
                function () {
                    _drupal_bootstrap_variables();
                },

            // DRUPAL_BOOTSTRAP_SESSION
            4 =>
                function () {
                    require_once DRUPAL_ROOT . '/' . variable_get('session_inc', 'includes/session.inc');
                    drupal_session_initialize();
                },

            // DRUPAL_BOOTSTRAP_PAGE_HEADER
            5 =>
                function () {
                    _drupal_bootstrap_page_header();
                },

            // DRUPAL_BOOTSTRAP_LANGUAGE
            6 =>
                function () {
                    drupal_language_initialize();
                },

            // DRUPAL_BOOTSTRAP_FULL
            7 =>
                function () {
                    require_once DRUPAL_ROOT . '/includes/common.inc';
                    _drupal_bootstrap_full();
                },
        );
    }
}
