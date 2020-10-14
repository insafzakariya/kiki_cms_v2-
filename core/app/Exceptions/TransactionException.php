<?php
/**
 * Created by PhpStorm.
 * User: Nishan
 * Date: 3/2/2016
 * Time: 3:47 PM
 */

namespace App\Exceptions;


class TransactionException extends \Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}