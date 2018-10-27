<?php

namespace Nerbiz\Wordclass;

class Widgets
{
    /**
     * Enable shortcodes in widgets
     */
    public static function enableShortcodes()
    {
        add_filter('widget_text', 'do_shortcode');
    }

    /**
     * Register a sidebar
     * @param  string  $id
     * @param  string  $name
     * @param  string  $description (Optional)
     * @param  array   $widget      'before' and 'after' setting
     * @param  array   $title       'before' and 'after' setting
     */
    public static function addSidebar($id, $name, $description = '', $widget = [], $title = [])
    {
        add_action('widgets_init', function () use ($id, $name, $description, $widget, $title) {
            register_sidebar([
                'id'            => $id,
                'name'          => $name,
                'description'   => $description,
                'class'         => '',
                'before_widget' => @$widget['before'] ?: '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => @$widget['after'] ?: '</aside>',
                'before_title'  => @$title['before'] ?: '<h1 class="widget-title">',
                'after_title'   => @$title['after'] ?: '</h1>',
            ]);
        });
    }

    /**
     * Add multiple sidebars
     * @param  array  $sidebars  As ID:options pairs:
     *                            name, description, widget (array), title (array)
     *                            Instead of setting options, a name (string) can be given, using default options
     */
    public static function addSidebars($sidebars)
    {
        foreach ($sidebars as $id => $options) {
            if (is_string($options)) {
                $options = ['name' => $options];
            }

            $options['description'] = @$options['description'] ?: '';
            $options['widget'] = @$options['widget'] ?: [];
            $options['title'] = @$options['title'] ?: [];

            static::addSidebar(
                $id,
                $options['name'],
                $options['description'],
                $options['widget'],
                $options['title']
            );
        }
    }
}
