(function($) {
    $(document).ready(function() {
        var lgContainer = document.getElementById('omeka-lightgallery');
        if (!lgContainer) {
            return;
        }

        function isPdfUrl(url) {
            var src = (url || '').toLowerCase();

            return src.indexOf('.pdf') !== -1
                || src.indexOf('application/pdf') !== -1
                || src.indexOf('pdf.js') !== -1
                || src.indexOf('viewer.html') !== -1;
        }

        function isPdfEmbed($embed) {
            var src = $embed.attr('src') || $embed.attr('data') || '';
            var type = ($embed.attr('type') || '').toLowerCase();

            return type.indexOf('pdf') !== -1
                || isPdfUrl(src);
        }

        function pageFitPdfUrl(url) {
            if (!url || url.indexOf('zoom=page-fit') !== -1) {
                return url;
            }

            var parts = url.split('#');
            var hash = parts.length > 1 ? parts.slice(1).join('#') : '';

            if (hash.match(/(^|&)zoom=/)) {
                hash = hash.replace(/(^|&)zoom=[^&]*/, '$1zoom=page-fit');
            } else {
                hash += (hash ? '&' : '') + 'zoom=page-fit';
            }

            return parts[0] + '#' + hash;
        }

        function preparePdfSourceUrls() {
            $(lgContainer).find('[data-src], [href], [src], [data]').each(function() {
                var $element = $(this);

                ['data-src', 'href', 'src', 'data'].forEach(function(attr) {
                    var value = $element.attr(attr);

                    if (isPdfUrl(value)) {
                        $element.attr(attr, pageFitPdfUrl(value));
                    }
                });
            });
        }

        function preparePdfEmbeds() {
            preparePdfSourceUrls();

            $('#omeka-lightgallery .lg-video-cont, .lg-container .lg-video-cont').each(function() {
                var $container = $(this);
                var $embed = $container.find('iframe, embed, object').first();
                var srcAttr = $embed.attr('src') ? 'src' : 'data';

                if (!$embed.length || !isPdfEmbed($embed)) {
                    return;
                }

                $container.addClass('lg-pdf-cont');
                $container.find('.lg-video').addClass('lg-pdf-viewer');
                $embed.addClass('lg-pdf-object');
                $embed.attr(srcAttr, pageFitPdfUrl($embed.attr(srcAttr)));
            });
        }

        lgContainer.addEventListener('lgAfterOpen', preparePdfEmbeds);
        lgContainer.addEventListener('lgAfterSlide', preparePdfEmbeds);
        lgContainer.addEventListener('lgSlideItemLoad', preparePdfEmbeds);
        preparePdfSourceUrls();

        var inlineGallery = lightGallery(lgContainer, {
            licenseKey: '76E9AA35-CDB54382-B1A52890-683C953F',
            container: lgContainer,
            hash: true,
            closable: false,
            thumbnail: true,
            showMaximizeIcon: true,
            captions: true,
            allowMediaOverlap: false,
            getCaptionFromTitleOrAlt: false,
            exThumbImage: 'data-thumb',
            showZoomInOutIcons: true,
            scale: 0.5,
            actualSize: false,
            infiniteZoom: false,
            // plugins: [lgThumbnail, lgVideo, lgZoom, lgHash, lgRotate],
            plugins: [lgThumbnail, lgVideo, lgZoom, lgHash],
        });

        inlineGallery.openGallery();
        preparePdfEmbeds();
        window.setTimeout(preparePdfEmbeds, 250);
        window.setTimeout(preparePdfEmbeds, 1000);

        var downloadButton = lgContainer.getElementsByClassName('lg-download');
        if (downloadButton.length) {
            downloadButton[0].addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
})(jQuery);
