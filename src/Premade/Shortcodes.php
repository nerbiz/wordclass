<?php

namespace Nerbiz\Wordclass\Premade;

use Nerbiz\Wordclass\Factory;
use Nerbiz\Wordclass\Shortcode;
use Nerbiz\Wordclass\WordclassInterface;

class Shortcodes implements WordclassInterface
{
    /**
     * [home_url]
     * Outputs the home URL of the website, with trailing slash
     * @return Shortcode
     * @throws \ReflectionException
     */
    public function homeUrl(): Shortcode
    {
        return Factory::make('Shortcode')
            ->setTag('home_url')
            ->setEnclosing(false)
            ->setOptionLabel(__('Home URL', 'wordclass'))
            ->setHandler(function () {
                return rtrim(home_url(), '/') . '/';
            })
            ->create();
    }

    /**
     * [copyright year='2018']
     * 'year' is optional, defaults to current
     * Creates a '© 2015 - 2018 Site name' line (where 2015 would be the start year)
     * @return Shortcode
     * @throws \ReflectionException
     */
    public function copyright(): Shortcode
    {
        return Factory::make('Shortcode')
            ->setTag('copyright')
            ->setEnclosing(false)
            ->setOptionLabel(__('Copyright', 'wordclass'))
            ->addAttribute('year', date('Y'), [
                'type'    => 'text',
                'label'   => __('Year', 'wordclass'),
                'tooltip' => __('Default value is the current year', 'wordclass'),
            ])
            ->setHandler(function ($attributes) {
                $currentYear = (int) date('Y');

                if (! isset($attributes['year'])) {
                    $years = $currentYear;
                } else {
                    $years = (int) $attributes['year'];
                    if ($attributes['year'] < $currentYear) {
                        $years .= ' - ' . $currentYear;
                    }
                }

                return '&copy; ' . $years . ' ' . get_bloginfo('name');
            })
            ->create();
    }

    /**
     * [page_link id="1" class="css-class" target="_blank"]link text[/page_link]
     * Creates an <a> element that links to a page
     * 'class' is optional, adds a CSS class to the element
     * 'target' is optional, adds a 'target' attribute to the element
     * @return Shortcode
     * @throws \ReflectionException
     */
    public function pageLink(): Shortcode
    {
        // Construct the page options for the dropdown
        $pageOptions = [
            [
                'value' => '',
                'text'  => __('- Please choose -', 'wordclass'),
            ]
        ];

        foreach (get_posts([
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ]) as $page) {
            $pageOptions[] = [
                'value' => $page->ID,
                'text'  => $page->post_title,
            ];
        };

        return Factory::make('Shortcode')
            ->setTag('page_link')
            ->setEnclosing(true)
            ->setOptionLabel(__('Page link', 'wordclass'))
            ->addAttribute('id', null, [
                'type'   => 'dropdown',
                'label'  => __('Page', 'wordclass'),
                'values' => $pageOptions,
            ])
            ->addAttribute('class', null, [
                'type'  => 'text',
                'label' =>__('CSS class', 'wordclass'),
            ])
            ->addAttribute('target', null, [
                'type'   => 'dropdown',
                'label'  => __("'target' attribute", 'wordclass'),
                'values' => [
                    [
                        'value' => '',
                        'text'  => __('Empty', 'wordclass'),
                    ],
                    [
                        'value' => '_self',
                        'text'  => '_self',
                    ],
                    [
                        'value' => '_blank',
                        'text'  => '_blank',
                    ],
                    [
                        'value' => '_parent',
                        'text'  => '_parent',
                    ],
                    [
                        'value' => '_top',
                        'text'  => '_top',
                    ],
                ]
            ])
            ->setHandler(function ($attributes, $content) {
                // Page ID is required
                if (is_numeric($attributes['id'])) {
                    $link = get_permalink($attributes['id']);
                    $class = (trim($attributes['class']) !== '')
                        ? 'class="' . $attributes['class'] . '"'
                        : '';
                    $target = (trim($attributes['target']) !== '')
                        ? 'target="' . $attributes['target'] . '"'
                        : '';

                    return sprintf('<a %s href="%s" %s>%s</a>', $class, $link, $target, $content);
                }
            })
            ->create();
    }
}
