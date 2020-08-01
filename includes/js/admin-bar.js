/**
 * @constructor
 */
function AdminBar()
{
    var self = this;

    /**
     * The main body element
     * @type {HTMLElement}
     */
    self.bodyElement = document.body;

    /**
     * The button that toggles the bar location
     * @type {HTMLElement}
     */
    self.toggleButton = document.getElementById('wp-admin-bar-adminbar-location-toggle')
        .getElementsByClassName('ab-item')[0];

    /**
     * The icon element inside the button
     * @type {HTMLElement}
     */
    self.toggleButtonIcon = self.toggleButton.getElementsByClassName('dashicons')[0];

    /**
     * Enable the toggle mechanism
     * @return {void}
     */
    self.enableLocationToggle = function() {
        self.toggleButton.addEventListener('click', function(event) {
            event.preventDefault();

            if (self.bodyElement.classList.contains('admin-bar-bottom')) {
                // Move the bar to the top
                self.bodyElement.classList.remove('admin-bar-bottom');
                self.storeLocationValue('top');
            } else {
                // Move the bar to the bottom
                self.bodyElement.classList.add('admin-bar-bottom');
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
        if (self.bodyElement.classList.contains('admin-bar-bottom')) {
            self.toggleButtonIcon.classList.remove('dashicons-arrow-down-alt');
            self.toggleButtonIcon.classList.add('dashicons-arrow-up-alt');
        } else {
            self.toggleButtonIcon.classList.remove('dashicons-arrow-up-alt');
            self.toggleButtonIcon.classList.add('dashicons-arrow-down-alt');
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
            self.bodyElement.classList.add('admin-bar-bottom');
        }

        // Set the proper icon
        self.updateIcon();
    };
}

document.addEventListener('DOMContentLoaded', function() {
    var adminBar = new AdminBar();
    adminBar.init();
    adminBar.enableLocationToggle();
});
