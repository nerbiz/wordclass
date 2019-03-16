jQuery(document).ready(function ($) {
    // Uploading files
    var fileFrame;
    var oldAttachmentId = wp.media.model.settings.post.id; // Store the old id

    $('.upload-media-button').on('click', function (event) {
        event.preventDefault();

        // Get the current attachment ID
        var $button = $(event.target);
        var currentAttachmentId = $button.data('attachmentId');

        // If the media frame already exists, reopen it.
        if (fileFrame) {
            // Set the post ID to what we want
            fileFrame.uploader.uploader.param('post_id', currentAttachmentId);
            // Open frame
            fileFrame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id = currentAttachmentId;
        }

        // Create the media frame
        fileFrame = wp.media.frames.file_frame = wp.media({
            title: 'Select a image to upload',
            button: {
                text: 'Use this image',
            },
            // Don't allow multiple files
            multiple: false
        });

        // When an image is selected, run a callback.
        fileFrame.on('select', function () {
            // We set multiple to false so only get one image from the uploader
            var attachment = fileFrame.state().get('selection').first().toJSON();

            // Show the image preview
            $button.prev('.image-preview-wrapper')
                .find('.image-preview')
                .attr('src', attachment.url);
            // Set the attachment ID in the input field
            $button.next('input[type="hidden"]').val(attachment.id);

            // Restore the main post ID
            wp.media.model.settings.post.id = oldAttachmentId;
        });

        // Finally, open the modal
        fileFrame.open();
    });

    // Restore the main ID when the add media button is pressed
    $('.add_media').on('click', function () {
        wp.media.model.settings.post.id = oldAttachmentId;
    });
});
