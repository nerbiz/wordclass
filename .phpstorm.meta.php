<?php

namespace PHPSTORM_META {
    override(
        \Nerbiz\Wordclass\Factory::make(0),
        map([
            'Admin'              => \Nerbiz\Wordclass\Admin::class,
            'AdminBar'           => \Nerbiz\Wordclass\AdminBar::class,
            'Assets'             => \Nerbiz\Wordclass\Assets::class,
            'Crypto'             => \Nerbiz\Wordclass\Crypto::class,
            'Editor'             => \Nerbiz\Wordclass\Editor::class,
            'Fixes'              => \Nerbiz\Wordclass\Fixes::class,
            'Helpers'            => \Nerbiz\Wordclass\Helpers::class,
            'Init'               => \Nerbiz\Wordclass\Init::class,
            'Mail'               => \Nerbiz\Wordclass\Mail::class,
            'Plugins'            => \Nerbiz\Wordclass\Plugins::class,
            'PostColumn'         => \Nerbiz\Wordclass\PostColumn::class,
            'PostColumnsEditor'  => \Nerbiz\Wordclass\PostColumnsEditor::class,
            'PostType'           => \Nerbiz\Wordclass\PostType::class,
            'SettingsPage'       => \Nerbiz\Wordclass\SettingsPage::class,
            'Shortcode'          => \Nerbiz\Wordclass\Shortcode::class,
            'Taxonomy'           => \Nerbiz\Wordclass\Taxonomy::class,
            'Theme'              => \Nerbiz\Wordclass\Theme::class,
            'Utilities'          => \Nerbiz\Wordclass\Utilities::class,
            'Webpack'            => \Nerbiz\Wordclass\Webpack::class,
            'Yoast'              => \Nerbiz\Wordclass\Yoast::class,
            'Premade\Shortcodes' => \Nerbiz\Wordclass\Premade\Shortcodes::class,
        ])
    );

    override(
        \Nerbiz\Wordclass\Factory::reuse(0),
        map([
            'Admin'              => \Nerbiz\Wordclass\Admin::class,
            'AdminBar'           => \Nerbiz\Wordclass\AdminBar::class,
            'Assets'             => \Nerbiz\Wordclass\Assets::class,
            'Crypto'             => \Nerbiz\Wordclass\Crypto::class,
            'Editor'             => \Nerbiz\Wordclass\Editor::class,
            'Fixes'              => \Nerbiz\Wordclass\Fixes::class,
            'Helpers'            => \Nerbiz\Wordclass\Helpers::class,
            'Init'               => \Nerbiz\Wordclass\Init::class,
            'Mail'               => \Nerbiz\Wordclass\Mail::class,
            'Plugins'            => \Nerbiz\Wordclass\Plugins::class,
            'PostColumn'         => \Nerbiz\Wordclass\PostColumn::class,
            'PostColumnsEditor'  => \Nerbiz\Wordclass\PostColumnsEditor::class,
            'PostType'           => \Nerbiz\Wordclass\PostType::class,
            'SettingsPage'       => \Nerbiz\Wordclass\SettingsPage::class,
            'Shortcode'          => \Nerbiz\Wordclass\Shortcode::class,
            'Taxonomy'           => \Nerbiz\Wordclass\Taxonomy::class,
            'Theme'              => \Nerbiz\Wordclass\Theme::class,
            'Utilities'          => \Nerbiz\Wordclass\Utilities::class,
            'Webpack'            => \Nerbiz\Wordclass\Webpack::class,
            'Yoast'              => \Nerbiz\Wordclass\Yoast::class,
            'Premade\Shortcodes' => \Nerbiz\Wordclass\Premade\Shortcodes::class,
        ])
    );
}
