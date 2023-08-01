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
               data-tab-id="<?php echo $section->getId(); ?>">
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

            <div class="wordclass-settings-section <?php echo $active ? '' : 'hidden'; ?>"
                 id="<?php echo $section->getId(); ?>">
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
    const tabButtons = document.querySelectorAll('.nav-tab');
    const tabContents = document.querySelectorAll('.wordclass-settings-section');

    // The URL query string as key-value pairs
    const queryParameters = {};

    // Matches a query string like "?page=website-settings&tab=contact"
    const queryRegEx = /[?&](?<key>[^=&]+)=?(?<value>[^&]+|)/g;

    // Store the query parameters
    for (const match of window.location.search.matchAll(queryRegEx)) {
        const {key, value} = match.groups;
        queryParameters[key] = value;
    }

    function nwSwitchToTab(tabId) {
        // Construct a new query string with a different tab value
        queryParameters.tab = tabId;
        const queryString = Object.entries(queryParameters)
            .map(entry => entry.join('='))
            .join('&');

        // Update the URL and referrer input field
        const newUrl = window.location.pathname + '?' + queryString;
        window.history.replaceState({}, '', newUrl);
        document.querySelector('[name="_wp_http_referer"]').value = newUrl;
    }

    document.addEventListener('click', event => {
        const tabButton = event.target.closest('.nav-tab');
        if (tabButton !== null) {
            const tabId = tabButton.dataset.tabId;
            nwSwitchToTab(tabId);

            // Set the clicked tab button as active
            for (const button of tabButtons) {
                (button === tabButton)
                    ? button.classList.add('nav-tab-active')
                    : button.classList.remove('nav-tab-active');
            }

            // Show the corresponding tab content
            for (const content of tabContents) {
                (content.id === tabId)
                    ? content.classList.remove('hidden')
                    : content.classList.add('hidden');
            }
        }
    });
</script>
