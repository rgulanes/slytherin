<?php

namespace Rougin\Slytherin\Application\Traits;

use Psr\Http\Message\ResponseInterface;

trait PrepareHttpResponseTrait
{
    /**
     * Sets the response to the user.
     * 
     * @param  mixed $result
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function prepareHttpResponse($result)
    {
        $response = $this->components->getHttpResponse();

        if ($result instanceof ResponseInterface) {
            $response = $result;
        } else {
            $response->getBody()->write($result);
        }

        // Sets the specified headers, if any.
        foreach ($response->getHeaders() as $name => $value) {
            header($name . ': ' . implode(',', $value));
        }

        // Sets the HTTP response code.
        http_response_code($response->getStatusCode());

        return $response;
    }
}