<?php

use Nerbiz\WordClass\Init;

if (! isset($currentMediaUrl, $currentMediaFilename, $inputName, $inputValue)) {
    return;
}

// translators: Notice/message in media input field
$noMediaSelectedText = __('No media selected', 'wordclass');

$imagesDirectory = Init::getVendorUri('nerbiz/wordclass/includes/images/');
$transparentPixelSrc = $imagesDirectory . 'transparent-pixel.png';
// Public domain icon downloaded from
// https://publicdomainvectors.org/en/free-clipart/Paper-sheet-vector-image/13299.html
$fileIconSrc = $imagesDirectory . 'file-icon.svg';

// Set a fallback image, if the current URL is empty
if (trim($currentMediaUrl) === '') {
    if ($currentMediaFilename !== '') {
        // If a filename is set, use the general file icon
        $currentMediaUrl = $fileIconSrc;
    } else {
        $currentMediaUrl = $transparentPixelSrc;
    }
}
?>

<div class="nw-media-upload-input">
    <div class="media-preview-wrapper">
        <img class="media-preview"
             src="<?php echo $currentMediaUrl; ?>"
             width="100"
             height="100"
             style="border: 1px #ccc solid;"
             data-transparent-pixel-src="<?php echo $transparentPixelSrc; ?>"
             data-file-icon-src="<?php echo $fileIconSrc; ?>">
    </div>

    <input type="button"
           class="button upload-media-button"
           <?php // translators: Button text for opening the media library ?>
           value="<?php echo __('Select media', 'wordclass'); ?>"><br>

    <span class="chosen-media-filename" data-fallback-text="<?php echo $noMediaSelectedText; ?>">
        <?php echo $currentMediaFilename ?: $noMediaSelectedText; ?>
    </span><br>

    <a href="#" class="clear-media-button">
        <?php // translators: Button text for clearing a media input field ?>
        <?php echo __('Clear', 'wordclass'); ?>
    </a>

    <input type="hidden"
           name="<?php echo $inputName; ?>"
           value="<?php echo $inputValue; ?>">
</div>
