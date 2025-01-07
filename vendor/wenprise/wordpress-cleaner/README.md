# wp-menu-manager

WordPress Element Cleaner

## install

```bash
composer require wenprise/wordpress-cleaner
```

## usage

````php
$cleaner = new Wenprise\Cleaner();

//Remove top level menu
$cleaner->remove_menu([
    'edit.php',
    'edit.php?post_type=page',
    'edit.php?post_type=activity',
    'edit.php?post_type=comm',
    'upload.php',
    'themes.php',
    'plugins.php',
    'edit-comments.php',
    'tools.php',
    'options-general.php',
]);

$cleaner->remove_submenu('index.php', 10)
             ->remove_submenu('themes.php', [6, 15, 20])
             ->remove_submenu('woocommerce', 'report')
             ->remove_submenu('elementor', 'go_elementor_pro')
             ->remove_submenu('elementor', 'go_knowledge_base_site')
             ->remove_submenu('options-general.php', [10, 15, 20, 25, 30, 40]);

//Remove post metabox
$cleaner->remove_meta_box('commentsdiv', 'post', 'side');

//Remove Dashboard widget
$cleaner->remove_meta_box('dashboard_primary', 'dashboard', 'normal')
        ->remove_meta_box('e-dashboard-overview', 'dashboard', 'normal');

//Remove admin bar links
$cleaner->remove_admin_bar_menu('wp-logo');

if ( ! current_user_can('administrator')) {
    $cleaner->remove_submenu('edit.php?post_type=staff', [15]);
}
````
