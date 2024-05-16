<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Helper {


    /**
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public static function getInitials( $string, $separator = '' )
    {
        $nameParts = explode( " ", $string );
        $initials = [];
        foreach ( $nameParts as $part )
        {
            $initials[] = mb_substr( $part, 0, 1 );
        }

        return implode( $separator, $initials );
    }

    /**
     * @param \Exception $exception
     * @param            $message
     * @param            $method
     * @param            $level
     *
     * @return string
     */
    public static function logError( \Exception $exception, $message, $method, $level )
    {
        $error_number = Str::random();
        Log::{$level}( "Error number: {$error_number} in {$method} - Message {$message} - Line Number {$exception->getLine() }" );
        return "Unknown error occured. Please report error number - {$error_number} - describing what you were trying to do when this message occured";
    }

}
