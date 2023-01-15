<?php

namespace App\Util;


use App\Controller\ApiController;
use App\Controller\BaseApiController;
use App\Util\Api\HttpStatusCode;

/**
 * Routeur
 */
class Router
{

    /**
     * Route l'affichage des différentes pages en fonction de l'action de l'URL
     *
     * @return void
     */
    public static function route()
    {
        if (!Router::hasActions()) {
            Router::redirectHome();
        }

        $action = Router::getActions()[0];

        if (Routes::exists($action)) {
            if ($action === Routes::API) {
                (new ApiController())->api();
            } elseif ($action === Routes::TEST) {
                (new BaseApiController())->HttpResponse(HttpStatusCode::STATUS_200, ['action' => 'test']);
            } else {
                // Page non implémenté
                (new BaseApiController())->HttpResponse(HttpStatusCode::STATUS_200, ['action default' => 'default']);
            }
        } else {
            (new BaseApiController())->HttpResponse(HttpStatusCode::STATUS_404);
        }
    }

    /**
     * Découpe $_SERVER['REQUEST_URI'] et retourne les élements composant l'URI.
     *
     * @return array
     */
    public static function getUriElements(): array
    {
        $path = ltrim($_SERVER['REQUEST_URI'], '/');

        return array_filter(
            explode('/', $path),
            function ($value) {
                return $value !== null && $value !== "";
            }
        );
    }

    /**
     * Retourne si l'URI contient une action
     *
     * @return bool
     */
    public static function hasActions(): bool
    {
        return Router::getActions() !== [];
    }

    /**
     * Retourne l'action de l'URI
     *
     * @return array
     */
    public static function getActions(): array
    {
        return Router::getUriElements() ?? null;
    }

    /**
     * Modifie l'action de routage par $action
     *
     * @param string $action
     * @return void
     */
    public static function setAction(string $action)
    {
        $_GET['action'] = $action;
    }

    /**
     * Redirige vers l'URI construite a partir de $uri_element
     *
     * @param array $uri_element
     * @return void
     */
    public static function redirect($uri_element = [])
    {

        header('Location: /' . UrlPath::build_url(\array_values($uri_element)));
        die();
    }

    /**
     * Redirige vers la page actuelle.
     *
     * @param mixed $get_data
     * @return void
     */
    public static function reload()
    {
        Router::redirect(Router::getActions());
    }

    /**
     * Redirige vers la page de l'action de routage $action.
     *
     * @param string $action
     * @return void
     */
    public static function redirectAction(string $action)
    {
        Router::redirect(['action' => $action]);
    }

    /**
     * Redirige vers l'action par défaut
     *
     * @return void
     */
    public static function redirectHome()
    {
        Router::redirect([Routes::default()]);
    }
}
