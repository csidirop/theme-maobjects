if (!CenterRow) {
    var CenterRow = {};
}

(function($) {

    CenterRow.megaMenu = function (menuSelector, customMenuOptions) {
        if (typeof menuSelector === 'undefined') {
            menuSelector = 'header nav';
        }

        var menuOptions = {
            /* prefix for generated unique id attributes, which are required
             to indicate aria-owns, aria-controls and aria-labelledby */
            uuidPrefix: "accessible-megamenu",

            /* css class used to define the megamenu styling */
            menuClass: "nav-menu",

            /* css class for a top-level navigation item in the megamenu */
            topNavItemClass: "nav-item",

            /* css class for a megamenu panel */
            panelClass: "sub-nav",

            /* css class for a group of items within a megamenu panel */
            panelGroupClass: "sub-nav-group",

            /* css class for the hover state */
            hoverClass: "hover",

            /* css class for the focus state */
            focusClass: "focus",

            /* css class for the open state */
            openClass: "open",

            openOnMouseover: true,
        };

        $.extend(menuOptions, customMenuOptions);

        $(menuSelector).filter(function() {
            return $(this).find('ul.navigation').length > 0
                && !$(this).hasClass('nav-mode-hamburger');
        }).accessibleMegaMenu(menuOptions);
    };

    Mashare.hamburgerMenu = function(menuSelector) {
        if (typeof menuSelector === 'undefined') {
            menuSelector = '#top-nav.nav-mode-hamburger';
        }

        $(menuSelector).each(function() {
            var $nav = $(this);
            var $button = $nav.find('.hamburger-menu-toggle').first();
            var $overlay = $nav.find('.hamburger-menu-overlay').first();
            var $primaryTriggers = $nav.find('[data-menu-target]');
            var $secondaryTriggers = $nav.find('[data-menu-subtarget]');
            var initialPrimaryId = $primaryTriggers.filter('.is-active').first().attr('data-menu-target');
            var initialSecondaryId = $secondaryTriggers.filter('.is-active').first().attr('data-menu-subtarget');

            if (!$button.length || !$overlay.length) {
                return;
            }

            var syncBranch = function(panelId, preferredChildId) {
                var $secondaryPanels = $overlay.find('[data-menu-panel]').filter(function() {
                    return !$(this).is('[data-menu-panel-parent]');
                });
                var $tertiaryPanels = $overlay.find('[data-menu-panel-parent]');
                var $primaryTrigger = $primaryTriggers.filter('[data-menu-target="' + panelId + '"]').first();
                var defaultChildId = preferredChildId || $primaryTrigger.attr('data-default-child');
                var $matchingChildTrigger = $secondaryTriggers.filter('[data-menu-parent="' + panelId + '"]');

                $primaryTriggers.removeClass('is-active');
                $primaryTrigger.addClass('is-active');

                $secondaryPanels.removeClass('is-active');
                $secondaryPanels.filter('[data-menu-panel="' + panelId + '"]').addClass('is-active');

                $secondaryTriggers.removeClass('is-active');
                $tertiaryPanels.removeClass('is-active');

                if (!defaultChildId && $matchingChildTrigger.length) {
                    defaultChildId = $matchingChildTrigger.first().attr('data-menu-subtarget');
                }

                if (defaultChildId) {
                    $secondaryTriggers.filter('[data-menu-subtarget="' + defaultChildId + '"]').addClass('is-active');
                    $tertiaryPanels.filter('[data-menu-panel="' + defaultChildId + '"][data-menu-panel-parent="' + panelId + '"]').addClass('is-active');
                }
            };

            var clearBranch = function() {
                var $secondaryPanels = $overlay.find('[data-menu-panel]').filter(function() {
                    return !$(this).is('[data-menu-panel-parent]');
                });
                var $tertiaryPanels = $overlay.find('[data-menu-panel-parent]');

                $primaryTriggers.removeClass('is-active');
                $secondaryTriggers.removeClass('is-active');
                $secondaryPanels.removeClass('is-active');
                $tertiaryPanels.removeClass('is-active');
            };

            var resetToInitialBranch = function() {
                if (initialPrimaryId) {
                    syncBranch(initialPrimaryId, initialSecondaryId);
                } else {
                    clearBranch();
                }
            };

            var closeMenu = function() {
                $button.attr('aria-expanded', 'false');
                $overlay.attr('hidden', true);
                $nav.removeClass('is-open');
                $('body').removeClass('hamburger-menu-open');
            };

            var openMenu = function() {
                $button.attr('aria-expanded', 'true');
                $overlay.removeAttr('hidden');
                $nav.addClass('is-open');
                $('body').addClass('hamburger-menu-open');

                resetToInitialBranch();
            };

            $button.off('click.hamburgerMenu').on('click.hamburgerMenu', function() {
                if ($nav.hasClass('is-open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            $primaryTriggers.off('.hamburgerMenuPrimary');
            $primaryTriggers.on('mouseenter.hamburgerMenuPrimary focusin.hamburgerMenuPrimary click.hamburgerMenuPrimary', function() {
                syncBranch($(this).attr('data-menu-target'));
            });

            $secondaryTriggers.off('.hamburgerMenuSecondary');
            $secondaryTriggers.on('mouseenter.hamburgerMenuSecondary focusin.hamburgerMenuSecondary click.hamburgerMenuSecondary', function() {
                var panelId = $(this).attr('data-menu-parent');
                syncBranch(panelId, $(this).attr('data-menu-subtarget'));
            });

            $overlay.off('mouseleave.hamburgerMenuOverlay').on('mouseleave.hamburgerMenuOverlay', function() {
                if ($nav.hasClass('is-open')) {
                    resetToInitialBranch();
                }
            });

            $overlay.find('[data-menu-close]').off('click.hamburgerMenu').on('click.hamburgerMenu', function() {
                closeMenu();
            });

            $(document).off('click.hamburgerMenu').on('click.hamburgerMenu', function(event) {
                if ($nav.hasClass('is-open') && !$nav.is(event.target) && $nav.has(event.target).length === 0) {
                    closeMenu();
                }
            });

            $(document).off('keydown.hamburgerMenu').on('keydown.hamburgerMenu', function(event) {
                if (event.key === 'Escape') {
                    closeMenu();
                    $button.trigger('focus');
                }
            });
        });
    };

    $(document).ready(function() {
        $('#advanced-form').parents('#search-container').addClass('with-advanced');

        $('.search-toggle').click(function() {
            $('#search-form').toggleClass('closed').toggleClass('open');
            if ($('#search-form').hasClass('open')) {
                $('#query').focus();
            }
        });

        Mashare.hamburgerMenu();
    });
})(jQuery)