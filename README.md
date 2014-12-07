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

```diff
diff --git a/includes/bootstrap.inc b/includes/bootstrap.inc
index 9f37dfc..28566ea 100644
--- a/includes/bootstrap.inc
+++ b/includes/bootstrap.inc
@@ -2202,11 +2202,26 @@ function drupal_anonymous_user() {
  * @param boolean $new_phase
  *   A boolean, set to FALSE if calling drupal_bootstrap from inside a
  *   function called from drupal_bootstrap (recursion).
+ * @param Drupal\Core\BootstrapInterface $object
+ *   Drupal Bootstrap object.
  *
  * @return int
  *   The most recently completed phase.
  */
-function drupal_bootstrap($phase = NULL, $new_phase = TRUE) {
+function drupal_bootstrap($phase = NULL, $new_phase = TRUE, \Drupal\Core\BootstrapInterface $object = NULL) {
+
+  /** @var \Drupal\Core\BootstrapInterface $bootstrap */
+  static $bootstrap = NULL;
+
+  // On the first call, bootstrap object does not exist yet.
+  if (!isset($bootstrap)) {
+    if (!isset($object)) {
+      // If no bootstrap object is injected, use default bootstrap.
+      $object = new \Drupal\Core\Bootstrap();
+    }
+    $bootstrap = $object;
+  }
+
   // Not drupal_static(), because does not depend on any run-time information.
   static $phases = array(
     DRUPAL_BOOTSTRAP_CONFIGURATION,
@@ -2243,41 +2258,7 @@ function drupal_bootstrap($phase = NULL, $new_phase = TRUE) {
         $stored_phase = $current_phase;
       }
 
-      switch ($current_phase) {
-        case DRUPAL_BOOTSTRAP_CONFIGURATION:
-          _drupal_bootstrap_configuration();
-          break;
-
-        case DRUPAL_BOOTSTRAP_PAGE_CACHE:
-          _drupal_bootstrap_page_cache();
-          break;
-
-        case DRUPAL_BOOTSTRAP_DATABASE:
-          _drupal_bootstrap_database();
-          break;
-
-        case DRUPAL_BOOTSTRAP_VARIABLES:
-          _drupal_bootstrap_variables();
-          break;
-
-        case DRUPAL_BOOTSTRAP_SESSION:
-          require_once DRUPAL_ROOT . '/' . variable_get('session_inc', 'includes/session.inc');
-          drupal_session_initialize();
-          break;
-
-        case DRUPAL_BOOTSTRAP_PAGE_HEADER:
-          _drupal_bootstrap_page_header();
-          break;
-
-        case DRUPAL_BOOTSTRAP_LANGUAGE:
-          drupal_language_initialize();
-          break;
-
-        case DRUPAL_BOOTSTRAP_FULL:
-          require_once DRUPAL_ROOT . '/includes/common.inc';
-          _drupal_bootstrap_full();
-          break;
-      }
+      $bootstrap($current_phase);
     }
   }
   return $stored_phase;
```
