(function($) {
    $(document).ready(function() {
        var lgContainer = document.getElementById('omeka-lightgallery');
        if (!lgContainer) {
            return;
        }

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

        var downloadButton = lgContainer.getElementsByClassName('lg-download');
        if (downloadButton.length) {
            downloadButton[0].addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
})(jQuery);

