// Only if definitions have been given
if(window.wordclassShortcodeButtons) {
    tinymce.create('tinymce.plugins.WordclassShortcodeButtons', {
        init: function(editor, url) {
            var shortcode = null;

            // Loop over the shortcode dialog definitions
            for(var i=-1;  ++i<window.wordclassShortcodeButtons.length;) {
                // For convenience
                shortcode = window.wordclassShortcodeButtons[i];

                // Create and register the button
                editor.addButton(shortcode.name, {
                    text: shortcode.text,
                    cmd: shortcode.name
                });

                // Define the action for the button
                editor.addCommand(shortcode.name, function() {
                    editor.windowManager.open({
                        title: shortcode.text,
                        body: shortcode.inputs,
                        // Insert the shortcode when the window form is submitted
                        onsubmit: function(event) {
                            // Put the shortcode parameters in an array ('name="value"' strings)
                            // When the value is not empty
                            var params = [];
                            var name = null;
                            var value = null;
                            for(var j=-1;  ++j<shortcode.inputs.length;) {
                                name = shortcode.inputs[j].name;
                                value = event.data[name];

                                if(value != '')
                                    params.push(name + '="' + value + '"');
                            }

                            // Construct the shortcode
                            editor.insertContent('[' + shortcode.code + ' ' + params.join(' ') + ']');
                        }
                    });
                });
            }
        }
    });

    tinymce.PluginManager.add('wcshortcodebuttons', tinymce.plugins.WordclassShortcodeButtons);
}
