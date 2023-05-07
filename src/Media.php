<?php

namespace Nerbiz\WordClass;

class Media
{
    /**
     * Set the size of featured images
     * @param  int  $width
     * @param  int  $height
     * @param  bool $crop Whether to resize (false) or crop (true) images
     * @return self
     */
    public function setFeaturedImageSize(int $width, int $height, bool $crop = false): self
    {
        add_action('after_setup_theme', function () use ($width, $height, $crop) {
            set_post_thumbnail_size($width, $height, $crop);
        });

        return $this;
    }

    /**
     * Add a new image size, and add it to the size chooser
     * @param  string $name          Key name for the $sizes array
     * @param  string $nameInChooser Name in the size chooser
     * @param  int    $width
     * @param  int    $height
     * @param  bool   $crop          Whether to resize (false) or crop (true) images
     * @return self
     */
    public function addImageSize(
        string $name,
        string $nameInChooser,
        int $width,
        int $height,
        bool $crop = false
    ): self {
        add_action('after_setup_theme', function () use ($name, $nameInChooser, $width, $height, $crop) {
            add_image_size($name, $width, $height, $crop);

            // Set the image size name for the chooser
            add_filter('image_size_names_choose', function ($sizes) use ($name, $nameInChooser) {
                $sizes[$name] = $nameInChooser;
                return $sizes;
            });
        });

        return $this;
    }

    /**
     * Add upload support for a specific file type
     * @param string $extension
     * @param string $mimeType
     * @return self
     */
    public function addUploadSupport(string $extension, string $mimeType): self
    {
        add_filter('upload_mimes', function (array $mimeTypes) use ($extension, $mimeType) {
            $mimeTypes[$extension] = $mimeType;

            return $mimeTypes;
        }, 10);

        return $this;
    }

    /**
     * Add support for uploading SVG files
     * @return self
     */
    public function addSvgSupport(): self
    {
        // Add SVG extension support
        $this->addUploadSupport('svg', 'image/svg+xml');

        // Adjust the SVG file if needed
        add_filter('wp_handle_upload_prefilter', function ($file) {
            // See if the file is an SVG file
            if ($file['type'] !== 'image/svg+xml') {
                return $file;
            }

            // Get the SVG file contents
            $svgContents = trim(file_get_contents($file['tmp_name']));

            // Add the '<?xml' line if it's missing
            if (! str_starts_with($svgContents, '<?xml')) {
                file_put_contents($file['tmp_name'], sprintf(
                    '%s%s%s',
                    '<?xml version="1.0" encoding="utf-8"?>',
                    PHP_EOL,
                    $svgContents
                ));
            }

            return $file;
        });

        return $this;
    }

    /**
     * Replace the hostname in media URLs, for local development with remote media
     * @param string   $hostName The hostname in 'example.com' or 'sub.example.com' format
     * @param string[] $environments The environments in which to replace the host
     * @return self
     */
    public function temporaryHost(
        string $hostName,
        array  $environments = ['local', 'development']
    ): self {
        // Skip when not in the right environment
        if (! in_array(wp_get_environment_type(), $environments, true)) {
            return $this;
        }

        add_filter('wp_get_attachment_image_src', function ($image) use ($hostName) {
            if (isset($image[0])) {
                $image[0] = str_replace($_SERVER['HTTP_HOST'], $hostName, $image[0]);
            }

            return $image;
        });

        add_filter('wp_get_attachment_url', function (string $url) use ($hostName) {
            return str_replace($_SERVER['HTTP_HOST'], $hostName, $url);
        });

        return $this;
    }
}
