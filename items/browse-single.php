<?php if (get_theme_option('browse_item_style') === 'list'): ?>
    <div class="item-meta hentry list">
        <h3><?php echo link_to_item(metadata($item, array('Dublin Core', 'Title'), array('class'=>'permalink'))); ?></h3>

        <div class="item-meta-content">
            <?php if (metadata($item, 'has thumbnail')): ?>
                <div class="item-img">
                <?php echo link_to_item(item_image()); ?>
                </div>
            <?php endif; ?>

            <span class="item-meta-details">
                <?php if ($creator = metadata('item', array('Dublin Core', 'Creator'))): ?>
                    <span class="item-creator"><?php echo $creator; ?></span>
                <?php endif; ?>
                <?php if ($date = metadata('item', array('Dublin Core', 'Date'))): ?>
                    <span class="item-date"><?php echo $date; ?></span>
                <?php endif; ?>
                <?php if ($description = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>350))): ?>
                    <div class="item-description"><?php echo $description; ?></div>
                <?php endif; ?>
            </span>
        </div>
    </div>
<?php else: ?>
    <div class="item hentry" >
        <div class="item-meta">
            <?php if (metadata('item', 'has files')): ?>
                <div class="item-img"> <?php echo link_to_item(item_image()); ?> </div>
            <?php endif; ?>

            <h2><?php echo link_to_item(null, array('class'=>'permalink')); ?></h2>

            <span class="item-meta-details">
                <?php if ($creator = metadata('item', array('Dublin Core', 'Creator'))): ?>
                    <span class="item-creator"> <?php echo $creator; ?> </span>
                <?php endif; ?>
                <?php if ($date = metadata('item', array('Dublin Core', 'Date'))): ?>
                    <span class="item-date"><?php echo $date; ?></span>
                <?php endif; ?>
            </span>
            <?php fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>
        </div>
    </div>
<?php endif; ?>