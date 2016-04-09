<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of Logging
 *
 * @author giovannifiore
 */
class Logging {

    const RED = "[31m";
    const GREEN = "[32m";
    const YELLOW = "[33m";
    const LIGHTCYAN = "[36m";
    const WHITE = "[0m";

    public static function printLog($msg) {
        if (is_array($msg))
            echo print_r() . "\n";
        else
            echo $msg . "\n";
    }

    public static function logType($text, $type) {
        $color = "";
        $escape = chr(27); //"\033"

        switch ($type) {
            case "SUCCESS":
                $color = self::GREEN;
                break;
            case "ERROR":
                $color = self::RED;
                break;
            case "INFO":
                $color = self::LIGHTCYAN;
                break;
            case "WARNING":
                $color = self::YELLOW;
                break;
        }

        return $escape . "$color" . "$text" . $escape . self::WHITE;
    }

}
