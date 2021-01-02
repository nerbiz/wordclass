/**
 * @param {jQuery|HTMLElement} $element
 * @constructor
 */
function MediaUploadInput($element)
{
    var self = this;

    /**
     * The main element containing image preview and input
     * @type {jQuery|HTMLElement}
     */
    self.$element = $($element);

    /**
     * The button that triggers the media selector
     * @type {jQuery|HTMLElement}
     */
    self.$uploadButton = self.$element.find('.upload-media-button');

    /**
     * The button that clears the current value
     * @type {jQuery|HTMLElement}
     */
    self.$clearButton = self.$element.find('.clear-media-button');

    /**
     * The image element that shows the selected attachment
     * @type {jQuery|HTMLElement}
     */
    self.$imagePreview = self.$element.find('.media-preview');

    /**
     * The input field containing the value (selected attachment ID)
     * @type {jQuery|HTMLElement}
     */
    self.$inputField = self.$element.find('input[type="hidden"]');

    /**
     * The element that contains the filename of the currently selected media
     * @type {jQuery|HTMLElement}
     */
    self.$chosenMediaFilename = self.$element.find('.chosen-media-filename');

    /**
     * A 1x1 transparent white pixel, used as image placeholder
     * @type {String}
     */
    self.transparentPixelBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

    /**
     * The media frame
     * @type {Object|null}
     */
    self.fileFrame = null;

    /**
     * Set a fallback 'src' for the preview image, if needed
     * @return {void}
     */
    self.setFallbackImagePreview = function () {
        if (! self.$imagePreview.attr('src')) {
            self.$imagePreview.attr('src', self.transparentPixelBase64);
        }
    };

    /**
     * Enable the media upload/selector button
     * @return {void}
     */
    self.enableUploadButton = function () {
        self.$uploadButton.on('click', function (event) {
            event.preventDefault();

            // Get the current attachment ID
            var currentAttachmentId = self.$inputField.val();

            // If the media frame already exists, reopen it.
            if (self.fileFrame) {
                // Set the post ID to what we want
                self.fileFrame.uploader.uploader.param('post_id', currentAttachmentId);
                // Open frame
                self.fileFrame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = currentAttachmentId;
            }

            // Create the media frame
            self.fileFrame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                // Don't allow multiple files
                multiple: false
            });

            // When an image is selected, run a callback.
            self.fileFrame.on('select', function () {
                // We set multiple to false so only get one image from the uploader
                var attachment = self.fileFrame.state().get('selection').first().toJSON();

                // Set the attachment ID in the input field
                self.$inputField.val(attachment.id);
                // Show the image preview and filename
                self.$imagePreview.attr('src', attachment.url);
                self.$chosenMediaFilename.text(attachment.filename);

                // Restore the main post ID
                wp.media.model.settings.post.id = window.mediaUploadInputSettings.oldAttachmentId;
            });

            // Finally, open the modal
            self.fileFrame.open();
        });
    };

    /**
     * Enable the button that clears the current value
     * @return {void}
     */
    self.enableClearButton = function () {
        self.$clearButton.on('click', function (event) {
            event.preventDefault();

            self.$imagePreview.attr('src', self.transparentPixelBase64);
            self.$inputField.val('');
            self.$chosenMediaFilename.text(self.$chosenMediaFilename.data('fallbackText'));
        });
    };
}

jQuery(document).ready(function ($) {
    window.mediaUploadInputSettings = {
        // Store the old id
        oldAttachmentId: (wp.media)
            ? wp.media.model.settings.post.id
            : null
    };

    $('.media-upload-input').each(function (index, element) {
        var mediaUploadInput = new MediaUploadInput(element);
        mediaUploadInput.setFallbackImagePreview();
        mediaUploadInput.enableUploadButton();
        mediaUploadInput.enableClearButton();
    });

    // Restore the main ID when the add media button is pressed
    $('.add_media').on('click', function () {
        wp.media.model.settings.post.id = window.mediaUploadInputSettings.oldAttachmentId;
    });
});
