<?php

namespace Nerbiz\Wordclass;

use Traits\CanSetPrefix;

class Shortcodes
{
    use CanSetPrefix;

    /**
     * The shortcode tag name
     * @var String
     */
    protected $tag;

    /**
     * Used for placing the new button in the editor
     * @var Integer|string|null
     */
    protected $toolbarNumber = 1;
    protected $toolbarAfter = null;

    /**
     * The text on the shortcode button in TinyMCE
     * @var String
     */
    protected $buttonText = null;

    /**
     * Whether the shortcode is enclosing (true) or self-closing (false)
     * @var Boolean
     */
    protected $enclosing = false;

    /**
     * Whether to add a corresponding button to TinyMCE or not
     * @var boolean
     */
    protected $addToEditor = true;

    /**
     * The parameters of the shortcode
     * @var Array
     */
    protected $parameters = [];

    /**
     * The default values for the shortcode attributes
     * @var Array
     */
    protected $defaults = [];

    /**
     * The shortcode handler
     * @var Callable
     */
    protected $hook;

    /**
     * The allowed input types for the parameters
     * This is used for the TinyMCE modal
     * @var Array
     */
    protected static $allowedInputTypes = null;

    /**
     * @see create()
     */
    protected function __construct($tag, $enclosing, $addtoeditor)
    {
        $this->tag = $tag;

        if (is_bool($enclosing)) {
            $this->enclosing = $enclosing;
        }

        if (is_bool($addtoeditor)) {
            $this->addToEditor = $addtoeditor;
        }
    }

    /**
     * Set where the button needs to be added to the TinyMCE editor
     * @param  int  $toolbar    The toolbar number, 1 = default, 2/3/4 = advanced
     * @param  string   $after      (Optional) the name of the button to place the new button after
     *                                'first' places the button as the first one
     *                                null places the button at the end
     * @return self
     */
    public function toolbar($toolbar, $after = null)
    {
        $this->toolbarNumber = $toolbar;
        $this->toolbarAfter = $after;

        return $this;
    }

    /**
     * Set the text to use on the corresponding TinyMCE button
     * @param  string  $text
     * @return self
     */
    public function buttonText($text)
    {
        $this->buttonText = $text;

        return $this;
    }

    /**
     * Add a label for the TinyMCE editor
     * This doesn't add anything to the shortcode
     * @param  string  $text  If empty, this will insert an empty line
     * @return self
     */
    public function addLabel($text = '')
    {
        if (trim($text) == '') {
            $text = html_entity_decode('&nbsp;');
        }

        $this->parameters[] = [
            'type' => 'label',
            'text' => $text
        ];

        return $this;
    }

    /**
     * Validate/improve the parameter options and return them
     * @param  array  $parameter
     * @return array
     */
    protected static function validateParameter($parameter)
    {
        // Make sure the options are an array
        if (is_string($parameter)) {
            $parameter = ['name' => $parameter];
        }

        // Set the default value for the parameter (or null if omitted)
        if (! array_key_exists('default', $parameter)) {
            $parameter['default'] = null;
        }

        // If no label is given, use the name
        if (! array_key_exists('label', $parameter)) {
            $parameter['label'] = $parameter['name'];
        }

        // Default and fallback type is 'text'
        if (! array_key_exists('type', $parameter) || ! in_array($parameter['type'], static::$allowedInputTypes)) {
            $parameter['type'] = 'text';
        }

        if ($parameter['type'] == 'dropdown') {
            // If no values are given, make it an empty array
            if (! array_key_exists('values', $parameter)) {
                $parameter['values'] = [];
            }

            // Set the default selected option
            $parameter['value'] = $parameter['default'];

            $values = [];
            // Start with the empty placeholder if given, and remove it from the array
            if (array_key_exists('placeholder', $parameter)) {
                $values[] = ['text' => $parameter['placeholder'], 'value' => ''];
                unset($parameter['placeholder']);
            }

            // Then add all the values, label and value are the same
            foreach ($parameter['values'] as $value => $text) {
                $values[] = ['text' => $text, 'value' => $value];
            }

            // Overwrite the values array
            $parameter['values'] = $values;
        }

        if ($parameter['type'] == 'checkbox') {
            // Default checkbox text is an empty string (label could be sufficient)
            if (! array_key_exists('text', $parameter)) {
                $parameter['text'] = '';
            }

            // Default 'checked' state is false
            if (! array_key_exists('checked', $parameter)) {
                $parameter['checked'] = false;
            }
        }

        return $parameter;
    }

    /**
     * Add a parameter to the shortcode
     * @param  string|array  $parameter  A string that defines the parameter name
     *                                   Or an array containing:
     *                                     name: the name of the parameter
     *                                     default: (Optional) the default value of the parameter (default is null)
     *                                     For the TinyMCE modal:
     *                                       type: (Optional) the type of input for the parameter (default is 'text')
     *                                       label: (Optional) the input label in the TinyMCE modal, when inserting the shortcode
     *                                     For type 'label':
     *                                       text: the text to show
     *                                     For type 'dropdown':
     *                                       values: an array of choosable values
     *                                       placeholder: (Optional) add an empty first option, like 'Please choose'
     *                                     For type 'checkbox':
     *                                       text: (Optional) the text to show next to the checkbox
     *                                       checked: (Optional) to show the checkbox checked by default (default is false)
     * @return self
     */
    public function addParameter($parameter)
    {
        $parameter = static::validateParameter($parameter);

        // Set the default value for this parameter and remove it from the array
        $this->defaults[$parameter['name']] = $parameter['default'];
        unset($parameter['default']);

        // Add the parameter to the collection
        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * Set the shortcode handler
     * @param  callable  $hook
     * @return self
     */
    public function hook($hook)
    {
        if (is_callable($hook)) {
            $this->hook = $hook;
        }

        return $this;
    }

    /**
     * Add the shortcode
     */
    public function add()
    {
        // Needed in the shortcode closure
        $tag = $this->tag;
        $defaults = $this->defaults;
        $hook = $this->hook;

        add_shortcode($tag, function ($parameters, $content = null) use ($tag, $defaults, $hook) {
            $parameters = shortcode_atts($defaults, $parameters, $tag);
            return $hook($parameters, $content);
        });

        // Add the corresponding button to TinyMCE if needed
        if ($this->addToEditor) {
            if ($this->buttonText === null) {
                $this->buttonText = $this->tag;
            }

            Editor::addShortcodeButton(
                [
                    'id'         => static::prefix() . '_' . $this->tag,
                    'tag'        => $this->tag,
                    'enclosing'  => $this->enclosing,
                    'buttontext' => $this->buttonText,
                    'inputs'     => $this->parameters
                ],
                $this->toolbarAfter,
                $this->toolbarNumber
            );
        }
    }

    /**
     * Predefined shortcode: [home_url]
     * Get the home URL of the website, with trailing slash
     * @param  bool  $add      (Optional) Whether to add a shortcode button to the editor or not
     * @param  string   $after    (Optional) the name of the button to place the new button after
     *                              'first' places the button as the first one
     *                              null places the button at the end
     * @param  int  $toolbar  (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     */
    public static function homeUrl($add = false, $after = null, $toolbar = 1)
    {
        static::create('home_url', false, $add)
            ->buttonText(__('Home URL', 'wordclass'))
            ->toolbar($toolbar, $after)
            ->hook(function () {
                return rtrim(esc_url(home_url()), '/') . '/';
            })
            ->add();
    }

    /**
     * Predefined shortcode: [copyright year='2017']
     * 'year' is optional, defaults to current
     * Creates a '© 2013 - 2017 Site name' line
     * @param  bool  $add      (Optional) Whether to add a shortcode button to the editor or not
     * @param  string   $after    (Optional) the name of the button to place the new button after
     *                              'first' places the button as the first one
     *                              null places the button at the end
     * @param  int  $toolbar  (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     */
    public static function copyright($add = false, $after = null, $toolbar = 1)
    {
        static::create('copyright', false, $add)
            ->buttonText(__('Copyright', 'wordclass'))
            ->toolbar($toolbar, $after)
            ->addParameter([
                'name'    => 'year',
                'label'   => __('Year', 'wordclass'),
                'type'    => 'text',
                'default' => date('Y'),
                'tooltip' => __('Default value is the current year', 'wordclass')
            ])
            ->hook(function ($parameters) {
                $currentYear = date('Y');
                $years = $parameters['year'];
                if ((int) $parameters['year'] < $currentYear) {
                    $years .= ' - ' . $currentYear;
                }

                return '&copy; ' . $years . ' ' . get_bloginfo('name');
            })
            ->add();
    }

    /**
     * Predefined shortcode: [google_analytics code='UA-...']
     * 'code' is the tracking code (no output if no code is given)
     * Creates a Google Analytics include script
     * @param  bool  $add      (Optional) Whether to add a shortcode button to the editor or not
     * @param  string   $after    (Optional) the name of the button to place the new button after
     *                              'first' places the button as the first one
     *                              null places the button at the end
     * @param  int  $toolbar  (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     */
    public static function googleAnalytics($add = false, $after = null, $toolbar = 1)
    {
        static::create('google_analytics', false, $add)
            ->buttonText(__('Google Analytics', 'wordclass'))
            ->toolbar($toolbar, $after)
            ->addParameter([
                'name'    => 'code',
                'label'   => __('Tracking code', 'wordclass'),
                'type'    => 'text'
            ])
            ->hook(function ($parameters) {
                // Tracking code is required
                if (is_string($parameters['code']) && strlen($parameters['code']) > 0) {
                    $trackingCode = $parameters['code'];
                    require __DIR__ . '/../includes/js/google-analytics.php';
                }
            })
            ->add();
    }

    /**
     * Predefined shortcode: [page_link id='1' class='css-class' target='_blank']linktext[/page_link]
     * 'class' is optional, and adds a CSS class to the element
     * 'target' is optional, and adds a 'target' attribute to the element
     * Creates an <a> element that links to a page of the site
     * @param  bool  $add      (Optional) Whether to add a shortcode button to the editor or not
     * @param  string   $after    (Optional) the name of the button to place the new button after
     *                              'first' places the button as the first one
     *                              null places the button at the end
     * @param  int  $toolbar  (Optional) the toolbar number, 1 = default, 2/3/4 = advanced
     */
    public static function pageLink($add = true, $after = null, $toolbar = 1)
    {
        // Construct the page options for the dropdown
        $pageOptions = [];
        $previousParent = 0;
        // Keeps track of the indentations per parent
        $indents = [0 => ''];
        foreach (get_pages([
            'post_type'    => 'page',
            'sort_order'   => 'asc',
            'sort_column'  => 'ID',
            'hierarchical' => true
        ]) as $page) {
            $prefix = '';
            if ($page->post_parent > 0) {
                // Add a new indentation if it doesn't exist yet
                if (! array_key_exists($page->post_parent, $indents)) {
                    // But only increase the indent, if the current parent is different from the previous
                    // Which means that this is a subpage of a subpage
                    if ($page->post_parent != $previousParent) {
                        $indents[$page->post_parent] .= $indents[$previousParent] . str_repeat(html_entity_decode('&nbsp;').' ', 2);
                        $previousParent = $page->post_parent;
                    }
                }

                // Add the ⤷ character to the prefix
                $prefix .= $indents[$page->post_parent] . '⤷ ';
            }
            // Indent resets at top-level pages
            else {
                $previousParent = 0;
            }

            // Add the post status to the prefix, if it's not published
            if ($page->post_status != 'publish') {
                $prefix .= '(' . $page->post_status . ') ';
            }

            // Add the option
            $pageOptions[$page->ID] = $prefix . $page->post_title;
        };

        static::create('page_link', true, $add)
            ->buttonText(__('Page link', 'wordclass'))
            ->toolbar($toolbar, $after)
            ->addParameter([
                'name'   => 'id',
                'label'  => __('Page', 'wordclass'),
                'type'   => 'dropdown',
                'values' => $pageOptions,
                'placeholder' => __('- Please choose -', 'wordclass'),
            ])
            ->addParameter([
                'name'  => 'class',
                'label' => __('CSS class', 'wordclass'),
                'type'  => 'text',
            ])
            ->addParameter([
                'name'  => 'target',
                'label' => __("'target' attribute", 'wordclass'),
                'type'  => 'text',
            ])
            ->hook(function ($parameters, $content) {
                if (is_numeric($parameters['id'])) {
                    $link = get_permalink($parameters['id']);
                    $class = ($parameters['class'] != '')
                        ? 'class="' . $parameters['class'] . '"'
                        : '';
                    $target = ($parameters['target'] != '')
                        ? 'target="' . $parameters['target'] . '"'
                        : '';

                    return sprintf(
                        '<a href="%s" %s %s>%s</a>',
                        $link,
                        $class,
                        $target,
                        $content
                    );
                }
            })
            ->add();
    }

    /**
     * Initialize the creation chain
     * @param  string   $tag          The tag of the shortcode
     * @param  bool  $enclosing    (Optional) to make the shortcode enclosing (default is false)
     * @param  bool  $addtoeditor  (Optional) add a corresponding button to TinyMCE (true) or not (false)
     * @return Shortcodes  An instance of this class
     */
    public static function create($tag, $enclosing = false, $addtoeditor = true)
    {
        // Set the allowed input types, if not set yet
        if (static::$allowedInputTypes === null) {
            static::$allowedInputTypes = ['text', 'dropdown', 'checkbox'];
        }

        return new static($tag, $enclosing, $addtoeditor);
    }
}
