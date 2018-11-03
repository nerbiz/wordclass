window.wordclassShortcodeButtonsPlugin = new function()
{
    var self = this;

    /**
     * Inserts the shortcode in the editor
     * Called directly after pressing a shortcode button, in case there are no parameters
     * Or after confirming a dialog, in case there are parameters
     * @param  {Object} tinyMce      TinyMCE editor instance
     * @param  {Object} properties   The shortcode properties
     * @param  {Array}  attributes   Submitted attributes data
     * @param  {String} selectedText The text that was selected in the editor, before a modal was opened
     * @return {void}
     */
    self.insertIntoEditor = function(tinyMce, properties, attributes, selectedText)
    {
        // Convert the attributes array to a string
        var attributesString = '';
        for (var name in attributes) {
            if (attributes[name] !== '') {
                attributesString += ' ' + name + '="' + attributes[name] + '"';
            }
        }

        // Construct and insert the shortcode
        var tag = '[' + properties.tag + attributesString + ']';

        // Enclose selected text, or put the shortcode before it
        if (properties.enclosing) {
            tag += selectedText + '[/' + properties.tag + '] ';
        } else {
            tag += ' ' + selectedText;
        }

        tinyMce.insertContent(tag);
    };

    /**
     * Convert a properties array to a menu item definition
     * @param  {Object} tinyMce TinyMCE editor instance
     * @param  {Array}  properties
     * @return {Object}
     */
    self.createMenuItemDefinition = function(tinyMce, properties)
    {
        return {
            text: properties.optionLabel,
            onclick: function () {
                // Keep selected text, if there is any
                var selectedText = tinyMCE.activeEditor.selection.getContent();

                // Don't open a modal dialog if there are no parameters
                if (properties.inputs === undefined || properties.inputs.length < 1) {
                    self.insertIntoEditor(tinyMce, properties, [], selectedText);
                } else {
                    tinyMce.windowManager.open({
                        title: properties.optionLabel,
                        body: properties.inputs,
                        // Insert the shortcode when the window form is submitted
                        onsubmit: function (event) {
                            self.insertIntoEditor(tinyMce, properties, event.data, selectedText);
                        }
                    });
                }
            },
        };
    };

    /**
     * Initialize the plugin
     * @param  {Object} tinyMce TinyMCE editor instance
     * @param  {String} url     The plugin file URL
     * @return {void}
     */
    self.initPlugin = function(tinyMce, url)
    {
        // Create and register the button
        tinyMce.addButton('wc_shortcodes', {
            type: 'menubutton',
            text: 'Shortcodes',
            icon: null,
            menu: window.wordclassShortcodeButtons.map(function(properties) {
                return self.createMenuItemDefinition(tinyMce, properties);
            }),
        });
    };

    /**
     * Add the plugin
     * @return {void}
     */
    (self.addToTinyMce = function()
    {
        // Only if definitions have been given
        if (window.wordclassShortcodeButtons !== undefined) {
            tinymce.create('tinymce.plugins.WordclassShortcodeButtons', {
                init: self.initPlugin
            });

            tinymce.PluginManager.add('wc_shortcodebuttons', tinymce.plugins.WordclassShortcodeButtons);
        }
    })();
};
