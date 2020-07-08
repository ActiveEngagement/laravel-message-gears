<?php

namespace Actengage\LaravelMessageGears\Contracts;

interface HttpMessage {

    /**
     * Send the message as an http request
     * 
     * @return  \GuzzleHttp\Psr7\Response
     */
    public function send();

    /**
     * Cast the message as an array.
     * 
     * @return  array
     */
    public function toArray();
    
}