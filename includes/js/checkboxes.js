class WordClassCheckboxes
{
    /**
     * Change the value of a hidden input that is coupled to a checkbox
     * @param {HTMLInputElement} checkbox
     * @returns {void}
     */
    changeValue(checkbox) {
        checkbox.previousElementSibling.value = (checkbox.checked)
            ? checkbox.dataset.onValue
            : checkbox.dataset.offValue;
    }

    /**
     * Enable checkbox handling
     * @returns {void}
     */
    enable() {
        // Set the current value at page load
        document.querySelectorAll('.wordclass-checkbox')
            .forEach(checkbox => this.changeValue(checkbox));

        // Update the value when a checkbox changes
        document.addEventListener('input', event => {
            if (event.target.classList.contains('wordclass-checkbox')) {
                this.changeValue(event.target);
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => new WordClassCheckboxes().enable());
