<?php

namespace WenpriseCatalog;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Init
{
    public function __construct()
    {
        add_filter('woocommerce_get_price_html', [$this, 'remove_frontend_prices']);
        add_action('init', [$this, 'remove_add_to_cart']);
        add_action('template_redirect', [$this, 'disable_cart_access']);
        add_action('template_redirect', [$this, 'disable_checkout_access']);
        add_filter('woocommerce_admin_features', [$this, 'disable_admin_features']);


        add_action('admin_menu', [$this, 'remove_payments_menu'], 999);

        $this->setUpdateChecker();
    }


    function remove_payments_menu()
    {
        remove_menu_page('admin.php?page=wc-admin&task=payments');
        remove_menu_page('admin.php?page=wc-settings&tab=checkout');
    }


    // Remove prices from front end
    public function remove_frontend_prices($price): string
    {
        return '';
    }

    // Remove Add to Cart button from front end
    public function remove_add_to_cart()
    {
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

        $priority = has_action('wp_loaded', ['WC_Form_Handler', 'add_to_cart_action']);

        remove_action('wp_loaded', ['WC_Form_Handler', 'add_to_cart_action'], $priority);

        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

        add_filter('woocommerce_loop_add_to_cart_link', '__return_empty_string', 10);
        add_filter('woocommerce_is_purchasable', '__return_false');

        add_filter('woocommerce_marketing_menu_items', '__return_empty_array');

        add_filter('woocommerce_admin_features', function ($features)
        {
            return array_values(
                array_filter($features, function ($feature)
                {
                    return;
                })
            );
        });

        $menu_manager = new \Wenprise\Cleaner();

        $menu_manager->remove_menu([
            'woocommerce',
            'woocommerce-marketing',
        ]);

        add_action('admin_head', function ()
        {
            echo '<style>
                      #wpbody{
                        margin-top: 0 !important;
                      }
                  </style>';
        });


    }


    public function disable_admin_features($features)
    {
        $analytics = array_search('analytics', $features);
        unset($features[ $analytics ]);

        $marketing = array_search('marketing', $features);
        unset($features[ $marketing ]);

        return $features;
    }


    // If user tries to access cart page, redirect to home
    public function disable_cart_access()
    {
        if (is_cart()) {
            wp_redirect(get_home_url());
            exit;
        }
    }

    // If user tries to access checkout page, redirect to home
    public function disable_checkout_access()
    {
        if (is_checkout()) {
            wp_redirect(get_home_url());
            exit;
        }
    }


    public function setUpdateChecker()
    {
        $update_checker = PucFactory::buildUpdateChecker(
            'https://api.wpcio.com/api/plugin/info/wenprise-catalog-mode',
            WENPRISE_CATALOG_MAIN_FILE,
            '_b'
        );
    }
}