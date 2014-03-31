<?php

namespace Drupal\Core;

/**
 * Class BootstrapPhases
 * @package Drupal\Core
 */
final class BootstrapPhases implements \ArrayAccess
{
    /** @see Bootstrap::__construct() */
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

    /**
     *
     */
    public function __construct()
    {
        $this->values = array(
            $this::CONFIGURATION => function () {
                    _drupal_bootstrap_configuration();
                },
            $this::PAGE_CACHE => function () {
                    _drupal_bootstrap_page_cache();
                },
            $this::DATABASE => function () {
                    _drupal_bootstrap_database();
                },
            $this::VARIABLES => function () {
                    _drupal_bootstrap_variables();
                },
            $this::SESSION => function () {
                    require_once DRUPAL_ROOT . '/' . variable_get('session_inc', 'includes/session.inc');
                    drupal_session_initialize();
                },
            $this::PAGE_HEADER => function () {
                    _drupal_bootstrap_page_header();
                },
            $this::LANGUAGE => function () {
                    drupal_language_initialize();
                },
            $this::FULL => function () {
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
    static function getPhases()
    {
        return range(self::CONFIGURATION, self::FULL);
    }

    public function all()
    {
        return $this->values;
    }
}
