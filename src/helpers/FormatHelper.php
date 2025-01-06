<?php

/**
 * Author: Sébastien Tille
 * Date: November 26th, 2024
 */

/**
 * Helper class used for data formatting operations.
 */
class FormatHelper {

    /**
     * Date format to use throughout the application. To use for view only (the 
     * databases uses a separate format - see the DatabaseModel class).
     */
    private const DATE_FORMAT = 'j F Y';

    /**
     * Gets a string that contains the date in the "26 novembre 2024" format.
     * @param string $date The unformatted date.
     * @return string A string with the formatted date. 
     */
    public static function getFullDate(string $date) {
        $d = new DateTime($date);
        return $d->format(self::DATE_FORMAT);
    }
}