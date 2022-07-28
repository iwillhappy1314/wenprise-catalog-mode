<?php

namespace Wenprise;

class Cleaner
{
    /**
     * @var array
     */
    public $menus_to_remove = [];


    /**
     * @var array
     */
    public $submenus_to_remove = [];


    /**
     * @var array
     */
    public $metaboxes_to_remove = [];


    /**
     * AdminMenuManager constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'do_remove']);
    }


    /**
     * 执行移除操作
     */
    public function do_remove()
    {
        global $submenu;

        foreach ($this->menus_to_remove as $item) {
            remove_menu_page($item);
        }

        foreach ($this->submenus_to_remove as $parent => $indexes_or_slug) {

            if ( ! is_int($indexes_or_slug) && ! is_array($indexes_or_slug)) {
                remove_submenu_page($parent, $indexes_or_slug);
            } else {
                $indexes = (array)$indexes_or_slug;

                foreach ($indexes as $index) {
                    unset($submenu[ $parent ][ $index ]);
                }
            }

        }

        foreach ($this->metaboxes_to_remove as $metabox_to_remove) {
            remove_meta_box($metabox_to_remove[ 'id' ], $metabox_to_remove[ 'screen' ], $metabox_to_remove[ 'context' ]);
        }
    }


    /**
     * 移除顶级菜单
     *
     * @param string|array $slug
     *
     * @return $this
     */
    function remove_menu($slug)
    {
        if (is_array($slug)) {
            $this->menus_to_remove = array_merge($this->menus_to_remove, $slug);
        } else {
            $this->menus_to_remove[] = $slug;
        }

        return $this;
    }


    /**
     * 移除子菜单
     *
     * @param string       $parent
     * @param string|array $index
     *
     * @return $this
     */
    function remove_submenu($parent, $index)
    {
        $this->submenus_to_remove[ $parent ] = $index;

        return $this;
    }


    /**
     * 移除Metabox
     *
     * @param $id
     * @param $screen
     * @param $context
     *
     * @return $this
     */
    function remove_meta_box($id, $screen, $context)
    {
        $this->metaboxes_to_remove[] = [
            'id'      => $id,
            'screen'  => $screen,
            'context' => $context,
        ];

        return $this;
    }
}
