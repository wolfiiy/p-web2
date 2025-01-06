<?php

/**
 * Author: Sebastien Tille
 * Date: October 8th, 2024
 */

/**
 * Holds application-wide constants.
 */
class Constants {
    /**
     * Date format used in the database.
     */
    const DB_DATE_FORMAT = "Y-m-d";

    /**
     * Error message to display if an error occures while reading the config 
     * file.
     */
    const ERROR_CONFIG_FILE = "
        Une erreur est survenue en essayant de lire le fichier de configuration. 
    ";

    const ERROR_DB_CONNECTION = "
        Une erreur est survenue lors de la connexion à la base de données. 
    ";

    /**
     * Error message to display if an error occured while executing a query.
     */
    const ERROR_QUERY_SIMPLE = "
        Une erreur est survenue pendant l'exécution de la requête (simple). 
    ";

    /**
     * Error message to display if an error occured while executing a query.
     */
    const ERROR_QUERY_PREPARE = "
        Une erreur est survenue pendant l'exécution de la requête (prepare). 
    ";

    /**
     * Error message to display when the field is missing in a form.
     */
    const ERROR_REQUIRED = "Veuillez renseigner ce champ";

    /**
     * Error message to display when is not alphabetic.
     */
    const ERROR_NAME = "Seuls les caractères alphabétique et les espaces sont autorisés";

    /**
     * Error message to display when is not alphabetic.
     */
    const ERROR_TEXT = "Seuls les caractères alphanumériques, les espaces et les caractères spéciaux suivants sont autorisés : &minus; . , : ; ( ) ? ! &#39;";

    /**
     * Error message to display when the text exceed the authorized size.
     */
    const ERROR_LENGTH  = "Le champ doit avoir un nombre de caractères entre 2 et 150";

    /**
     * Error message to display when the text exceed the authorized size.
     */
    const ERROR_RESUME  = "Le champ doit avoir un nombre de caractères entre 50 et 2000";

    /**
     * Error message to display when the field is not a url.
     */
    const ERROR_URL = "L'url n'est pas valide";

    /**
     * Message to display when no ratings have been given to a book.
     */
    const NO_RATING = "Aucune note";

    /**
     * Fallback cover in case the proper image could not be found.
     */
    const DEFAULT_COVER = "assets/img/placeholders/cover-placeholder.png";
}
