<?php

namespace App\Util;


/**
 * Fonction de récupération du numéro de version depuis le fichier VERSION à la racine.
 */
class Version
{
    public static function getVersion() {
        return file_get_contents(APP_ROOT . 'VERSION');
    }

    public static function getCssVersion() {
        return '?v=' . Version::getVersion();
    }

    public static function getApiVersion() {
        return file_get_contents(APP_ROOT . 'API_VERSION');
    }
}
