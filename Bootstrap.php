<?php

namespace Drupal\Core;

/**
 * Class Bootstrap
 * @package Drupal\Core
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Calls the bootstrap phase.
     *
     * This method can be overridden to support events in subclasses.
     *
     * @param $current_phase
     * @return mixed
     */
    public function __invoke($current_phase)
    {
        switch ($current_phase) {
            case DRUPAL_BOOTSTRAP_CONFIGURATION:
                _drupal_bootstrap_configuration();
                break;

            case DRUPAL_BOOTSTRAP_PAGE_CACHE:
                _drupal_bootstrap_page_cache();
                break;

            case DRUPAL_BOOTSTRAP_DATABASE:
                _drupal_bootstrap_database();
                break;

            case DRUPAL_BOOTSTRAP_VARIABLES:
                _drupal_bootstrap_variables();
                break;

            case DRUPAL_BOOTSTRAP_SESSION:
                require_once DRUPAL_ROOT.'/'.variable_get('session_inc', 'includes/session.inc');
                drupal_session_initialize();
                break;

            case DRUPAL_BOOTSTRAP_PAGE_HEADER:
                _drupal_bootstrap_page_header();
                break;

            case DRUPAL_BOOTSTRAP_LANGUAGE:
                drupal_language_initialize();
                break;

            case DRUPAL_BOOTSTRAP_FULL:
                require_once DRUPAL_ROOT.'/includes/common.inc';
                _drupal_bootstrap_full();
                break;
        }
    }
}
