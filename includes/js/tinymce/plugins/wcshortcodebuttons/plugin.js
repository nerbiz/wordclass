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
                editor.addButton(shortcode.id, {
                    text: shortcode.buttontext,
                    icon: 'code',
                    cmd: shortcode.id
                });

                // Define the action for the button
                editor.addCommand(shortcode.id, function() {
                    // If text is selected in the editor, store it for if the tag is enclosing
                    var selectedText = tinyMCE.activeEditor.selection.getContent();

                    editor.windowManager.open({
                        title: shortcode.buttontext,
                        body: shortcode.inputs,
                        // Insert the shortcode when the window form is submitted
                        onsubmit: function(event) {
                            // Put the shortcode parameters in an array ('name="value"' strings)
                            // When the value is not empty
                            var params = [];
                            for(var name in event.data) {
                                if(event.data[name] != '')
                                    params.push(name + '="' + event.data[name] + '"');
                            }

                            // Construct and insert the shortcode
                            var tag = '[' + shortcode.tag + ' ' + params.join(' ') + ']';
                            // Enclose the selected text in the shortcode
                            if(shortcode.enclosing)
                                tag += selectedText + '[/' + shortcode.tag + '] ';
                            // Otherwise put the shortcode before the selected text, to not lose text
                            else
                                tag += ' ' + selectedText;

                            editor.insertContent(tag);
                        }
                    });
                });
            }
        }
    });

    tinymce.PluginManager.add('wcshortcodebuttons', tinymce.plugins.WordclassShortcodeButtons);
}
