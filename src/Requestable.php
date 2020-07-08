<?php

namespace Actengage\LaravelMessageGears;

interface Requestable {

    /**
     * Cast the object into a request.
     * 
     * @return \GuzzleHttp\Psr7\Request
     */
    public function send();

}