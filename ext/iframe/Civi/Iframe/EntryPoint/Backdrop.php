<?php

namespace Civi\Iframe\EntryPoint;

//TEMPLATE:START

/**
 * Begin processing of an embedded page-view on Backdrop
 */
class Backdrop {

  public static function main(): void {

    define('CIVICRM_IFRAME', 1);
    define('BACKDROP_ROOT', getcwd());

    // Do not accept cookies.
    // The whole issue is that browsers disagree on cookie-handling for embedded iframe content.
    // (Ex: Safari 16 doesn't send cookies; but Firefox 118 does.)
    // This means that `iframe.php` has the same cookie-less behavior for all browsers/users/tools.
    foreach (array_keys($_COOKIE) as $cookie) {
      unset($_COOKIE[$cookie]);
    }

    $GLOBALS['civicrm_url_defaults'][]['scheme'] = 'iframe';

    require_once BACKDROP_ROOT . '/core/includes/bootstrap.inc';
    \backdrop_bootstrap(BACKDROP_BOOTSTRAP_FULL);
    $GLOBALS['config']['x_frame_options'] = '';
    \civicrm_initialize();

    \Civi::service('iframe.router')->invoke([
      'route' => trim($_SERVER['PATH_INFO'], '/'),
    ]);
  }

}

//TEMPLATE:END
