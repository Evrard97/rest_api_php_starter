<?php

namespace App\Util;


/**
 * Enumération des actions routées.
 */
abstract class Routes
{
    /* API */
    const API = 'api';
    const VERSION = 'version';
    const TEST = 'test';

    /**
     * Retourne toute les constantes
     *
     * @return array
     */
    public static function all(): array
    {
        $refl = new \ReflectionClass('\App\Util\Routes');

        return $refl->getConstants();
    }

    /**
     * Retourne si $action existe.
     *
     * @param string $action
     * @return bool
     */
    public static function exists($action): bool
    {
        $refl = new \ReflectionClass('\App\Util\Routes');

        return in_array($action, $refl->getConstants());
    }

    /**
     * Retourne l'action par défaut
     *
     * @return string
     */
    public static function default(): string
    {
        return Routes::API;
    }
}
