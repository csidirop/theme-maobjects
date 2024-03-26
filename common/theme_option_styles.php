<?php
    ($bodyBgColor = get_theme_option('body_bg_color')) || ($buttonBgColor = "#FFFFFF");
    ($borderColor = get_theme_option('border_color')) || ($buttonBgColor = "#DEDEDE");
    ($linkColor = get_theme_option('link_color')) || ($linkColor = "#C72E2E");
    ($headerImageHeight = get_theme_option('header_image_height')) || ($headerImageHeight = "auto");
    $headerImageHeightMobile = get_theme_option('header_image_height_mobile');
    $headerEverywhere = get_theme_option('header_everywhere');
    $headerImagePosition = get_theme_option('header_image_position');
    $headerImagePosition = str_replace('_', '-', $headerImagePosition);
    $backgroundImageUrl = get_theme_option('background_image');
    $backgroundImagePosition = get_theme_option('background_image_position');
    $backgroundImageRepeat = get_theme_option('background_image_repeat');
    $backgroundImageSize = get_theme_option('background_image_size');
    $backgroundImageOpacity = get_theme_option('background_image_opacity');
    $backgroundImageDonotshowundercontent = get_theme_option('background_image_donotshowundercontent');
    // Match easy to read options to CSS keywords:
    switch ($backgroundImagePosition) {
        case 'Fix to center':
            $backgroundImagePosition = 'center';
            break;
        case 'Fix to top':
            $backgroundImagePosition = 'top';
            break;
        case 'Fix to bottom':
            $backgroundImagePosition = 'bottom';
            break;
        case 'Fix to left':
            $backgroundImagePosition = 'left';
            break;
        case 'Fix to right':
            $backgroundImagePosition = 'right';
            break;
    }
    switch ($backgroundImageRepeat) {
        case 'Repeat':
            $backgroundImageRepeat = 'repeat';
            break;
        case 'Repeat horizontally':
            $backgroundImageRepeat = 'repeat-x';
            break;
        case 'Repeat vertically':
            $backgroundImageRepeat = 'repeat-y';
            break;
        case 'Do not repeat':
            $backgroundImageRepeat = 'no-repeat';
            break;
    }
    $backgroundImageRepeat = str_replace('_', '-', $backgroundImageRepeat); // TODO: why is it '_' in the first place
    switch ($backgroundImageSize) {
        case 'Auto':
            $backgroundImageSize = 'auto';
            break;
        case 'Cover':
            $backgroundImageSize = 'cover';
            break;
        case 'Contain':
            $backgroundImageSize = 'contain';
            break;
    }
    is_numeric($backgroundImageOpacity) ? $backgroundImageOpacity / 100 : 100;
?>

<style>

body,
#search-form,
#search-container input[type="text"],
#search-container button,
#advanced-form {
    background-color: <?php echo $bodyBgColor; ?>
}

header nav .navigation,
#search-container input[type="text"],
#search-container button,
#search-form.closed + .search-toggle,
#advanced-form,
#search-filters ul li,
#item-filters ul li,
.element-set h2,
#exhibit-page-navigation,
#exhibit-pages > ul > li:not(:last-of-type),
#exhibit-pages h4,
table,
th, 
td,
.search-entry,
.item-pagination.navigation,
.secondary-nav ul {
    border-color: <?php echo $borderColor; ?>
}

a,
.secondary-nav.navigation li.active a,
.pagination-nav .sorting a,
#sort-links .sorting a,
#exhibit-pages .current a {
    color: <?php echo $linkColor; ?>;
}

<?php if ($headerEverywhere !== '1'): ?>
#home #header-image {
    display: none;
}
<?php endif; ?>

#header-image {
    height: <?php echo $headerImageHeight; ?>;
    align-items: <?php echo $headerImagePosition; ?>;
}
<?php if ($headerImageHeightMobile !== ''): ?>
@media screen and (max-width:640px) {
    #header-image {
        height: <?php echo $headerImageHeightMobile; ?>;
    }
}
<?php endif; ?>

/* Userdefined Backgroundimage */
<?php 
    $storage = Zend_Registry::get('storage');
    $uri = $storage->getUri($storage->getPathByType(get_theme_option('background_image'), 'theme_uploads'));
    if ($backgroundImageUrl):
?>
    body {
        background-color: transparent;
        background-image: url('<?php echo $uri; ?>');
        background-size: <?php echo $backgroundImageSize; ?>;
        background-position: <?php echo $backgroundImagePosition; ?>;
        background-repeat: <?php echo $backgroundImageRepeat; ?>;
        background-attachment: fixed;
    }
    /* Make some elements transparent: */
    #search-form {
        background-color: transparent;
    }

    @media screen {
        #search-form {
            background-color: transparent;
        }
        #search-container #search-form.open + button.search-toggle {
            border: none;
            background-color: transparent;
        }
    }
<?php endif; ?>

<?php if($backgroundImageUrl && ($backgroundImageDonotshowundercontent == '1')): ?>
    #wrap {
        background-color: #FFFFFF;
        border-left-width: 20px;
        border-right-width: 20px;
        border-left-style: solid;
        border-right-style: solid;
        border-color: #FFFFFF;
    }
<?php endif; ?>

</style>