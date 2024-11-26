<?php

/**
 * Author: Sébastien Tille
 * Date: November 26th, 2024
 */

/**
 * Helper class used for data formatting operations.
 */
class FormatUtils {

    /**
     * Date format to use throughout eh application.
     */
    const DATE_FORMAT = 'd MMMM yyyy';

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