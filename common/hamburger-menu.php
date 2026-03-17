<?php
/* @var $container Zend\Navigation\Navigation */
$container = $this->container;
$menuId = 'header-menu-panel';
$showChildren = get_theme_option('nav_show_levels') != 0;
$hasVisibleChildren = function ($page) {
    foreach ($page->getPages() as $child) {
        if (get_view()->navigation()->accept($child) && $child->get("separator") !== true) {
            return true;
        }
    }

    return false;
};

$topPages = [];
$activeTopPanel = null;
$activeChildPanel = null;

foreach ($container as $page) {
    if (!$this->navigation()->accept($page) || $page->get("separator") === true) {
        continue;
    }

    $topPanelId = 'menu-panel-' . md5((string) $page->getHref() . '|' . (string) $page->getLabel());
    $pageHasChildren = $showChildren && $hasVisibleChildren($page);
    $activeChildForPage = null;

    foreach ($page->getPages() as $child) {
        if (!$this->navigation()->accept($child) || $child->get("separator") === true) {
            continue;
        }

        if ($showChildren && $hasVisibleChildren($child) && $child->isActive()) {
            $activeChildForPage = 'menu-subpanel-' . md5((string) $child->getHref() . '|' . (string) $child->getLabel());
        }
    }

    if ($page->isActive()) {
        $activeTopPanel = $topPanelId;
        if ($activeChildForPage) {
            $activeChildPanel = $activeChildForPage;
        }
    }

    $topPages[] = [
        'page' => $page,
        'panel_id' => $topPanelId,
        'has_children' => $pageHasChildren,
        'active_child_panel' => $activeChildForPage,
    ];
}
?>
<button
    class="modal-button hamburger-menu-toggle"
    type="button"
    aria-controls="<?php echo $menuId; ?>"
    aria-expanded="false"
    aria-haspopup="dialog"
>
    <span class="burger-button-text"><?php echo __('Menu'); ?></span>
    <svg class="svg-icon-burger-dims" aria-hidden="true" focusable="false">
        <use xlink:href="/sprite.svg#icon-burger-bold"></use>
    </svg>
</button>
<div id="<?php echo $menuId; ?>" class="hamburger-menu-overlay" hidden>
    <div class="hamburger-menu-backdrop" data-menu-close="true"></div>
    <div
        class="hamburger-menu-dialog"
        role="dialog"
        aria-modal="true"
        aria-label="<?php echo __('Menu'); ?>"
    >
        <div class="hamburger-menu-header">
            <div class="hamburger-menu-brand">
                <?php if (get_theme_option('logo')): ?>
                    <a href="<?php echo html_escape(url('/')); ?>" class="hamburger-menu-brand-link">
                        <?php echo theme_logo(); ?>
                    </a>
                <?php else: ?>
                    <span class="hamburger-menu-title"><?php echo option('site_title'); ?></span>
                <?php endif; ?>
            </div>
            <button class="hamburger-menu-close" type="button" data-menu-close="true" aria-label="<?php echo __('Close menu'); ?>">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="hamburger-menu-body">
            <div class="hamburger-menu-column hamburger-menu-column-primary">
                <ul class="navigation hamburger-primary-list">
                    <?php foreach ($topPages as $topPage): ?>
                        <?php $page = $topPage['page']; ?>
                        <li class="hamburger-primary-item<?php echo $page->isActive() ? ' active' : ''; ?>">
                            <?php if ($topPage['has_children']): ?>
                                <div class="hamburger-menu-row">
                                    <button
                                        class="hamburger-primary-trigger<?php echo $topPage['panel_id'] === $activeTopPanel ? ' is-active' : ''; ?>"
                                        type="button"
                                        data-menu-target="<?php echo $topPage['panel_id']; ?>"
                                        data-default-child="<?php echo $topPage['active_child_panel']; ?>"
                                    >
                                        <?php echo html_escape($this->translate($page->getLabel())); ?>
                                    </button>
                                    <a class="hamburger-menu-link" href="<?php echo $page->getHref(); ?>" aria-label="<?php echo __('Open page'); ?>">
                                        &rarr;
                                    </a>
                                </div>
                            <?php else: ?>
                                <a class="hamburger-primary-link" href="<?php echo $page->getHref(); ?>">
                                    <?php echo html_escape($this->translate($page->getLabel())); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="hamburger-menu-column hamburger-menu-column-secondary">
                <?php foreach ($topPages as $topPage): ?>
                    <?php if (!$topPage['has_children']) continue; ?>
                    <?php $page = $topPage['page']; ?>
                    <section
                        class="hamburger-panel-group<?php echo $topPage['panel_id'] === $activeTopPanel ? ' is-active' : ''; ?>"
                        data-menu-panel="<?php echo $topPage['panel_id']; ?>"
                    >
                        <div class="hamburger-panel-header">
                            <a href="<?php echo $page->getHref(); ?>"><?php echo html_escape($this->translate($page->getLabel())); ?></a>
                        </div>
                        <ul class="navigation hamburger-secondary-list">
                            <?php foreach ($page->getPages() as $child): ?>
                                <?php if (!$this->navigation()->accept($child) || $child->get("separator") === true) continue; ?>
                                <?php $childHasChildren = $showChildren && $hasVisibleChildren($child); ?>
                                <?php $childPanelId = 'menu-subpanel-' . md5((string) $child->getHref() . '|' . (string) $child->getLabel()); ?>
                                <li class="hamburger-secondary-item<?php echo $child->isActive() ? ' active' : ''; ?>">
                                    <?php if ($childHasChildren): ?>
                                        <div class="hamburger-menu-row">
                                            <button
                                                class="hamburger-secondary-trigger<?php echo $childPanelId === $activeChildPanel ? ' is-active' : ''; ?>"
                                                type="button"
                                                data-menu-subtarget="<?php echo $childPanelId; ?>"
                                                data-menu-parent="<?php echo $topPage['panel_id']; ?>"
                                            >
                                                <?php echo html_escape($this->translate($child->getLabel())); ?>
                                            </button>
                                            <a class="hamburger-menu-link" href="<?php echo $child->getHref(); ?>" aria-label="<?php echo __('Open page'); ?>">
                                                &rarr;
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <a class="hamburger-secondary-link" href="<?php echo $child->getHref(); ?>">
                                            <?php echo html_escape($this->translate($child->getLabel())); ?>
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endforeach; ?>
            </div>

            <div class="hamburger-menu-column hamburger-menu-column-tertiary">
                <?php foreach ($topPages as $topPage): ?>
                    <?php if (!$topPage['has_children']) continue; ?>
                    <?php foreach ($topPage['page']->getPages() as $child): ?>
                        <?php if (!$this->navigation()->accept($child) || $child->get("separator") === true) continue; ?>
                        <?php if (!($showChildren && $hasVisibleChildren($child))) continue; ?>
                        <?php $childPanelId = 'menu-subpanel-' . md5((string) $child->getHref() . '|' . (string) $child->getLabel()); ?>
                        <section
                            class="hamburger-panel-group<?php echo $childPanelId === $activeChildPanel ? ' is-active' : ''; ?>"
                            data-menu-panel="<?php echo $childPanelId; ?>"
                            data-menu-panel-parent="<?php echo $topPage['panel_id']; ?>"
                        >
                            <div class="hamburger-panel-header">
                                <a href="<?php echo $child->getHref(); ?>"><?php echo html_escape($this->translate($child->getLabel())); ?></a>
                            </div>
                            <ul class="navigation hamburger-tertiary-list">
                                <?php echo $this->partial('common/hamburger-menu-list.php', ['children' => $child->getPages()]); ?>
                            </ul>
                        </section>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
