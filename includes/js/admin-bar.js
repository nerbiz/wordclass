class WordClassAdminBar
{
    /**
     * The button that toggles the bar location
     * @type {HTMLElement|null}
     */
    toggleButton = null;

    /**
     * The icon element inside the button
     * @type {HTMLElement|null}
     */
    toggleButtonIcon = null;

    /**
     * Enable the toggle mechanism
     * @return {void}
     */
    enableLocationToggle() {
        this.toggleButton.addEventListener('click', event => {
            event.preventDefault();

            if (document.body.classList.contains('admin-bar-bottom')) {
                // Move the bar to the top
                document.body.classList.remove('admin-bar-bottom');
                this.storeLocationValue('top');
            } else {
                // Move the bar to the bottom
                document.body.classList.add('admin-bar-bottom');
                this.storeLocationValue('bottom');
            }

            this.updateIcon();
        });
    }

    /**
     * Update the icon in the button, based on the location of the bar
     * @return {void}
     */
    updateIcon() {
        if (document.body.classList.contains('admin-bar-bottom')) {
            this.toggleButtonIcon.classList.remove('dashicons-arrow-down-alt');
            this.toggleButtonIcon.classList.add('dashicons-arrow-up-alt');
        } else {
            this.toggleButtonIcon.classList.remove('dashicons-arrow-up-alt');
            this.toggleButtonIcon.classList.add('dashicons-arrow-down-alt');
        }
    }

    /**
     * Store the location value
     * @param value
     * @return {void}
     */
    storeLocationValue(value) {
        window.localStorage.setItem('admin-bar-location', value);
    }

    /**
     * Get the stored location value
     * @return {String|null}
     */
    getLocationValue() {
        return window.localStorage.getItem('admin-bar-location');
    }

    /**
     * Initialize the mechanism
     * @return {void}
     */
    init() {
        const toggleButtonParent = document.getElementById('wp-admin-bar-wordclass-adminbar-location-toggle');

        // Disable if the element doesn't exist
        if (toggleButtonParent === null) {
            return;
        }

        this.toggleButton = toggleButtonParent.querySelector('.ab-item');
        this.toggleButtonIcon = this.toggleButton.querySelector('.dashicons');

        // Set the initial location
        if (this.getLocationValue() === 'bottom') {
            document.body.classList.add('admin-bar-bottom');
        }

        this.updateIcon();
        this.enableLocationToggle();
    }
}

document.addEventListener('DOMContentLoaded', () => new WordClassAdminBar().init());
