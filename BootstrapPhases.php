<?php

namespace Drupal\Core;

/**
 * Class BootstrapPhases
 * @package Drupal\Core
 */
class BootstrapPhases implements \ArrayAccess
{
    const NEVER_STARTED = -1;
    const CONFIGURATION = 0;
    const PAGE_CACHE    = 1;
    const DATABASE      = 2;
    const VARIABLES     = 3;
    const SESSION       = 4;
    const PAGE_HEADER   = 5;
    const LANGUAGE      = 6;
    const FULL          = 7;

    private $values;

    public function __construct()
    {
        $this->values = array(
            self::CONFIGURATION => function () {
                    _drupal_bootstrap_configuration();
                },
            self::PAGE_CACHE => function () {
                    _drupal_bootstrap_page_cache();
                },
            self::DATABASE => function () {
                    _drupal_bootstrap_database();
                },
            self::VARIABLES => function () {
                    _drupal_bootstrap_variables();
                },
            self::SESSION => function () {
                    require_once DRUPAL_ROOT . '/' . variable_get('session_inc', 'includes/session.inc');
                    drupal_session_initialize();
                },
            self::PAGE_HEADER => function () {
                    _drupal_bootstrap_page_header();
                },
            self::LANGUAGE => function () {
                    drupal_language_initialize();
                },
            self::FULL => function () {
                    require_once DRUPAL_ROOT . '/includes/common.inc';
                    _drupal_bootstrap_full();
                },
        );
    }

    public function offsetExists($id)
    {
        return array_key_exists($id, $this->values);
    }

    public function offsetGet($id)
    {
        if (!array_key_exists($id, $this->values)) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        return $this->values[$id];
    }

    public function offsetSet($id, $value)
    {
        throw new \InvalidArgumentException(sprintf('"%s" cannot be changed.', __CLASS__));
    }

    public function offsetUnset($id)
    {
        throw new \InvalidArgumentException(sprintf('"%s" cannot be changed.', __CLASS__));
    }

    /**
     * Returns all defined value names.
     *
     * @return array An array of value names
     */
    public function keys()
    {
        return array_keys($this->values);
    }
}
