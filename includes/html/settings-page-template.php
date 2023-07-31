<?php

use Nerbiz\WordClass\SettingsPage;

if (! isset($settingsPage) || ! $settingsPage instanceof SettingsPage) {
    return;
}

$currentTab = $_GET['tab'] ?? null;
?>

<div class="wrap">
    <h1>
        <?php echo $settingsPage->getPageTitle(); ?>
    </h1>

    <?php
    // Prevent double errors
    if ($settingsPage->getParentSlug() === null) {
        settings_errors();
    }
    ?>

    <nav class="nav-tab-wrapper">
        <?php // Output tab buttons, first is active at page load ?>
        <?php $first = true; ?>
        <?php foreach ($settingsPage->getSections() as $section): ?>
            <?php
            // See if the tab is active
            $active = ($currentTab !== null)
                ? ($currentTab === $section->getId())
                : $first;
            $first = false;
            ?>

            <a href="#"
               class="nav-tab <?php echo $active ? 'nav-tab-active' : ''; ?>"
               data-tab-id="<?php echo $section->getId(); ?>"
            >
                <?php echo $section->getTitle(); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <form method="POST">
        <?php wp_nonce_field(-1, $settingsPage->getNonceName()); ?>

        <?php // Output sections, first is visible at page load ?>
        <?php $first = true; ?>
        <?php foreach ($settingsPage->getSections() as $section): ?>
            <?php
            // See if the tab is active
            $active = ($currentTab !== null)
                ? ($currentTab === $section->getId())
                : $first;
            $first = false;
            ?>

            <div class="wordclass-settings-section"
                 id="<?php echo $section->getId(); ?>"
                 style="<?php echo $active ? '' : 'display: none;'; ?>"
            >
                <table class="form-table">
                    <tbody>
                        <?php // Output settings fields ?>
                        <?php foreach ($section->getFields() as $field): ?>
                            <?php if ($field->isFullWidth()): ?>
                                <tr>
                                    <td colspan="2" style="padding-left: 0;">
                                        <?php if (($label = $field->getLabel()) !== ''): ?>
                                            <h3 style="margin: 0;">
                                                <?php echo $label; ?>
                                            </h3>
                                        <?php endif; ?>

                                        <?php echo $field->render(); ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th scope="row"><?php echo $field->getLabel(); ?></th>
                                    <td><?php echo $field->render(); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <?php submit_button(
            __('Save settings', 'wordclass'),
            'primary',
            $settingsPage->getSubmitButtonName()
        ); ?>
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
