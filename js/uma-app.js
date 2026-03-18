(function ($) {
    'use strict';

    var COOKIE_LIFETIME_MS = 31536000000;
    var COOKIE_PATH = '; path=/';
    var trackingInitialized = false;

    // Mirror the TYPO3 cookie domain behavior so existing consent cookies stay valid.
    function getCookieDomain() {
        return /(^|\.)uni-mannheim\.de$/.test(window.location.hostname)
            ? '; domain=.uni-mannheim.de'
            : '';
    }

    // Keep consent for one year, matching the shared site bundle.
    function getCookieExpiry() {
        var cookieDate = new Date();
        cookieDate.setTime(cookieDate.getTime() + COOKIE_LIFETIME_MS);
        return '; expires=' + cookieDate.toUTCString();
    }

    // Convert document.cookie into a simple lookup object for consent checks.
    function getCookies() {
        return document.cookie.split(';').reduce(function (cookies, cookie) {
            var trimmedCookie = $.trim(cookie);

            if (!trimmedCookie) {
                return cookies;
            }

            var separatorIndex = trimmedCookie.indexOf('=');
            var name = separatorIndex >= 0 ? trimmedCookie.slice(0, separatorIndex) : trimmedCookie;
            var value = separatorIndex >= 0 ? trimmedCookie.slice(separatorIndex + 1) : '';

            cookies[name] = value;
            return cookies;
        }, {});
    }

    // The footer contains both consent states; toggle the visible one in place.
    function updateCookieControls(cookies) {
        var accepted = cookies.cookieAccept === '1';

        $('.cookie-status-accepted').toggleClass('hide', !accepted);
        $('.cookie-status-rejected').toggleClass('hide', accepted);
    }

    // Initialize Matomo only after consent has been handled and only once per page load.
    function initTracking(cookies) {
        if (trackingInitialized || typeof window._paq === 'undefined' || !Array.isArray(window.siteIds)) {
            return;
        }

        trackingInitialized = true;

        var trackerBaseUrl = '//webstats.uni-mannheim.de/';

        if (cookies.cookieAccept !== '1') {
            window._paq.push(['disableCookies']);
        }

        window._paq.push(['setConsentGiven']);
        window._paq.push(['setTrackerUrl', trackerBaseUrl + 'piwik.php']);

        var trackerCount = 0;

        $.each(window.siteIds, function (index, siteId) {
            if (!siteId) {
                return;
            }

            if (trackerCount === 0) {
                window._paq.push(['setSiteId', siteId]);
            } else {
                window._paq.push(['addTracker', trackerBaseUrl + 'piwik.php', siteId]);
            }

            trackerCount += 1;
        });

        var script = document.createElement('script');
        var firstScript = document.getElementsByTagName('script')[0];

        script.type = 'text/javascript';
        script.async = true;
        script.defer = true;
        script.src = trackerBaseUrl + 'piwik.js';

        firstScript.parentNode.insertBefore(script, firstScript);
    }

    // Persist the consent decision using the legacy cookie names expected by the site.
    function saveCookieConsent(accepted) {
        var cookieSuffix = getCookieExpiry() + COOKIE_PATH + getCookieDomain();

        document.cookie = 'cookieNotice=1' + cookieSuffix;
        document.cookie = 'cookieAccept=' + (accepted ? '1' : '0') + cookieSuffix;

        var cookies = getCookies();

        updateCookieControls(cookies);
        initTracking(cookies);
        $('.cookie-notice').removeClass('visible');
    }

    // Reproduce the shared scroll button behavior, including footer avoidance classes.
    function updateScrollButtonState() {
        var $window = $(window);
        var $body = $('body');
        var isNearMetafooter = false;
        var $metafooter = $('.metafooter');
        var $footer = $('footer').first();

        $body.toggleClass('enable-scroll-top-button', $window.scrollTop() > $window.height() / 3);

        if ($metafooter.length) {
            var metafooterOffset = $metafooter.offset().top;
            var metafooterWidth = $metafooter.width();
            var isWithinMetafooterRange =
                $window.scrollTop() > metafooterOffset - $window.height() + 32 &&
                $window.scrollTop() <= metafooterOffset + $metafooter.outerHeight() - $window.height() + 32 &&
                ($window.width() - metafooterWidth) / 2 <= 80;

            isNearMetafooter = isWithinMetafooterRange;
            $body.toggleClass('scroll-pos-metafooter', isWithinMetafooterRange);
        } else {
            $body.removeClass('scroll-pos-metafooter');
        }

        if ($footer.length) {
            var isNearFooter = $window.scrollTop() > $footer.offset().top - $window.height() + 32 && !isNearMetafooter;
            $body.toggleClass('scroll-pos-footer', isNearFooter);
        } else {
            $body.removeClass('scroll-pos-footer');
        }
    }

    $(function () {
        // Smooth-scroll back to the top when the floating button is used.
        $('#scroll-to-top').on('click', function () {
            $('html, body').animate({scrollTop: 0}, 400);
            return false;
        });

        // Consent buttons share the same storage flow but differ in the stored value.
        $('.cookie-accept').on('click', function () {
            saveCookieConsent(true);
        });

        $('.cookie-reject').on('click', function () {
            saveCookieConsent(false);
        });

        // Some pages may provide a placeholder for the controls outside the footer.
        $('.insert-cookie-controls').each(function () {
            $(this).append($('.cookie-controls').removeClass('hide'));
        });

        var cookies = getCookies();

        updateCookieControls(cookies);

        if (cookies.cookieNotice === '1') {
            initTracking(cookies);
        } else {
            $('.cookie-notice').addClass('visible');
        }

        updateScrollButtonState();
        $(window).on('scroll resize orientationchange', updateScrollButtonState);
    });
}(jQuery));
