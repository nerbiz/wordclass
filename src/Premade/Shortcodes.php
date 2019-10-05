<?php

namespace Nerbiz\Wordclass\Premade;

use Nerbiz\Wordclass\Shortcode;
use Nerbiz\Wordclass\WordclassInterface;

class Shortcodes
{
    /**
     * [home_url]
     * Outputs the home URL of the website, with trailing slash
     * @return Shortcode
     */
    public function homeUrl(): Shortcode
    {
        return (new Shortcode())
            ->setTag('home_url')
            ->setEnclosing(false)
            ->setOptionLabel(__('Home URL', 'wordclass'))
            ->setHandler(function () {
                return rtrim(home_url(), '/') . '/';
            })
            ->create();
    }

    /**
     * [page_link id="1" class="css-class" target="_blank"]link text[/page_link]
     * Creates an <a> element that links to a page
     * 'class' is optional, adds a CSS class to the element
     * 'target' is optional, adds a 'target' attribute to the element
     * @return Shortcode
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

        return (new Shortcode())
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
