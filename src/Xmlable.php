<?php

namespace Actengage\LaravelMessageGears;

interface Xmlable {

    /**
     * Casts the object as XML.
     * 
     * @return \Actengage\LaravelMessageGears\Xml;
     */
    public function toXml();

}