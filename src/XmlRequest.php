<?php

namespace Actengage\LaravelMessageGears;

use GuzzleHttp\Psr7\Request;

trait XmlRequest {

    /**
     * Get the request version.
     *
     * @return string
     */
    public function getRequestVersion()
    {
        return '3.1';
    }

    /**
     * Get the request base URI.
     *
     * @return string
     */
    public function getRequestBaseUri()
    {
        return "https://api.messagegears.net/{$this->getRequestVersion()}/WebService";
    }

    /**
     * Get the request URI.
     *
     * @param  array  $query
     * @return string
     */
    public function getRequestUri(array $query = [])
    {
        $query = array_merge($query, $this->getRequestParams());
    
        return $this->getRequestBaseUri() . (
            $query && count($query) ? '?' . http_build_query($this->getRequestParams()) : null
        );
    }

    /**
     * Get the request parameters.
     *
     * @return array
     */
    public function getRequestParams()
    {        
        return [];
    }

    /**
     * Cast the message as a request.
     *
     * @param  array  $query
     * @return \GuzzleHttp\Psr7\Request
     */
    public function toRequest(array $params = null)
    {
        return new Request('GET', $this->getRequestUri());
    }

}