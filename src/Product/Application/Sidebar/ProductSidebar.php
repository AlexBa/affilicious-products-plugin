<?php
namespace Affilicious\Product\Application\Sidebar;

if(!defined('ABSPATH')) exit('Not allowed to access pages directly.');

class ProductSidebar implements SidebarInterface
{
    const ID = 'product-sidebar';

    /**
     * @inheritdoc
     */
    public function init()
    {
        register_sidebar(array(
            'name' => __('Product Sidebar', 'affilicious'),
            'id' => self::ID,
            'description' => __('Place your widgets into this sidebar, which is visible on every product page.', 'affilicious'),
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
            'before_widget' => '<li class="widget">',
            'after_widget' => '</li>',
        ));
    }
}