<?php
$linkToFileMetadata = get_option('link_to_file_metadata');
$itemFiles = $item->Files;
$useLightgallery  = get_theme_option('media_lightgallery');
$mediaOnlyPrimary = get_theme_option('media_only_primary');
if ($itemFiles && $useLightgallery) {
    queue_lightgallery_assets();
}
echo head(array('title' => metadata('item', array('Dublin Core', 'Title')), 'bodyclass' => 'items show'));
?>

<?php if (get_theme_option('hide_item_heading') != 1): ?>
    <h1><?php echo metadata('item', 'rich_title', array('no_escape' => true)); ?></h1>
<?php endif; ?>

<nav class="item-nav-buttons">
    <ul class="item-pagination navigation">
        <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
        <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
    </ul>
</nav>

<div class="content-container">
    <div class="primary-content">
        <?php
        // Regular display of all mediafiles:
        if ($itemFiles && !$useLightgallery && !$mediaOnlyPrimary) {
            echo files_for_item(array('imageSize' => 'thumbnail'), array('class' => 'element center'));
        // Display only primary media (first file):
        } elseif ($itemFiles && !$useLightgallery && $mediaOnlyPrimary) {
            $image = item_image('thumbnail', array(), 0, $item);
            $url = metadata('item', array('Item Type Metadata', 'URL'), array('no_filter' => true));
            // If a URL exists in the metadata, link the image to that URL:
            echo $url ? '<a class="cover" target="_blank" href="' . $url . '">' . $image . '</a>' : $image;
        // Display all files using lightgallery
        } elseif ($itemFiles && $useLightgallery) {
            echo lightGallery($itemFiles);
        }
        ?>
    </div>

    <div class="secondary-content">
        <!-- Add all metadata entries: -->
        <?php echo all_element_texts('item'); ?>

        <!-- Hide metadata entry if option is set: -->
        <style>
            <?php if(get_theme_option('hide_item_metadata_title')) : ?>
                #dublin-core-title {
                    display: none;
                }
            <?php endif; ?>
        </style>

        <!-- If the item belongs to a collection, create a link to that collection: -->
        <?php if (metadata('item', 'Collection Name')): ?>
        <div id="collection" class="element">
            <h3><?php echo __('Collection'); ?></h3>
            <div class="element-text"><p><?php echo link_to_collection_for_item(); ?></p></div>
        </div>
        <?php endif; ?>

        <!-- Add a list of all tags associated with the item: -->
        <?php if (metadata('item', 'has tags')): ?>
        <div id="item-tags" class="element">
            <h3><?php echo __('Tags'); ?></h3>
            <div class="element-text"><?php echo tag_string('item'); ?></div>
        </div>
        <?php endif;?>

        <?php if ((get_theme_option('other_media') == 1) && $itemFiles): ?>
        <?php echo lightgallery_other_files($itemFiles); ?>
        <?php endif; ?>

        <!-- Add a citation for this item: -->
        <?php if (get_theme_option('show_citation') == 1): ?>
        <div id="item-citation" class="element">
            <h3><?php echo __('Citation'); ?></h3>
            <div class="element-text"><?php echo metadata('item', 'citation', array('no_escape' => true)); ?></div>
        </div>
        <?php endif; ?>

        <!-- Adds the output formats in a collapsible menu: -->
        <?php if (get_theme_option('show_outputformats') == 1): ?>
         <div id="item-output-formats" class="element">
            <h3><?php echo __('Output Formats'); ?></h3>
            <details class="element-text outputs">
                <summary class="outputs-label">
                    <?php echo __('Show'); ?>
                </summary>
                <?php echo output_format_list(); ?>
            </details>
        </div>
        <?php endif; ?>

        <div id="item-output-formats" class="element">
            <h3><?php echo __('Output Formats'); ?></h3>

            <!-- Trigger button -->
            <button type="button" class="outputs-label" popovertarget="output-formats-popover" popovertargetaction="toggle">
                <?php echo __('Show'); ?>
            </button>

            <!-- Popover content -->
            <div id="output-formats-popover" popover class="element-text outputs">
                <?php echo output_format_list(); ?>
            </div>
        </div>
        
        <style>
            .element-text.outputs[popover] {
                top: 50%;
                right: 0;
                padding: 0.75rem 5rem 0.75rem 0.5rem;
                margin-left: -0.5rem;
                /* transform: translateY(-50%); */
                border: 1px solid #ccc;
                border-radius: 0.4rem;
                background: #fff;
                width: fit-content;

                transform: translate(100%, -50%);  /* start off-screen to the right */
                opacity: 0;


            }

            .element-text.outputs[popover]:popover-open {
                transform: translate(0, -50%);     /* slide into view */
                opacity: 1;
                                /* animation */
                transition:
                    transform 0.25s ease-out,
                    opacity 0.25s ease-out;
            }

            button {
                width: 36px;
                overflow: hidden;
                text-indent: -9999px;
                position: relative;
            }
            button:after {
                content: "\f002";
                font-family: "Font Awesome 5 Free";
                font-weight: 900;
                position: absolute;
                top: 6px;
                right: 0;
                text-indent: 0;
                width: 36px;
                text-align: center;
                cursor: pointer;
            }
        </style>
    </div>
</div>

<!-- Adds additional Item content generated by plugins, like: GeoLocation Map or Item Relations -->
<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>

<nav class="item-nav-buttons">
    <ul class="item-pagination navigation">
        <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
        <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
    </ul>
</nav>

<?php echo foot(); ?>
