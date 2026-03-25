<?php
$pageTitle = __('Browse Items');
$tagCloudRoute = class_exists('AvantSearch') ? 'find' : 'items/browse';

echo head(array('title' => $pageTitle, 'bodyclass' => 'items tags'));
?>

<h1><?php echo $pageTitle; ?></h1>

<nav class="items-nav navigation secondary-nav">
    <?php echo public_nav_items(); ?>
</nav>

<section class="tags-page-search" aria-labelledby="tags-page-search-heading">
    <div class="tags-page-search-form" role="search">
        <label class="sr-only" for="tags-page-search-query"><?php echo __('Filter tags'); ?></label>
        <input
            id="tags-page-search-query"
            type="search"
            value=""
            placeholder="<?php echo __('Filter tags'); ?>"
            aria-describedby="tags-page-search-status"
            autocomplete="off">
    </div>
    <p id="tags-page-search-status" class="tags-page-search-status" aria-live="polite">
        <?php echo __('Showing all tags.'); ?>
    </p>
</section>

<?php echo tag_cloud($tags, $tagCloudRoute); ?>

<?php echo foot(); ?>
