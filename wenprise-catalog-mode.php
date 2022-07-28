<?php
/*
  Plugin name: WooCommerce Catalog Mode
  Plugin URI: https://www.wpzhiku.com
  Description: Enable WooCommerce Catalog mode
  Version: 1.0
  Author: iwillhappy1314
  Author URI: https://www.wpzhiku.com
  License: GNU General Public License
  Text Domain: wc-catalog-mode
*/

// Die if called directly
if ( ! defined('WPINC')) {
    die;
}

define('WENPRISE_CATALOG_PATH', plugin_dir_path(__FILE__));

add_action('plugins_loaded', function ()
{
    require_once(WENPRISE_CATALOG_PATH . 'vendor/autoload.php');

    new WenpriseCatalog\Init();
});