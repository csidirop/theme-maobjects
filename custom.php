<?php

require_once dirname(__FILE__) . '/functions.php';

add_filter(array('Display', 'Item', 'Item Type Metadata', 'URL'), 'make_url_link');
add_filter(array('Display', 'Item', 'Item Type Metadata', 'Link'), 'make_url_link');

function make_url_link($url)
{
    if(empty($url)) {
        return;
    }
    return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
}

/**
 * Get the navigation for items. (2. level navigation bar)
 *  - Replacing public_nav_items
 *  - Reorder entries
 *
 * @package Omeka\Function\View\Navigation
 * @uses nav()
 * @param array $navArray
 * @param int|null $maxDepth
 * @return string
 */
function custom_public_nav_items(array $navArray = null, $maxDepth = 0)
{
    if (!$navArray) {
        $navArray = array();
        if (total_records('Tag')) {
            $navArray[] = array(
                    'label' => __('Browse by Tag'),
                    'uri' => url('items/tags')
                );
        }
        $navArray[] = array(
                'label' => __('Browse All'),
                'uri' => url('/find'),
            );
    }
    return nav($navArray, 'public_navigation_items');
}
