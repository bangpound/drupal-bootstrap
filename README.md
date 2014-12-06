Drupal 7 Bootstrap
==================

This library serves to replace Drupal 7's bootstrap in a manner that can be overridden.

Usage
-----

To use this, you must patch Drupal 7 core in a few places places.

In each front controller (`index.php`, `cron.php`, `authorize.php`, `install.php`,
`update.php`, and `xmlrpc.php`), you must include the Composer autoloader.

After

```php
define('DRUPAL_ROOT', getcwd());
```

add:

```php
require_once DRUPAL_ROOT .'/../vendor/autoload.php';
```

For Drush to work, you must add `drushrc.php` to `sites/all/drush`:

```php
<?php

// Autoloading for Drush from the Drupal root's composer.json and vendor directory.
require __DIR__ . '/../../../vendor/autoload.php';
```

Apply the included `drupal_bootstrap.patch` to your Drupal root directory. The patch
is included below.

<script src="https://gist.github.com/anonymous/2b0e1a028e153531d51b.js"></script>
