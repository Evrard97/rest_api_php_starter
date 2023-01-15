<?php

namespace App\Controller;

use App\Util\Api\HttpStatusCode;
use App\Util\Router;
use App\Util\Routes;
use App\Util\Version;

class ApiController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct();

        if (count($this->uri_arguments) == 0) {
            Router::redirect([Routes::API, Routes::VERSION]);
        }
    }

    public function api()
    {
        if ($this->uri_arguments[0] == Routes::VERSION) {
            $this->HttpResponse(HttpStatusCode::STATUS_200, [Routes::VERSION => Version::getApiVersion()]);
        } else {
            $this->notFound();
        }
    }
}
