<?php

namespace Actengage\LaravelMessageGears;

use Illuminate\Contracts\Support\Arrayable;

class Recipient implements Arrayable, Xmlable {

    /**
     * The recipient email address.
     *
     * @var string
     */
    public $emailAddress;

    /**
     * The recipient recipient id.
     *
     * @var string
     */
    public $recipientId;

    /**
     * The recipient meta data.
     *
     * @var array
     */
    public $meta = [];

    /**
     * Construct the recipient
     *
     * @return void
     */
    public function __construct($params = [])
    {
        if(is_string($params)) {
            $params = ['email' => $params];
        }

        foreach($params as $key => $value) {
            if(method_exists($this, $key)) {
                $this->$key($value);
            }
            else {
                $this->meta($key, $value);
            }
        }
    }

    /**
     * Get the meta key/values if a non existent property is set.
     *
     * @return mixed
     */
    public function __get($key)
    {
        if(array_key_exists($key, $this->meta)) {
            return $this->meta[$key];
        }
        
        return null;
    }

    /**
     * Set the email address of the recipient.
     *
     * @param  string  $emailAddress
     * @return static
     */
    public function email(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Set the id of the recipient.
     *
     * @param  string  $id
     * @return static
     */
    public function id(string $recipientId)
    {
        $this->recipientId = $recipientId;

        return $this;
    }

    /**
     * Sets the meta key/value pair.
     *
     * @return static
     */
    public function meta($key, $value)
    {
        $this->meta[$key] = $value;

        return $this;
    }

    /**
     * Convert the recipient to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $values = array_filter([
            'EmailAddress' => $this->emailAddress,
            'RecipientId' => $this->recipientId,
        ]);

        return array_merge($values, $this->meta);
    }

    public function toXml()
    {
        return Xml::fromArray($this->toArray(), new Xml('<Recipient/>'));
    }
}