<?php


namespace app\src\Resquest;

class Request
{

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * @var array
     */
    private $parameters;

    /**
     * Request constructor.
     *
     * @param array $query Query string from the request
     * @param array $request Request body from the request (Post method)
     */
    public function __construct(array $query = [], array $request = []) {
        $this->parameters = array_merge($query, $request);
    }

    /**
     * Return parameters name from get or post arguments
     *
     * @param string $name Name of the parameters to retrieve
     * @return mixed|null
     */
    public function GetParameters(String $name) {
        if (!array_key_exists($name, $this->parameters)) {
            return null;
        }

        return $this->parameters[$name];
    }

    /**
     * Create a instance from global variable
     * This method needs to stay static and have the name create from global
     *
     * @return Request
     */
    public static function createFromGlobal() {
        return new self($_GET, $_POST);
    }

    /**
     * Return the request method used
     * if no method available return get by default
     *
     * @return mixed|string
     */
    public function getMethod() {
        if ($this->GetParameters('_METHOD') !== null) {
            return $this->GetParameters('_METHOD');
        }

        return $_SERVER['REQUEST_METHOD'] ?? self::GET;
    }

    /**
     * Return the request uri
     * also take care of removing the query string to not interfeer with our routing system
     *
     * @return string
     */
    public function getUri() {
        $uri = $_SERVER['REQUEST_URI'];

        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return $uri;
    }
}