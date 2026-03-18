<?php

function mashare_display_featured_exhibit() {
    $html = '';
    $featuredExhibit = exhibit_builder_random_featured_exhibit();
    if ($featuredExhibit) {
        $html .= get_view()->partial('exhibit-builder/exhibits/single.php', array('exhibit' => $featuredExhibit));
    }
    $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    return $html;
}

function centerrow_get_square_thumbnail_url($file, $view) {
    if ($file->hasThumbnail()) {
        $squareThumbnail = file_display_url($file, 'square_thumbnail');
    } else {
        $mimeType = $file->mime_type;
        $fileType = (strpos($mimeType, 'image')) ? 'image' : 'video';
        $squareThumbnail = $view->baseUrl() . '/application/views/scripts/images/fallback-' . $fileType . '.png';
    }
    return $squareThumbnail;
}

function centerrow_public_nav_main() {
    $view = get_view();
    $nav = new Omeka_Navigation;
    $nav->loadAsOption(Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_OPTION_NAME);
    $nav->addPagesFromFilter(Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_FILTER_NAME);
    $html = $view->navigation()->menu($nav)->setPartial('common/accessible-megamenu.php')->render();
    $view->navigation()->menu($nav)->setPartial(null);
    return $html;
}

function maobjects_queue_lightgallery_assets() {
    queue_css_file('lightgallery');
    queue_css_file('lightgallery-bundle.min', 'all', false, 'javascripts/vendor/lightgallery/css');
    queue_js_file(array(
        'vendor/lightgallery/lightgallery.min',
        'vendor/lightgallery/plugins/thumbnail/lg-thumbnail.min',
        'vendor/lightgallery/plugins/video/lg-video.min',
        'vendor/lightgallery/plugins/rotate/lg-rotate.min',
        'vendor/lightgallery/plugins/hash/lg-hash.min',
        'vendor/lightgallery/plugins/zoom/lg-zoom.min',
    ));
    queue_js_file('lg-itemfiles-config', 'js');
}
?>
