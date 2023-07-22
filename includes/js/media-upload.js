/**
 * @param {Element} element
 * @constructor
 */
function NwMediaInput(element)
{
    var self = this;

    /**
     * The main element containing media preview and input
     * @type {Element}
     */
    self.element = element;

    /**
     * The button that triggers the media selector
     * @type {Element}
     */
    self.uploadButton = self.element.querySelectorAll('.upload-media-button')[0];

    /**
     * The button that clears the current value
     * @type {Element}
     */
    self.clearButton = self.element.querySelectorAll('.clear-media-button')[0];

    /**
     * The element that shows the selected attachment
     * @type {Element}
     */
    self.mediaPreview = self.element.querySelectorAll('.media-preview')[0];

    /**
     * The input field containing the value (selected attachment ID)
     * @type {Element}
     */
    self.inputField = self.element.querySelectorAll('input[type="hidden"]')[0];

    /**
     * The element that contains the filename of the currently selected media
     * @type {Element}
     */
    self.chosenMediaFilename = self.element.querySelectorAll('.chosen-media-filename')[0];

    /**
     * A 1x1 transparent pixel
     * @type {string}
     */
    self.transparentPixelSrc = self.mediaPreview.dataset.transparentPixelSrc;

    /**
     * An empty file icon
     * @type {string}
     */
    self.fileIconSrc = self.mediaPreview.dataset.fileIconSrc;

    /**
     * The media frame
     * @type {object|null}
     */
    self.fileFrame = null;

    /**
     * Enable the media upload/selector button
     * @return {void}
     */
    self.enableUploadButton = function () {
        self.uploadButton.addEventListener('click', function (event) {
            event.preventDefault();

            // Get the current attachment ID
            var currentAttachmentId = self.inputField.value;

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
                title: 'Select media file to upload',
                button: {
                    text: 'Use this file',
                },
                // Don't allow multiple files
                multiple: false,
            });

            // When an file is selected, run a callback.
            self.fileFrame.on('select', function () {
                var attachment = self.fileFrame.state().get('selection').first().toJSON();

                // Set the attachment ID in the input field
                self.inputField.value = attachment.id;
                // Show the media preview and filename
                var imageSrc = (attachment.type === 'image')
                    ? attachment.sizes.thumbnail.url
                    : self.fileIconSrc;
                self.mediaPreview.setAttribute('src', imageSrc);
                self.chosenMediaFilename.innerHTML = attachment.filename;

                // Restore the main post ID
                wp.media.model.settings.post.id = window.NwMediaInputSettings.oldAttachmentId;
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
        self.clearButton.addEventListener('click', function (event) {
            event.preventDefault();

            self.mediaPreview.setAttribute('src', self.transparentPixelSrc);
            self.inputField.value = '';
            self.chosenMediaFilename.innerHTML = self.chosenMediaFilename.dataset.fallbackText;
        });
    };
}

document.addEventListener('DOMContentLoaded', event => {
    // Used by the for-loops below
    var i;

    window.NwMediaInputSettings = {
        // Store the old id
        oldAttachmentId: (wp.media)
            ? wp.media.model.settings.post.id
            : null
    };

    var mediaInputElements = document.querySelectorAll('.nw-media-upload-input');
    for (i = 0; i < mediaInputElements.length; i++) {
        var mediaUploadInput = new NwMediaInput(mediaInputElements[i]);
        mediaUploadInput.enableUploadButton();
        mediaUploadInput.enableClearButton();
    }

    // Restore the main ID when the add media button is pressed
    var addButtons = document.querySelectorAll('.add_media');
    for (i = 0; i < addButtons.length; i++) {
        addButtons[i].addEventListener('click', function () {
            wp.media.model.settings.post.id = window.NwMediaInputSettings.oldAttachmentId;
        });
    }
});
