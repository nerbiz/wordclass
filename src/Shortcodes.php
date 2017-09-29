<?php

namespace Wordclass;

class Shortcodes {
    use Traits\CanSetPrefix;



    /**
     * The shortcode tag name
     * @var String
     */
    private $_tag;

    /**
     * Used for placing the new button in the editor
     * @var Integer|String|null
     */
    private $_toolbarNumber = 1;
    private $_toolbarAfter = null;

    /**
     * The text on the shortcode button in TinyMCE
     * @var String
     */
    private $_buttonText = null;

    /**
     * Whether the shortcode is enclosing (true) or self-closing (false)
     * @var Boolean
     */
    private $_enclosing = false;

    /**
     * Whether to add a corresponding button to TinyMCE or not
     * @var boolean
     */
    private $_addToEditor = true;

    /**
     * The parameters of the shortcode
     * @var Array
     */
    private $_parameters = [];

    /**
     * The default values for the shortcode attributes
     * @var Array
     */
    private $_defaults = [];

    /**
     * The shortcode handler
     * @var Callable
     */
    private $_hook;

    /**
     * The allowed input types for the parameters
     * This is used for the TinyMCE modal
     * @var Array
     */
    private static $_allowedInputTypes = null;



    /**
     * @see create()
     */
    private function __construct($tag, $enclosing, $addtoeditor) {
        $this->_tag = $tag;

        if(is_bool($enclosing))
            $this->_enclosing = $enclosing;

        if(is_bool($addtoeditor))
            $this->_addToEditor = $addtoeditor;
    }



    /**
     * Set where the button needs to be added to the TinyMCE editor
     * @param  Integer  $toolbar    The toolbar number, 1 = default, 2/3/4 = advanced
     * @param  String   $after      (Optional) the name of the button to place the new button after
     *                                'first' places the button as the first one
     *                                null places the button at the end
     * @return $this
     */
    public function toolbar($toolbar, $after=null) {
        $this->_toolbarNumber = $toolbar;
        $this->_toolbarAfter = $after;

        return $this;
    }



    /**
     * Set the text to use on the corresponding TinyMCE button
     * @param  String  $text
     * @return $this
     */
    public function buttonText($text) {
        $this->_buttonText = $text;

        return $this;
    }



    /**
     * Add a label for the TinyMCE editor
     * This doesn't add anything to the shortcode
     * @param String  $text
     * @return $this
     */
    public function addLabel($text) {
        $this->_parameters[] = [
            'type' => 'label',
            'text' => $text
        ];

        return $this;
    }



    /**
     * Validate/improve the parameter options and return them
     * @param  Array  $parameter
     * @return Array
     */
    private static function validateParameter($parameter) {
        // Make sure the options are an array
        if(is_string($parameter))
            $parameter = ['name' => $parameter];

        // Set the default value for the parameter (or null if omitted)
        if( ! array_key_exists('default', $parameter))
            $parameter['default'] = null;

        // If no label is given, use the name
        if( ! array_key_exists('label', $parameter))
            $parameter['label'] = $parameter['name'];

        // Default and fallback type is 'text'
        if( ! array_key_exists('type', $parameter)  ||  ! in_array($parameter['type'], static::$_allowedInputTypes))
            $parameter['type'] = 'text';

        if($parameter['type'] == 'dropdown') {
            // If no values are given, make it an empty array
            if( ! array_key_exists('values', $parameter))
                $parameter['values'] = [];

            // Set the default selected option
            $parameter['value'] = $parameter['default'];

            $values = [];
            // Start with the empty placeholder if given, and remove it from the array
            if(array_key_exists('placeholder', $parameter)) {
                $values[] = ['text' => $parameter['placeholder'], 'value' => ''];
                unset($parameter['placeholder']);
            }

            // Then add all the values, label and value are the same
            foreach($parameter['values'] as $value => $text)
                $values[] = ['text' => $text, 'value' => $value];

            // Overwrite the values array
            $parameter['values'] = $values;
        }

        if($parameter['type'] == 'checkbox') {
            // Default checkbox text is an empty string (label could be sufficient)
            if( ! array_key_exists('text', $parameter))
                $parameter['text'] = '';

            // Default 'checked' state is false
            if( ! array_key_exists('checked', $parameter))
                $parameter['checked'] = false;
        }

        return $parameter;
    }



    /**
     * Add a parameter to the shortcode
     * @param String|Array  $parameter  A string that defines the parameter name
     *                                  Or an array containing:
     *                                    name: the name of the parameter
     *                                    default: (Optional) the default value of the parameter (default is null)
     *                                    For the TinyMCE modal:
     *                                      type: (Optional) the type of input for the parameter (default is 'text')
     *                                      label: (Optional) the input label in the TinyMCE modal, when inserting the shortcode
     *                                    For type 'label':
     *                                      text: the text to show
     *                                    For type 'dropdown':
     *                                      values: an array of choosable values
     *                                      placeholder: (Optional) add an empty first option, like 'Please choose'
     *                                    For type 'checkbox':
     *                                      text: (Optional) the text to show next to the checkbox
     *                                      checked: (Optional) to show the checkbox checked by default (default is false)
     * @return $this
     */
    public function addParameter($parameter) {
        $parameter = static::validateParameter($parameter);

        // Set the default value for this parameter and remove it from the array
        $this->_defaults[$parameter['name']] = $parameter['default'];
        unset($parameter['default']);

        // Add the parameter to the collection
        $this->_parameters[] = $parameter;

        return $this;
    }



    /**
     * Set the shortcode handler
     * @param  Callable  $hook
     * @return $this
     */
    public function hook($hook) {
        if(is_callable($hook))
            $this->_hook = $hook;

        return $this;
    }



    /**
     * Add the shortcode
     */
    public function add() {
        // Needed in the shortcode closure
        $tag = $this->_tag;
        $defaults = $this->_defaults;
        $hook = $this->_hook;

        add_shortcode($tag, function($parameters, $content=null) use($tag, $defaults, $hook) {
            $parameters = shortcode_atts($defaults, $parameters, $tag);
            return $hook($parameters, $content);
        });

        // Add the corresponding button to TinyMCE if needed
        if($this->_addToEditor) {
            if($this->_buttonText == null)
                $this->_buttonText = $this->_tag;

            Editor::addShortcodeButton(
                [
                    'id'         => static::prefix() . '_' . $this->_tag,
                    'tag'        => $this->_tag,
                    'enclosing'  => $this->_enclosing,
                    'buttontext' => $this->_buttonText,
                    'inputs'     => $this->_parameters
                ],
                $this->_toolbarNumber,
                $this->_toolbarAfter
            );
        }
    }



    /**
     * Predefined shortcode: [base_url]
     * Get the base URL of the website, with trailing slash
     * @return String
     */
    public static function baseUrl() {
        static::add('base_url', function() {
            return rtrim(esc_url(home_url()), '/') . '/';
        });
    }



    /**
     * Predefined shortcode: [copyright year='2017']
     * 'year' is optional, defaults to current
     * Creates a '© 2013 - 2017 Site name' line
     * @return String
     */
    public static function copyright() {
        static::add('copyright', function($params) {
            $currentYear = date('Y');
            $params = shortcode_atts(['year' => $currentYear], $params);

            $years = $params['year'];
            ((int) $params['year'] < $currentYear)  &&  $years .= ' - '.$currentYear;

            return '&copy; ' . $years . ' ' . get_bloginfo('name');
        });
    }



    /**
     * Initialize the creation chain
     * @param  String   $tag          The tag of the shortcode
     * @param  Boolean  $enclosing    (Optional) to make the shortcode enclosing (default is false)
     * @param  Boolean  $addtoeditor  (Optional) add a corresponding button to TinyMCE (true) or not (false)
     * @return Object  An instance of this class
     */
    public static function create($tag, $enclosing=false, $addtoeditor=true) {
        // Set the allowed input types, if not set yet
        if(static::$_allowedInputTypes == null)
            static::$_allowedInputTypes = ['text', 'dropdown', 'checkbox'];

        return new static($tag, $enclosing, $addtoeditor);
    }
}
