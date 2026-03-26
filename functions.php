<?php

function centerrow_display_featured_exhibit() {
    $html = '';
    $featuredExhibit = exhibit_builder_random_featured_exhibit();
    if ($featuredExhibit) {
        $html .= get_view()->partial('exhibit-builder/exhibits/single.php', array('exhibit' => $featuredExhibit));
    }
    $html = apply_filters('exhibit_builder_display_random_featured_exhibit', $html);
    return $html;
}

function centerrow_get_square_thumbnail_url($file, $view) {
    if ($file->hasThumbnail()) {
        $squareThumbnail = file_display_url($file, 'square_thumbnail');
    } else {
        $mimeType = $file->mime_type;
        $fileType = (strpos($mimeType, 'image')) ? 'image' : 'video';
        $squareThumbnail = $view->baseUrl() . '/application/views/scripts/images/fallback-' . $fileType . '.png';
    }
    return $squareThumbnail;
}

function centerrow_public_nav_main() {
    $view = get_view();
    $nav = new Omeka_Navigation;
    $nav->loadAsOption(Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_OPTION_NAME);
    $nav->addPagesFromFilter(Omeka_Navigation::PUBLIC_NAVIGATION_MAIN_FILTER_NAME);
    $html = $view->navigation()->menu($nav)->setPartial('common/accessible-megamenu.php')->render();
    $view->navigation()->menu($nav)->setPartial(null);
    return $html;
}

/**
 * Queues the necessary CSS and JS assets for lightGallery, if enabled in the theme options.
 * 
 * @return void
 */
function maobjects_queue_lightgallery_assets() {
    queue_css_file('lightgallery');
    queue_css_file('lightgallery-bundle.min', 'all', false, 'javascripts/vendor/lightgallery/css');
    queue_js_file(array(
        'vendor/lightgallery/lightgallery.min',
        'vendor/lightgallery/plugins/thumbnail/lg-thumbnail.min',
        'vendor/lightgallery/plugins/video/lg-video.min',
        'vendor/lightgallery/plugins/rotate/lg-rotate.min',
        'vendor/lightgallery/plugins/hash/lg-hash.min',
        'vendor/lightgallery/plugins/zoom/lg-zoom.min',
    ));
    queue_js_file('lg-itemfiles-config', 'js');
}

/**
 * Parse the browse item metadata theme option into renderable field specs.
 *
 * Supported line formats:
 * - Dublin Core:Creator
 * - 'Dublin Core':'Creator'
 * - Dublin Core:Description|view=list|snippet=350
 *
 * Empty lines and comment lines starting with "#" are ignored.
 *
 * @param string|null $config Raw config string. When null, reads the theme option.
 * @return array
 */
function maobjects_parse_browse_item_metadata_config($config = null) {
    if ($config === null) {
        $config = get_theme_option('browse_item_metadata');
    }

    $config = trim((string) $config);
    if ($config === '') {
        $config = "Dublin Core:Creator\nDublin Core:Date\nDublin Core:Description|view=list|snippet=350";
    }

    $config = str_replace(array('\r\n', '\n', '\r'), "\n", $config);
    $lines = preg_split("/\r\n|\n|\r/", $config);
    $specs = array();

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0 || strpos($line, ';') === 0) {
            continue;
        }

        $segments = preg_split('/\s*\|\s*/', $line);
        $fieldSegment = array_shift($segments);

        if (!$fieldSegment) {
            continue;
        }

        $fieldParts = preg_split('/\s*[:,]\s*/', $fieldSegment, 2);
        if (count($fieldParts) !== 2) {
            continue;
        }

        $elementSet = trim($fieldParts[0], " \t\n\r\0\x0B'\"");
        $elementName = trim($fieldParts[1], " \t\n\r\0\x0B'\"");

        if ($elementSet === '' || $elementName === '') {
            continue;
        }

        $fieldClass = 'item-' . trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $elementName)), '-');
        $spec = array(
            'element_set' => $elementSet,
            'element_name' => $elementName,
            'class' => $fieldClass !== 'item-' ? $fieldClass : 'item-meta-field',
            'snippet' => null,
            'view' => 'both',
        );

        foreach ($segments as $segment) {
            $segment = trim($segment);
            if ($segment === '') {
                continue;
            }

            if (strpos($segment, '=') === false) {
                $flag = strtolower(trim($segment, "'\""));
                if (in_array($flag, array('list', 'grid', 'both'), true)) {
                    $spec['view'] = $flag;
                }
                continue;
            }

            list($key, $value) = array_map('trim', explode('=', $segment, 2));
            $key = strtolower($key);
            $value = trim($value, "'\"");

            if ($key === 'view' && in_array($value, array('list', 'grid', 'both'), true)) {
                $spec['view'] = $value;
            } elseif ($key === 'snippet' && ctype_digit($value) && (int) $value > 0) {
                $spec['snippet'] = (int) $value;
            }
        }

        $specs[] = $spec;
    }

    return $specs;
}

/**
 * Render configured browse item metadata fields for list or grid browse cards.
 *
 * @param Item $item
 * @param string $viewMode Either "list" or "grid".
 * @return string
 */
function maobjects_render_browse_item_metadata($item, $viewMode = 'grid') {
    $html = '';
    $viewMode = $viewMode === 'list' ? 'list' : 'grid';

    foreach (maobjects_parse_browse_item_metadata_config() as $field) {
        if ($field['view'] !== 'both' && $field['view'] !== $viewMode) {
            continue;
        }

        $options = array();
        if (!empty($field['snippet'])) {
            $options['snippet'] = $field['snippet'];
        }

        $value = metadata($item, array($field['element_set'], $field['element_name']), $options);
        if (!$value) {
            continue;
        }

        $tag = !empty($field['snippet']) ? 'div' : 'span';
        $html .= sprintf(
            '<%1$s class="item-meta-field %2$s">%3$s</%1$s>',
            $tag,
            html_escape($field['class']),
            $value
        );
    }

    return $html;
}

/**
 * Render Omeka browse sort links as select dropdowns.
 *
 * The helper reuses browse_sort_links() as its source for sort fields, then
 * builds a second dropdown for sort direction while preserving the current
 * browse context. The original link list is kept in a <noscript> fallback.
 * 
 * Disclaimer: This functions was mainly generated by AI (2026-03-23)
 *
 * @param array $sortLinks Label-to-field mapping passed to browse_sort_links().
 * @param string $selectId HTML id attribute used for the generated field select.
 * @param string|null $ariaLabel Accessible label for the generated field select.
 * @return string
 */
function maobjects_browse_sort_select(array $sortLinks, $selectId = 'browse-sort-select', $ariaLabel = null) {
    $sortLinksHtml = browse_sort_links($sortLinks);
    $options = array();
    $parseSortQuery = function ($href) {
        $decodedHref = html_entity_decode($href, ENT_QUOTES, 'UTF-8');
        $query = array();
        $queryString = parse_url($decodedHref, PHP_URL_QUERY);

        if ($queryString !== null) {
            parse_str($queryString, $query);
        }

        return $query;
    };
    $replaceSortQuery = function ($href, array $replacements) use ($parseSortQuery) {
        $decodedHref = html_entity_decode($href, ENT_QUOTES, 'UTF-8');
        $parts = parse_url($decodedHref);
        $query = $parseSortQuery($decodedHref);

        foreach ($replacements as $key => $value) {
            if ($value === null || $value === '') {
                unset($query[$key]);
            } else {
                $query[$key] = $value;
            }
        }

        $rebuiltHref = '';

        if (isset($parts['scheme'])) {
            $rebuiltHref .= $parts['scheme'] . '://';

            if (isset($parts['user'])) {
                $rebuiltHref .= $parts['user'];

                if (isset($parts['pass'])) {
                    $rebuiltHref .= ':' . $parts['pass'];
                }

                $rebuiltHref .= '@';
            }

            if (isset($parts['host'])) {
                $rebuiltHref .= $parts['host'];
            }

            if (isset($parts['port'])) {
                $rebuiltHref .= ':' . $parts['port'];
            }
        }

        if (isset($parts['path'])) {
            $rebuiltHref .= $parts['path'];
        }

        $rebuiltQuery = http_build_query($query);
        if ($rebuiltQuery !== '') {
            $rebuiltHref .= '?' . $rebuiltQuery;
        }

        if (isset($parts['fragment'])) {
            $rebuiltHref .= '#' . $parts['fragment'];
        }

        return $rebuiltHref;
    };

    // Try to parse the HTML with DOMDocument if available:
    if (class_exists('DOMDocument')) {
        $dom = new DOMDocument();
        $previousUseInternalErrors = libxml_use_internal_errors(true);
        $loaded = $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $sortLinksHtml . '</div>');
        libxml_clear_errors();
        libxml_use_internal_errors($previousUseInternalErrors);

        if ($loaded) {
            foreach ($dom->getElementsByTagName('a') as $link) {
                $label = trim($link->textContent);
                $href = trim($link->getAttribute('href'));
                $parentClass = '';

                if ($link->parentNode instanceof DOMElement) {
                    $parentClass = $link->parentNode->getAttribute('class');
                }

                if ($label === '' || $href === '') {
                    continue;
                }

                $isCurrent = strpos(' ' . $parentClass . ' ', ' sorting ') !== false
                    || strpos(' ' . $link->getAttribute('class') . ' ', ' sorting ') !== false;
                $sortQuery = $parseSortQuery($href);

                $options[] = array(
                    'href' => $href,
                    'label' => $label,
                    'selected' => $isCurrent,
                    'sort_field' => isset($sortQuery['sort_field']) ? $sortQuery['sort_field'] : null,
                );
            }
        }
    }

    // If DOMDocument parsing failed or is unavailable, try regex parsing:
    if (!$options && preg_match_all('/<li\b([^>]*)>\s*<a\b([^>]*)href=(["\'])(.*?)\3([^>]*)>(.*?)<\/a>\s*<\/li>/is', $sortLinksHtml, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $label = trim(html_entity_decode(strip_tags($match[6]), ENT_QUOTES, 'UTF-8'));
            $href = trim(html_entity_decode($match[4], ENT_QUOTES, 'UTF-8'));
            $isCurrent = strpos(' ' . $match[1] . ' ' . $match[2] . ' ' . $match[5] . ' ', ' sorting ') !== false;
            $sortQuery = $parseSortQuery($href);

            if ($label === '' || $href === '') {
                continue;
            }

            $options[] = array(
                'href' => $href,
                'label' => $label,
                'selected' => $isCurrent,
                'sort_field' => isset($sortQuery['sort_field']) ? $sortQuery['sort_field'] : null,
            );
        }
    }

    // If regex parsing with list items failed, try a more lenient regex parsing without list items:
    if (!$options && preg_match_all('/<a\b([^>]*)href=(["\'])(.*?)\2([^>]*)>(.*?)<\/a>/is', $sortLinksHtml, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $label = trim(html_entity_decode(strip_tags($match[5]), ENT_QUOTES, 'UTF-8'));
            $href = trim(html_entity_decode($match[3], ENT_QUOTES, 'UTF-8'));
            $isCurrent = strpos(' ' . $match[1] . ' ' . $match[4] . ' ', ' sorting ') !== false;
            $sortQuery = $parseSortQuery($href);

            if ($label === '' || $href === '') {
                continue;
            }

            $options[] = array(
                'href' => $href,
                'label' => $label,
                'selected' => $isCurrent,
                'sort_field' => isset($sortQuery['sort_field']) ? $sortQuery['sort_field'] : null,
            );
        }
    }

    // If no options were parsed, return the original HTML:
    if (!$options) {
        return $sortLinksHtml;
    }

    // Determine the selected option index (default to 0 if none selected):
    $selectedIndex = null;
    foreach ($options as $index => $option) {
        if ($option['selected']) {
            $selectedIndex = $index;
            break;
        }
    }

    // If no option is marked as selected, default to the first option:
    if ($selectedIndex === null) {
        $selectedIndex = 0;
    }

    $currentSortField = isset($_GET['sort_field']) && $_GET['sort_field'] !== ''
        ? $_GET['sort_field']
        : $options[$selectedIndex]['sort_field'];
    $currentSortDir = isset($_GET['sort_dir']) && $_GET['sort_dir'] === 'd' ? 'd' : 'a';
    $currentFieldHref = $options[$selectedIndex]['href'];

    foreach ($options as $index => $option) {
        if (!empty($option['sort_field'])) {
            $options[$index]['href'] = $replaceSortQuery($option['href'], array(
                'sort_field' => $option['sort_field'],
                'sort_dir' => $currentSortDir,
            ));
        }

        if ($option['sort_field'] === $currentSortField) {
            $currentFieldHref = $options[$index]['href'];
        }
    }

    $orderOptions = array(
        array(
            'href' => $replaceSortQuery($currentFieldHref, array(
                'sort_field' => $currentSortField,
                'sort_dir' => 'a',
            )),
            'label' => __('Ascending'),
            'selected' => $currentSortDir !== 'd',
        ),
        array(
            'href' => $replaceSortQuery($currentFieldHref, array(
                'sort_field' => $currentSortField,
                'sort_dir' => 'd',
            )),
            'label' => __('Descending'),
            'selected' => $currentSortDir === 'd',
        ),
    );

    // Build the select HTML:
    if ($ariaLabel === null) {
        $ariaLabel = __('Sort records');
    }

    $html = '<label class="sort-label" for="' . html_escape($selectId) . '">' . __('Sort by: ') . '</label>';
    $html .= '<select id="' . html_escape($selectId) . '" class="sort-select sort-field-select" aria-label="' . html_escape($ariaLabel) . '" onchange="if (this.value) { window.location.href = this.value; }">';

    foreach ($options as $index => $option) {
        $html .= '<option value="' . html_escape($option['href']) . '"';
        if ($index === $selectedIndex) {
            $html .= ' selected="selected"';
        }
        $html .= '>' . html_escape($option['label']) . '</option>';
    }

    $html .= '</select>';
    $html .= '<select id="' . html_escape($selectId . '-direction') . '" class="sort-select sort-order-select" aria-label="' . html_escape(__('Sort direction')) . '" onchange="if (this.value) { window.location.href = this.value; }">';

    foreach ($orderOptions as $option) {
        $html .= '<option value="' . html_escape($option['href']) . '"';
        if ($option['selected']) {
            $html .= ' selected="selected"';
        }
        $html .= '>' . html_escape($option['label']) . '</option>';
    }

    $html .= '</select>';
    $html .= '<noscript>' . $sortLinksHtml . '</noscript>';

    return $html;
}

/**
 * Render the Facets plugin block only when it contains actual facet fields.
 *
 * The Facets plugin can emit its outer container even when no configured facet
 * has any values in the current browse context. Capture the plugin output first
 * so the theme can suppress that empty shell.
 *
 * @param array $args Hook arguments passed to the Facets plugin.
 * @return string
 */
function maobjects_public_facets_if_available(array $args = array()) {
    if (!function_exists('get_specific_plugin_hook_output')) {
        return '';
    }

    $html = get_specific_plugin_hook_output('Facets', 'public_facets', $args);

    if (!is_string($html) || trim($html) === '') {
        return '';
    }

    if (strpos($html, 'id="facets-field-') === false && strpos($html, "id='facets-field-") === false) {
        return '';
    }

    return $html;
}

?>
