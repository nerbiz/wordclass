<?php
if (! isset($currentMediaUrl, $currentMediaFilename, $prefixedName, $inputValue)) {
    return;
}

$noMediaSelectedText = __('No media selected', 'wordclass');
?>

<div class="media-upload-input">
    <div class="media-preview-wrapper">
        <img class="media-preview"
             src="<?php echo $currentMediaUrl; ?>"
             width="100"
             height="100"
             style="border: 1px #ccc solid;">
    </div>

    <input type="button"
           class="button upload-media-button"
           value="<?php echo __('Select media', 'wordclass'); ?>"><br>

    <span class="chosen-media-filename" data-fallback-text="<?php echo $noMediaSelectedText; ?>">
        <?php echo $currentMediaFilename ?: $noMediaSelectedText; ?>
    </span><br>

    <a href="#" class="clear-media-button">
        <?php echo __('Clear', 'wordclass'); ?>
    </a>

    <input type="hidden"
           name="<?php echo $prefixedName; ?>"
           value="<?php echo $inputValue; ?>">
</div>
