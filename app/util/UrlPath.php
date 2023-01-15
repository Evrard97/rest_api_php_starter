<?php

namespace App\Util;

/**
 * Fonctions relatives a la manipulation des chemin de fichier & url
 */
class UrlPath
{
    public static function add_post_data_to_url($url, $post_data)
    {
        if (isset($post_data) && !empty($post_data)) {
            return $url . '?' . http_build_query($post_data);
        }

        return $url;
    }

    /**
     * Construit une URI en utilisant les elements de $uri_elements
     *
     * @param array $uri_elements
     * @return string
     */
    public static function build_url(array $uri_elements): string
    {
        return \implode('/', $uri_elements);
    }
}
