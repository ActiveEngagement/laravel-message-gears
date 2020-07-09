<?php

namespace Actengage\LaravelMessageGears;

class CData {

    /**
     * The CData content.
     * 
     * @var string
     */
    public $data;

    /**
     * Construct the CData instance.
     * 
     * @param  string  $data
     * @return string
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * Convert the CData to a string.
     * 
     * @return string
     */
    public function __toString()
    {
        return "<![CDATA[{$this->data}]]>";
    }

}