<?php

namespace App\Util\Api;

/**
 * Enumération des codes de statut HTTP
 */
abstract class HttpStatusCode
{
    /**
     * The request was successfully completed.
     */
    public const STATUS_200 = 'HTTP/1.1 200 OK';
    /**
     * A new resource was successfully created.
     */
    public const STATUS_201 = 'HTTP/1.1 201 Created';
    /**
     * The request was invalid.
     */
    public const STATUS_400 = 'HTTP/1.1 400 Bad Request';
    /**
     * The request did not include an authentication token or the authentication token was expired.
     */
    public const STATUS_401 = 'HTTP/1.1 401 Unauthorized';
    /**
     * The client did not have permission to access the requested resource.
     */
    public const STATUS_403 = 'HTTP/1.1 403 Forbidden';
    /**
     * The requested resource was not found.
     */
    public const STATUS_404 = 'HTTP/1.1 404 Not Found';
    /**
     * The HTTP method in the request was not supported by the resource. For example, the DELETE method cannot be used with the Agent API.
     */
    public const STATUS_405 = 'HTTP/1.1 405 Method Not Allowed';
    /**
     * The request could not be completed due to a conflict. For example,  POST ContentStore Folder API cannot complete if the given file or folder name already exists in the parent location.
     */
    public const STATUS_409 = 'HTTP/1.1 409 Conflict';
    /**
     * The HyperText Transfer Protocol (HTTP) 422 Unprocessable Entity response status code indicates that the server understands the content type of the request entity, and the syntax of the request entity is correct, but it was unable to process the contained instructions.
     */
    public const STATUS_422 = 'HTTP/1.1 422 Unprocessable Entity';
    /**
     * The request was not completed due to an internal error on the server side.
     */
    public const STATUS_500 = 'HTTP/1.1 500 Internal Server Error';
    /**
     * The server was unavailable.
     */
    public const STATUS_503 = 'HTTP/1.1 503 Service Unavailable';
}
