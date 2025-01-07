<?php

namespace WenpriseCatalog;

class Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_save_catalog_settings', [$this, 'save_settings']);
        add_action('wp_ajax_get_catalog_settings', [$this, 'get_settings']);
    }

    public function add_settings_menu() {
        add_options_page(
            '目录模式设置',
            '目录模式设置',
            'manage_options',
            'wenprise-catalog-settings',
            [$this, 'render_settings_page']
        );
    }

    public function enqueue_scripts($hook) {
        if ('settings_page_wenprise-catalog-settings' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'wenprise-catalog-style',
            plugins_url('frontend/dist/css/app.css', WENPRISE_CATALOG_MAIN_FILE),
            [],
            filemtime(plugin_dir_path(WENPRISE_CATALOG_MAIN_FILE) . 'frontend/dist/css/app.css')
        );

        // 注册 React 组件
        wp_enqueue_script(
            'wenprise-catalog-settings',
            plugins_url('frontend/dist/js/settings.js', WENPRISE_CATALOG_MAIN_FILE),
            ['wp-element'],
            filemtime(plugin_dir_path(WENPRISE_CATALOG_MAIN_FILE) . 'frontend/dist/js/settings.js'),
            true
        );

        // 添加必要的数据
        wp_localize_script('wenprise-catalog-settings', 'wpCatalogSettings', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wenprise_catalog_settings'),
        ]);
    }

    public function render_settings_page() {
        echo '<div id="wenprise-catalog-settings"></div>';
    }

    public function get_settings() {
        check_ajax_referer('wenprise_catalog_settings', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('权限不足');
        }

        $settings = get_option('wenprise_catalog_settings', []);
        wp_send_json_success($settings);
    }

    public function save_settings() {
        check_ajax_referer('wenprise_catalog_settings', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('权限不足');
        }

        $settings = json_decode(stripslashes($_POST['settings']), true);

        if (update_option('wenprise_catalog_settings', $settings)) {
            wp_send_json_success('设置已保存');
        } else {
            wp_send_json_error('保存失败');
        }
    }

    /**
     * 获取所有设置的默认值
     */
    public static function get_default_settings() {
        return [
            'hide_loop_price' => 1,
            'hide_single_price' => 1,
            'hide_sale_flash' => 1,
            'remove_loop_add_to_cart' => 1,
            'remove_single_add_to_cart' => 1,
            'disable_add_to_cart_action' => 1,
            'disable_cart_page' => 1,
            'disable_checkout_page' => 1,
            'disable_analytics' => 1,
            'disable_marketing' => 1,
            'remove_payments_menu' => 1,
            'remove_marketing_menu' => 1,
        ];
    }
}