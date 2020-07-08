<?php

namespace Actengage\LaravelMessageGears\Api;

use Actengage\LaravelMessageGears\Contracts\HttpMessage;
use Illuminate\Contracts\Support\Arrayable;

abstract class Message implements Arrayable, HttpMessage {

}