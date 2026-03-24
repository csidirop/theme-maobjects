(function($) {
    $(document).ready(function() {
        var $searchContainer = $('#search-container');
        var $searchForm = $('#search-form');
        var $searchToggle = $('.search-toggle');
        var $searchQuery = $('#query');

        function setSearchState(isOpen) {
            $searchForm.toggleClass('open', isOpen).toggleClass('closed', !isOpen);
            $searchToggle.attr('aria-expanded', isOpen ? 'true' : 'false');
            $searchContainer.toggleClass('search-open', isOpen);

            if (isOpen) {
                window.requestAnimationFrame(function() {
                    $searchQuery.trigger('focus');
                });
            }
        }

        if ($searchForm.length && $searchToggle.length) {
            $searchToggle.off('click');
            setSearchState($searchForm.hasClass('open'));

            $searchToggle.on('click', function() {
                setSearchState(!$searchForm.hasClass('open'));
            });

            $(document).on('keydown', function(event) {
                if (event.key === 'Escape' && $searchForm.hasClass('open')) {
                    setSearchState(false);
                    $searchToggle.trigger('focus');
                }
            });

            $(document).on('click', function(event) {
                if ($searchForm.hasClass('open') && !$(event.target).closest('#search-container').length) {
                    setSearchState(false);
                }
            });
        }
    });
})(jQuery);
