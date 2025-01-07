<?php

/**
 * Author: Sebastien Tille, Abigaël Périsset
 * Date: 07.01, 2024
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
     * Error message to display when the text exceed the authorized size.
     */
    const ERROR_NAME  = "Le champ doit avoir un nombre de caractères entre 2 et 128";

    /**
     * Error message to display when the text exceed the authorized size.
     */

     const ERROR_PUBLISHER  = "Le champ doit avoir un nombre de caractères entre 2 et 50";
    /**
     * Error message to display when the text exceed the authorized size.
     */
    const ERROR_TITLE  = "Le champ doit avoir un nombre de caractères entre 2 et 100";

    /**
     * Error message to display when the text exceed the authorized size.
     */
    const ERROR_RESUME  = "Le champ doit avoir un nombre de caractères entre 50 et 2000";

    /**
     * Error message to display when is not a positive number.
     */
    const ERROR_PAGE  = "Le champ doit avoir un nombre au dessus de 0";

    /**
     * Error message to display when the field is not a url.
     */
    const ERROR_URL = "L'url n'est pas valide";

    /**
     * Error message to display when there is no image upload.
     */
    const ERROR_IMAGE = "Veillez insérer une image.";

    /**
     * Error message to display when the size of the cover is to big.
     */
    const ERROR_SIZE = "Le fichier est trop volumineux. La limite est de 2 Mo.";

    /**
     * Error message to display when the file mime is not allowed.
     */
    const ERROR_FILE_MIME = "Seule les images jpeg, png sont autorisés.";

    /**
     * Error message to display when moving the file to cover directory is not working.
     */
    const ERROR_FILE_MOVE = "Impossible de télécharger votre image veuillez réessayer.";

    /**
     * Message to display when no ratings have been given to a book.
     */
    const NO_RATING = "Aucune note";

    /**
     * Fallback cover in case the proper image could not be found.
     */
    const DEFAULT_COVER = "assets/img/placeholders/cover-placeholder.png";
}
