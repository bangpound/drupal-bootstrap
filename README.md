Drupal 7 Bootstrap
==================

This library serves to replace Drupal 7's bootstrap in a manner that can be overridden.

Usage
-----

To use this, you must patch Drupal 7 core in a few places places.

In each front controller (`index.php`, `cron.php`, `authorize.php`, `install.php`,
`update.php`, and `xmlrpc.php`), you must include the Composer autoloader.

```php
include __DIR__ . '/vendor/autoload.php';
```

For Drush to work, you must add `drushrc.php` to `sites/all/drush`:

```php
<?php

// Autoloading for Drush from the Drupal root's composer.json and vendor directory.
require __DIR__ . '/../../../vendor/autoload.php';
```

The function `drupal_bootstrap()` in `includes/bootstrap.inc` must be replaced with this
function:

```php
/**
 * Ensures Drupal is bootstrapped to the specified phase.
 *
 * In order to bootstrap Drupal from another PHP script, you can use this code:
 * @code
 *   define('DRUPAL_ROOT', '/path/to/drupal');
 *   require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
 *   drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
 * @endcode
 *
 * @param $phase
 *   A constant telling which phase to bootstrap to. When you bootstrap to a
 *   particular phase, all earlier phases are run automatically. Possible
 *   values:
 *   - DRUPAL_BOOTSTRAP_CONFIGURATION: Initializes configuration.
 *   - DRUPAL_BOOTSTRAP_PAGE_CACHE: Tries to serve a cached page.
 *   - DRUPAL_BOOTSTRAP_DATABASE: Initializes the database layer.
 *   - DRUPAL_BOOTSTRAP_VARIABLES: Initializes the variable system.
 *   - DRUPAL_BOOTSTRAP_SESSION: Initializes session handling.
 *   - DRUPAL_BOOTSTRAP_PAGE_HEADER: Sets up the page header.
 *   - DRUPAL_BOOTSTRAP_LANGUAGE: Finds out the language of the page.
 *   - DRUPAL_BOOTSTRAP_FULL: Fully loads Drupal. Validates and fixes input
 *     data.
 * @param $new_phase
 *   A boolean, set to FALSE if calling drupal_bootstrap from inside a
 *   function called from drupal_bootstrap (recursion).
 * @param $object
 *   Drupal Bootstrap object.
 *
 * @return
 *   The most recently completed phase.
 */
function drupal_bootstrap($phase = NULL, $new_phase = TRUE, \Drupal\Core\Bootstrap $object = NULL) {
  static $bootstrap;
  if (!isset($bootstrap)) {
    if (!isset($object)) {
      $object = new \Drupal\Core\Bootstrap();
    }
    $bootstrap = $object;
  }
  return $bootstrap($phase, $new_phase);
}
```
