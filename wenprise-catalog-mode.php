<?php
/*
  Plugin name: WooCommerce Catalog Mode
  Plugin URI: https://www.wpzhiku.com
  Description: Enable WooCommerce Catalog mode
  Version: 1.0.1
  Author: iwillhappy1314
  Author URI: https://www.wpzhiku.com
  License: GNU General Public License
  Text Domain: wc-catalog-mode
  WC requires at least: 6.5.0
  WC tested up to: 7.0.0
  Requires Plugins: woocommerce
*/

// Die if called directly
if ( ! defined('WPINC')) {
    die;
}

const WENPRISE_CATALOG_MAIN_FILE = __FILE__;
define('WENPRISE_CATALOG_PATH', plugin_dir_path(__FILE__));

add_action('plugins_loaded', function ()
{
    if(!class_exists('WC_Checkout')){
        return;
    }

    require_once(WENPRISE_CATALOG_PATH . 'vendor/autoload.php');

    new WenpriseCatalog\Settings();
    new WenpriseCatalog\Init();
});