<?php

namespace Heyday\SilverStripe\WkHtml;

use SilverStripe\View\ViewableData;

/**
 * Class TemplateHelper
 * @package Heyday\SilverStripe\WkHtml
 */
class TemplateHelper extends ViewableData
{
    /**
     * @param $css
     * @return string
     */
    public function EmbedCss($css)
    {
        if (file_exists(BASE_PATH . $css)) {
            return '<style>' . file_get_contents(BASE_PATH . $css) . '</style>';
        }
    }
    /**
     * Make a data URL from an image file
     * @param $path
     * @return string
     */
    public function EmbedBase64Image($path)
    {
        $path = BASE_PATH . $path;

        if (file_exists($path)) {
            $mime = $this->getMimeType($path);

            if ($mime) {
                return 'data:'.$mime.';base64,' . base64_encode(file_get_contents($path));
            }
        }
    }
    /**
     * Guess mime type based on file extension
     * @param  $path
     * @return string
     */
    public function getMimeType($path)
    {
        $mimeTypes = array(
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml'
        );

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (array_key_exists($ext, $mimeTypes)) {
            return $mimeTypes[$ext];
        }
    }
}