window.wordclassShortcodeButtonsPlugin = new function()
{
    var self = this;

    /**
     * Concatenate the parameters, but exclude empty values
     * @param  Object  params  Parameters as name:value pairs
     * @return String
     */
    self.concatenateParameters = function(params)
    {
        var string = '';

        for(var name in params) {
            if(params[name] != '') {
                string += ' ' + name + '="' + params[name] + '"';
            }
        }

        return string;
    };

    /**
     * Inserts the shortcode in the editor
     * Called directly after pressing a shortcode button, in case there are no parameters
     * Or after confirming a dialog, in case there are parameters
     * @param  Object  editor        TinyMCE editor instance
     * @param  Object  shortcode     The shortcode properties (defined in window.wordclassShortcodeButtons)
     * @param  String  params        The concatenated parameters string
     * @param  String  selectedText  The text that was selected in the editor, before a modal was opened
     */
    self.insertIntoEditor = function(editor, shortcode, params, selectedText)
    {
        // Construct and insert the shortcode
        var tag = '[' + shortcode.tag + params + ']';
        // Enclose the selected text in the shortcode
        if(shortcode.enclosing)
            tag += selectedText + '[/' + shortcode.tag + '] ';
        // Otherwise put the shortcode before the selected text, to not lose text
        else
            tag += ' ' + selectedText;

        editor.insertContent(tag);
    };

    /**
     * Initialize the plugin
     * @param  Object  editor  TinyMCE editor instance
     * @param  String  url     The plugin file URL
     */
    self.initPlugin = function(editor, url)
    {
        // Create all the shortcode buttons
        for(var i=-1;  ++i<window.wordclassShortcodeButtons.length;) {
            // Use an IIFE, so that values are scoped
            (function(editor, url, shortcode) {
                // Create and register the button
                editor.addButton(shortcode.id, {
                    text: shortcode.buttontext,
                    icon: 'code',
                    onclick: function() {
                        // If text is selected in the editor, store it for if the tag is enclosing
                        var selectedText = tinyMCE.activeEditor.selection.getContent();

                        // Don't open a modal dialog if there are no parameters
                        if(shortcode.inputs.length < 1) {
                            self.insertIntoEditor(editor, shortcode, '', selectedText);
                        }

                        else {
                            editor.windowManager.open({
                                title: shortcode.buttontext,
                                body: shortcode.inputs,
                                // Insert the shortcode when the window form is submitted
                                onsubmit: function(event) {
                                    var params = self.concatenateParameters(event.data);
                                    self.insertIntoEditor(editor, shortcode, params, selectedText);
                                }
                            });
                        }
                    }
                });
            })(editor, url, window.wordclassShortcodeButtons[i]);
        }
    };

    /**
     * Start adding the plugin
     */
    (self.addToTinyMce = function()
    {
        // Only if definitions have been given
        if(window.wordclassShortcodeButtons) {
            tinymce.create('tinymce.plugins.WordclassShortcodeButtons', {
                init: self.initPlugin
            });

            tinymce.PluginManager.add('wcshortcodebuttons', tinymce.plugins.WordclassShortcodeButtons);
        }
    })();
};
