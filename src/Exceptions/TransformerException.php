<?php
namespace Maras0830\LaravelSRT\Exceptions;

use Exception;

class TransformerException extends Exception
{
    /**
     * @var array
     */
    private $required_keys;

    /**
     * PayNowException constructor.
     * @param $message
     * @param array $required_keys
     */
    public function __construct($message, $required_keys = [])
    {
        parent::__construct("$message:" . implode(',', $required_keys));

        $this->required_keys = $required_keys;
    }
}
