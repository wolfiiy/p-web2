<?php
/**
 * Author: STE
 * Date: October 8th, 2024
 */

/**
 * Holds application-wide constants.
 */
class Constants {
    /**
     * Error message to display when the last name is missing in a form.
     */
    const ERROR_MISSING_LAST_NAME = "Veuillez entrer un nom de famille.";

    /**
     * Error message to display when the first name is missing in a form.
     */
    const ERROR_MISSING_FIRST_NAME = "Veuillez entrer un prénom.";

    /**
     * Message to display when no ratings have been given to a book.
     */
    const NO_RATING = "Aucune note";

    /**
     * Fallback cover in case the proper image could not be found.
     */
    const DEFAULT_COVER = "assets/img/placeholders/cover-placeholder.png";
}
?>