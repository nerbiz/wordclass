<?php

namespace Nerbiz\Wordclass;

use WP_Query;

class Pages
{
    /**
     * Let WordPress handle the window title
     * When using this, remove the <title> tag from <head>
     * @return self
     */
    public function automaticWindowTitle(): self
    {
        add_action('after_setup_theme', function () {
            add_theme_support('title-tag');
        }, 10);

        return $this;
    }

    /**
     * Change the separator between the page title and site name
     * i.e. the dash in 'About us - Company name'
     * @param string $separator
     * @return self
     */
    public function setWindowTitleSeparator(string $separator): self
    {
        $separator = trim($separator);
        if ($separator === '') {
            return $this;
        }

        add_filter('document_title_separator', function (string $wpSeparator) use ($separator) {
            return $separator;
        }, 10);

        return $this;
    }

    /**
     * Set the window title of the 404 page
     * @param string $title
     * @return self
     */
    public function set404PageTitle(string $title): self
    {
        $title = trim($title);
        if ($title === '') {
            return $this;
        }

        add_filter('document_title_parts', function (array $parts) use ($title) {
            if (! is_admin() && is_404()) {
                $parts['title'] = $title;
            }

            return $parts;
        }, 10);

        return $this;
    }

    /**
     * Set the window title of the search results page
     * @param string $title
     * @return self
     */
    public function setSearchPageTitle(string $title): self
    {
        $title = trim($title);
        if ($title === '') {
            return $this;
        }

        add_filter('document_title_parts', function (array $parts) use ($title) {
            if (! is_admin() && is_search()) {
                $parts['title'] = $title;
            }

            return $parts;
        }, 10);

        return $this;
    }

    /**
     * Disable search page, search form and search widget
     * @return self
     */
    public function disableSearchFunctionality(): self
    {
        // Show a 404 page instead of search page
        add_action('parse_query', function (WP_Query $query) {
            if (! is_admin() && is_search()) {
                // Cancel the search
                $query->is_search = false;
                $query->query_vars['s'] = false;
                $query->query['s'] = false;

                // Trigger a 404 error
                $query->set_404();
                status_header(404);
            }
        }, 10);

        // Replace the search form HTML with an empty string
        add_filter('get_search_form', '__return_empty_string', 10);

        // Unregister the search widget
        add_action('widgets_init', function () {
            unregister_widget('WP_Widget_Search');
        }, 10);

        return $this;
    }
}
