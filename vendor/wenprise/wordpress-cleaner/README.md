# wp-menu-manager
WordPress Admin Menu Manager

## install

```bash
composer require wenprise/wordpress-cleaner
```

## usage

````php
$menu_manager = new Wenprise\MenuManager\Cleaner();

$menu_manager->remove_menu([
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

$menu_manager->remove_submenu('index.php', 10)
             ->remove_submenu('themes.php', [6, 15, 20])
             ->remove_submenu('options-general.php', [10, 15, 20, 25, 30, 40]);
                          
$menu_manager->remove_meta_box('commentsdiv', 'post', 'side');

if ( ! current_user_can('administrator')) {
    $menu_manager->remove_submenu('edit.php?post_type=staff', [15]);
}
````
