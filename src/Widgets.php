<?php

namespace Wordclass;

class Widgets {
    use Traits\CanSetTextDomain;



    /**
     * Enable shortcodes in widgets
     */
    public static function enableShortcodes() {
        add_filter('widget_text', 'do_shortcode');
    }



    /**
     * Register a sidebar
     * @param String  $id
     * @param String  $name
     * @param String  $description (Optional)
     * @param Array   $widget      'before' and 'after' setting
     * @param Array   $title       'before' and 'after' setting
     */
    public function addSidebar($id, $name, $description='', $widget=[], $title=[]) {
        add_action('widgets_init', function() use($id, $name, $description, $widget, $title) {
            $description = $description ?: $name;

            register_sidebar([
                'id'            => $id,
                'name'          => __($name, static::$_textDomain),
                'description'   => __('Sidebar: ' . $description, static::$_textDomain),
                'class'         => '',
                'before_widget' => @$widget['before']  ?:  '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => @$widget['after']  ?:  '</aside>',
                'before_title'  => @$title['before']  ?:  '<h1 class="widget-title">',
                'after_title'   => @$title['after']  ?:  '</h1>'
            ]);
        });
    }



    /**
     * Add multiple sidebars
     * @param Array  $sidebars  As ID:options pairs:
     *                            name, description, widget (array), title (array)
     *                            Instead of setting options, a name (string) can be given, using default options
     */
    public function addSidebars($sidebars) {
        foreach($sidebars as $id => $options) {
            if(is_string($options))
                $options = ['name' => $options];

            $options['description'] = @$options['description']  ?:  '';
            $options['widget'] = @$options['widget']  ?:  [];
            $options['title'] = @$options['title']  ?:  [];

            static::addSidebar($id, $options['name'], $options['description'], $options['widget'], $options['title']);
        }
    }
}
