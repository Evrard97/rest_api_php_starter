<?php

namespace App\Controller;

use App\Util\Api\HttpStatusCode;

class BaseApiController
{
    protected $action;
    protected $uri_arguments;

    function __construct()
    {
        $uri_elements = \App\Util\Router::getUriElements();

        $this->action = $uri_elements[0];
        $this->uri_arguments = array_slice($uri_elements, 1);
    }

    protected function notFound()
    {
        $this->HttpResponse(HttpStatusCode::STATUS_404);
    }

    protected function forbidden()
    {
        $this->HttpResponse(HttpStatusCode::STATUS_403);
    }

    protected function unauthorized($response_body = null)
    {
        header('WWW-Authenticate: Basic realm="' . APP_TITLE . '"');
        $this->HttpResponse(HttpStatusCode::STATUS_401, $response_body);
    }


    /**
     * Retourne la réponse HTTP
     *
     * @param string $response_status
     * @param string $response_body
     * @return void
     */
    public function HttpResponse($response_status, $response_body = null)
    {
        header("Content-Type: application/json; charset=UTF-8");
        header($response_status);

        if (isset($response_body)) {
            print(\json_encode($response_body, JSON_PRETTY_PRINT));
        }
    }
}
