<?php
if (! isset($settingsPage, $pageSlug)) {
    return;
}

global $wp_settings_sections, $wp_settings_fields;
if (! isset($wp_settings_sections[$pageSlug])) {
    return;
}

$settingsGroup = $settingsPage->init->getPrefix() . '-' . $settingsPage->settingsGroup;
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
            <a href="#<?php echo $section['id']; ?>" class="nav-tab <?php echo $first ? 'nav-tab-active' : ''; ?>">
                <?php echo $section['title']; ?>
            </a>

            <?php $first = false; ?>
        <?php endforeach; ?>
    </h2>

    <form action="options.php" method="POST">
        <?php settings_fields($settingsGroup); ?>

        <?php // Output sections, first is visible at page load ?>
        <?php $first = true; ?>
        <?php foreach ((array)$wp_settings_sections[$pageSlug] as $section): ?>
            <div class="wordclass-settings-section"
                 id="<?php echo $section['id']; ?>"
                 style="<?php echo $first ? '' : 'display: none;'; ?>"
                 data-test="<?php echo $key; ?>"
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
    // The tab switching mechanism
    jQuery(document).ready(function ($) {
        var $tabButtons = $('.nav-tab');
        var $sections = $('.wordclass-settings-section');

        $tabButtons.on('click', function (event) {
            event.preventDefault();
            var $clickedButton = $(event.target);

            // Make the clicked button visually active
            $tabButtons.removeClass('nav-tab-active')
                .filter($clickedButton)
                .addClass('nav-tab-active');

            // Show the corresponding section
            $sections.css('display', 'none')
                .filter($clickedButton.attr('href'))
                .css('display', 'block');
        });
    });
</script>
