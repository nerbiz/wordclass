/**
 *
 * @constructor
 */
class NwMediaInput
{
    /**
     * The main element containing media preview and input
     * @var {Element}
     */
    element;

    /**
     * The button that triggers the media selector
     * @type {Element}
     */
    uploadButton;

    /**
     * The button that clears the current value
     * @type {Element}
     */
    clearButton;

    /**
     * The element that shows the selected attachment
     * @type {Element}
     */
    mediaPreview;

    /**
     * The input field containing the value (selected attachment ID)
     * @type {Element}
     */
    inputField;

    /**
     * The element that contains the filename of the currently selected media
     * @type {Element}
     */
    chosenMediaFilename;

    /**
     * A 1x1 transparent pixel
     * @type {string}
     */
    transparentPixelSrc;

    /**
     * An empty file icon
     * @type {string}
     */
    fileIconSrc;

    /**
     * The media frame
     * @type {object|null}
     */
    fileFrame = null;

    /**
     * @param {Element} element
     */
    constructor(element) {
        this.element = element;
        this.uploadButton = this.element.querySelector('.upload-media-button');
        this.clearButton = this.element.querySelector('.clear-media-button');
        this.mediaPreview = this.element.querySelector('.media-preview');
        this.inputField = this.element.querySelector('input[type="hidden"]');
        this.chosenMediaFilename = this.element.querySelector('.chosen-media-filename');
        this.transparentPixelSrc = this.mediaPreview.dataset.transparentPixelSrc;
        this.fileIconSrc = this.mediaPreview.dataset.fileIconSrc;
    }

    /**
     * Enable the media upload/selector button
     * @return {void}
     */
    enableUploadButton() {
        this.uploadButton.addEventListener('click', event => {
            event.preventDefault();

            // Get the current attachment ID
            const currentAttachmentId = this.inputField.value;

            // If the media frame already exists, reopen it.
            if (this.fileFrame) {
                // Set the post ID to what we want
                this.fileFrame.uploader.uploader.param('post_id', currentAttachmentId);
                // Open frame
                this.fileFrame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = currentAttachmentId;
            }

            // Create the media frame
            this.fileFrame = wp.media.frames.file_frame = wp.media({
                title: 'Select media file to upload',
                button: {
                    text: 'Use this file',
                },
                // Don't allow multiple files
                multiple: false,
            });

            // When an file is selected, run a callback.
            this.fileFrame.on('select', () => {
                const attachment = this.fileFrame.state().get('selection').first().toJSON();

                // Set the attachment ID in the input field
                this.inputField.value = attachment.id;
                // Show the media preview and filename
                const imageSrc = (attachment.type === 'image')
                    ? attachment.sizes.thumbnail.url
                    : this.fileIconSrc;
                this.mediaPreview.setAttribute('src', imageSrc);
                this.chosenMediaFilename.innerHTML = attachment.filename;

                // Restore the main post ID
                wp.media.model.settings.post.id = window.NwMediaInputSettings.oldAttachmentId;
            });

            // Finally, open the modal
            this.fileFrame.open();
        });
    }

    /**
     * Enable the button that clears the current value
     * @return {void}
     */
    enableClearButton() {
        this.clearButton.addEventListener('click', event => {
            event.preventDefault();

            this.mediaPreview.setAttribute('src', this.transparentPixelSrc);
            this.inputField.value = '';
            this.chosenMediaFilename.innerHTML = this.chosenMediaFilename.dataset.fallbackText;
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.NwMediaInputSettings = {
        // Store the old id
        oldAttachmentId: (wp.media)
            ? wp.media.model.settings.post.id
            : null
    };

    document.querySelectorAll('.nw-media-upload-input')
        .forEach(element => {
            const mediaUploadInput = new NwMediaInput(element);
            mediaUploadInput.enableUploadButton();
            mediaUploadInput.enableClearButton();
        });

    // Restore the main ID when the add media button is pressed
    document.querySelectorAll('.add_media')
        .forEach(button => {
            button.addEventListener('click', () => {
                wp.media.model.settings.post.id = window.NwMediaInputSettings.oldAttachmentId;
            });
        });
});
