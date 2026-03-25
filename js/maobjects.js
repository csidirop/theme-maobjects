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

        // Tag filtering on items browse tags page:
        var $tagFilterInput = $('#tags-page-search-query');
        var $tagFilterStatus = $('#tags-page-search-status');
        var $tagItems = $('.tags .hTagcloud li');

        if ($tagFilterInput.length && $tagItems.length) {
            var totalTagCount = $tagItems.length;

            function normalizeText(value) {
                return $.trim(String(value || '').toLowerCase());
            }

            function updateTagFilter() {
                var query = normalizeText($tagFilterInput.val());
                var visibleCount = 0;

                $tagItems.each(function() {
                    var $tagItem = $(this);
                    var tagText = normalizeText($tagItem.text());
                    var matches = query === '' || tagText.indexOf(query) !== -1;

                    $tagItem.prop('hidden', !matches);

                    if (matches) {
                        visibleCount += 1;
                    }
                });

                if (query === '') {
                    $tagFilterStatus.text('Showing all ' + totalTagCount + ' tags.');
                } else if (visibleCount === 0) {
                    $tagFilterStatus.text('No tags match "' + query + '".');
                } else {
                    $tagFilterStatus.text('Showing ' + visibleCount + ' of ' + totalTagCount + ' tags for "' + query + '".');
                }
            }

            $tagFilterInput.on('input', updateTagFilter);
            $tagFilterInput.on('keydown', function(event) {
                if (event.key === 'Escape') {
                    $tagFilterInput.val('');
                    updateTagFilter();
                }
            });

            updateTagFilter();
        }
    });
})(jQuery);
