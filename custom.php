<?php

require_once dirname(__FILE__) . '/functions.php';

add_filter(array('Display', 'Item', 'Item Type Metadata', 'URL'), 'make_url_link');

function make_url_link($url)
{
    if(empty($url)) {
        return;
    }
    return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
}
