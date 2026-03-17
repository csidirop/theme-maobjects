<?php foreach ($children as $child): ?>
    <?php if (!$this->navigation()->accept($child)) continue; ?>
    <?php if ($child->get("separator") === true) continue; ?>
    <?php $hasChildren = $child->hasPages(); ?>
    <li class="hamburger-menu-item<?php echo $child->isActive() ? ' active' : ''; ?><?php echo $hasChildren ? ' has-children' : ''; ?>">
        <a
            href="<?php echo $child->getHref(); ?>"
            <?php if ($child->getTarget() != ""): ?>
                target="<?php echo $child->getTarget(); ?>"
            <?php endif; ?>
        >
            <?php echo html_escape($this->translate($child->getLabel())); ?>
        </a>
        <?php if ($hasChildren && get_theme_option('nav_show_levels') != 0): ?>
            <ul class="hamburger-submenu">
                <?php echo $this->partial('common/hamburger-menu-list.php', ['children' => $child->getPages()]); ?>
            </ul>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
