<?php
if (! isset($settingsPage, $pageSlug)) {
    return;
}

global $wp_settings_sections, $wp_settings_fields;
if (! isset($wp_settings_sections[$pageSlug])) {
    return;
}

$currentTab = $_GET['tab'] ?? null;
?>

<div class="wrap">
    <h1>
        <?php echo $settingsPage->pageTitle; ?>
    </h1>

    <?php settings_errors(); ?>

    <h2 class="nav-tab-wrapper">
        <?php // Output tab buttons, first is active at page load ?>
        <?php $first = true; ?>
        <?php foreach ($wp_settings_sections[$pageSlug] as $section): ?>
            <?php
            // See if the tab is active
            $active = ($currentTab !== null)
                ? ($currentTab === $section['id'])
                : $first;
            ?>

            <a href="#"
               class="nav-tab <?php echo $active ? 'nav-tab-active' : ''; ?>"
               data-tab-id="<?php echo $section['id']; ?>"
            >
                <?php echo $section['title']; ?>
            </a>

            <?php $first = false; ?>
        <?php endforeach; ?>
    </h2>

    <form action="options.php" method="POST">
        <?php settings_fields($settingsPage->getSettingsGroup()); ?>

        <?php // Output sections, first is visible at page load ?>
        <?php $first = true; ?>
        <?php foreach ((array)$wp_settings_sections[$pageSlug] as $section): ?>
            <?php
            // See if the tab is active
            $active = ($currentTab !== null)
                ? ($currentTab === $section['id'])
                : $first;
            ?>

            <div class="wordclass-settings-section"
                 id="<?php echo $section['id']; ?>"
                 style="<?php echo $active ? '' : 'display: none;'; ?>"
            >
                <?php // Section title ?>
                <?php if (isset($section['title'])): ?>
                    <h2>
                        <?php echo $section['title']; ?>
                    </h2>
                <?php endif; ?>

                <?php // Subtitle callback ?>
                <?php if (isset($section['callback'])) {
                    call_user_func($section['callback'], $section);
                } ?>

                <?php  if (! isset($wp_settings_fields[$pageSlug][$section['id']])) {
                    continue;
                } ?>

                <table class="form-table">
                    <?php // Output settings fields ?>
                    <?php do_settings_fields($pageSlug, $section['id']); ?>
                </table>
            </div>

            <?php $first = false; ?>
        <?php endforeach; ?>

        <?php submit_button(__('Save settings', 'wordclass')); ?>
    </form>
</div>

<script>
    function setCurrentTab(value) {
        var parameters = {};

        // Convert the query string to a parameters object
        var matches;
        var regEx = /[?&]?([^=&]+)(?:=([^&]*)|)/g;
        while (matches = regEx.exec(window.location.search)) {
            parameters[matches[1]] = matches[2];
        }

        // Set or overwrite the tab parameter value
        parameters.tab = value;

        // Convert the parameters object back to a query string
        var queryStringArray = [];
        for (var key in parameters) {
            queryStringArray.push(key + '=' + parameters[key]);
        }

        // Update the page URL (history) and referrer input field
        var newQueryString = window.location.pathname + '?' + queryStringArray.join('&');
        window.history.replaceState('', '', newQueryString);
        $('[name="_wp_http_referer"]').val(newQueryString);
    }

    // The tab switching mechanism
    jQuery(document).ready(function ($) {
        var $tabButtons = $('.nav-tab');
        var $sections = $('.wordclass-settings-section');

        $tabButtons.on('click', function (event) {
            event.preventDefault();
            var $clickedButton = $(event.target);
            var tabId = $clickedButton.data('tabId');

            // Update the tab ID in the URL
            setCurrentTab(tabId);

            // Make the clicked button visually active
            $tabButtons.removeClass('nav-tab-active')
                .filter($clickedButton)
                .addClass('nav-tab-active');

            // Show the corresponding section
            $sections.css('display', 'none')
                .filter('#' + tabId)
                .css('display', 'block');
        });
    });
</script>
