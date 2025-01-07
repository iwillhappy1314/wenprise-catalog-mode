<?php

namespace WenpriseCatalog;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Init {
    private $settings;

    private $wp_cleaner;

    public function __construct() {
        $this->settings = get_option('wenprise_catalog_settings', [
            // 价格相关
            'hide_loop_price' => 1,
            'hide_single_price' => 1,
            'hide_sale_flash' => 1,

            // 购物车按钮相关
            'remove_loop_add_to_cart' => 1,
            'remove_single_add_to_cart' => 1,
            'disable_add_to_cart_action' => 1,

            // 页面访问
            'disable_cart_page' => 1,
            'disable_checkout_page' => 1,

            // 后台功能
            'disable_analytics' => 1,
            'disable_marketing' => 1,
            'remove_payments_menu' => 1,
            'remove_marketing_menu' => 1,
        ]);

        $this->init_hooks();
        $this->setUpdateChecker();

        $this->wp_cleaner =  new \Wenprise\Cleaner();

        $this->wp_cleaner->remove_menu([
            'woocommerce',
        ]);
    }


    private function init_hooks() {
        // 价格相关
        if (!empty($this->settings['hide_loop_price'])) {
            remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
        }

        if (!empty($this->settings['hide_single_price'])) {
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        }

        if (!empty($this->settings['hide_sale_flash'])) {
            remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
            remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
        }

        // 购物车按钮相关
        if (!empty($this->settings['remove_loop_add_to_cart'])) {
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        }

        if (!empty($this->settings['remove_single_add_to_cart'])) {
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        }

        if (!empty($this->settings['disable_add_to_cart_action'])) {
            $priority = has_action('wp_loaded', ['WC_Form_Handler', 'add_to_cart_action']);
            if ($priority !== false) {
                remove_action('wp_loaded', ['WC_Form_Handler', 'add_to_cart_action'], $priority);
            }
            add_filter('woocommerce_loop_add_to_cart_link', '__return_empty_string', 10);
            add_filter('woocommerce_is_purchasable', '__return_false');
        }

        // 页面访问控制
        if (!empty($this->settings['disable_cart_page'])) {
            add_action('template_redirect', [$this, 'disable_cart_access']);
        }

        if (!empty($this->settings['disable_checkout_page'])) {
            add_action('template_redirect', [$this, 'disable_checkout_access']);
        }

        // 后台功能
        if (!empty($this->settings['disable_analytics']) || !empty($this->settings['disable_marketing'])) {
            add_filter('woocommerce_admin_features', [$this, 'disable_admin_features']);
        }

        if (!empty($this->settings['remove_payments_menu'])) {
            add_action('admin_menu', [$this, 'remove_payments_menu'], 999);
        }

        if (!empty($this->settings['remove_marketing_menu'])) {
            add_action('admin_menu', function() {
                $this->wp_cleaner->remove_menu([
                    'woocommerce-marketing',
                ]);
            });
        }
    }

    public function remove_payments_menu() {
        remove_menu_page('admin.php?page=wc-admin&task=payments');
        remove_menu_page('admin.php?page=wc-settings&tab=checkout');
    }

    public function disable_admin_features($features) {
        if (!empty($this->settings['disable_analytics'])) {
            $analytics = array_search('analytics', $features);
            if ($analytics !== false) {
                unset($features[$analytics]);
            }
        }

        if (!empty($this->settings['disable_marketing'])) {
            $marketing = array_search('marketing', $features);
            if ($marketing !== false) {
                unset($features[$marketing]);
            }
        }

        return array_values($features);
    }

    public function disable_cart_access() {
        if (is_cart()) {
            wp_redirect(get_home_url());
            exit;
        }
    }

    public function disable_checkout_access() {
        if (is_checkout()) {
            wp_redirect(get_home_url());
            exit;
        }
    }

    public function setUpdateChecker() {
        $update_checker = PucFactory::buildUpdateChecker(
            'https://api.wpcio.com/api/plugin/info/wenprise-catalog-mode',
            WENPRISE_CATALOG_MAIN_FILE,
            '_b'
        );
    }
}