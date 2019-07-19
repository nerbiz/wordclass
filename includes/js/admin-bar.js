/**
 * @constructor
 */
function AdminBar()
{
    var self = this;

    /**
     * The main body element
     * @type {jQuery}
     */
    self.$body = $('body');

    /**
     * The button that toggles the bar location
     * @type {jQuery}
     */
    self.$toggleButton = $('.adminbar-location-toggle-button').find('.ab-item');

    /**
     * The icon element inside the button
     * @type {jQuery}
     */
    self.$toggleButtonIcon = self.$toggleButton.find('.dashicons');

    /**
     * Enable the toggle mechanism
     * @return {void}
     */
    self.enableLocationToggle = function() {
        self.$toggleButton.on('click', function(event) {
            event.preventDefault();

            if (self.$body.hasClass('admin-bar-bottom')) {
                // Move the bar to the top
                self.$body.removeClass('admin-bar-bottom');
                self.storeLocationValue('top');
            } else {
                // Move the bar to the bottom
                self.$body.addClass('admin-bar-bottom');
                self.storeLocationValue('bottom');
            }

            self.updateIcon();
        });
    };

    /**
     * Update the icon in the button, based on the location of the bar
     * @return {void}
     */
    self.updateIcon = function() {
        if (self.$body.hasClass('admin-bar-bottom')) {
            self.$toggleButtonIcon
                .removeClass('dashicons-arrow-down-alt')
                .addClass('dashicons-arrow-up-alt');
        } else {
            self.$toggleButtonIcon
                .removeClass('dashicons-arrow-up-alt')
                .addClass('dashicons-arrow-down-alt');
        }
    };

    /**
     * Store the location value
     * @param value
     * @return {void}
     */
    self.storeLocationValue = function(value) {
        window.localStorage.setItem('admin-bar-location', value);
    };

    /**
     * Get the stored location value
     * @return {String|null}
     */
    self.getLocationValue = function() {
        return window.localStorage.getItem('admin-bar-location');
    };

    /**
     * Initialize the mechanism
     * @return {void}
     */
    self.init = function() {
        // Set the initial location
        if (self.getLocationValue() === 'bottom') {
            self.$body.addClass('admin-bar-bottom');
        }

        // Set the proper icon
        self.updateIcon();
    };
}

jQuery(document).ready(function ($) {
    var adminBar = new AdminBar();
    adminBar.init();
    adminBar.enableLocationToggle();
});
