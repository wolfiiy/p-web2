<?php
/**
 * Author: Sebastien Tille
 * Date: September 30th, 2024
 */

/**
 * Handles database interactions.
 */
class DatabaseModel {

    /**
     * Path to the configuration file.
     */
    private const PATH_TO_CONFIG = "../../secrets.json";

    /**
     * Connection to the database.
     */
    private $connector;

    /**
     * Creates a new Database instance.
     * 
     * Establishes a connection to a database using the provided configuration.
     * The configuration file is located at the root of the application and is
     * named 'config.json'. It should contain the following keys:
     * - DBMS: the database management system.
     * - DB_HOST: the hostname or the IP address of the database server.
     * - DB_NAME: the name of the database to use.
     * - DB_CHARSET: the character set to use.
     * - DB_USER: the username for database authentification.
     * - DB_PASSWORD: the password for database authentification. 
     * 
     * @throws Exception If there is an error reading the config file.
     * @throws PDOException If the connection to the database fails.
     */
    public function __construct() {
        try {
            // Attempt to read the config file
            $configFile = file_get_contents(self::PATH_TO_CONFIG);
            $conf = json_decode($configFile, true);

            // Create the DSN
            $dsn = $conf['DBMS'] . ":";
            $dsn .= "host=" . $conf['DB_HOST'] . ";";
            $dsn .= "dbname=" . $conf['DB_NAME'] . ";";
            $dsn .= "charset=" . $conf['DB_CHARSET'];
        } catch (Exception $e) {
            die (Constants::ERROR_CONFIG_FILE
                . $e -> getMessage());
        }

        try {
            // Attempt to establish a connection to the database
            $this -> connector = new PDO(
                $dsn,
                $conf['DB_USER'],
                $conf['DB_PASSWORD']
            );
        } catch (PDOException $e) {
            // Kill the application if it failed to connect
            die(
                Constants::ERROR_DB_CONNECTION 
                . $e -> getMessage()
            );
        }
    }

    /**
     * Executes a simple SQL query with no protection against SQL injections.
     * Suitable for read-only operations with no user-provided values.
     * 
     * @param string $query The SQL query string to be executed.
     * @return PDOStatement|false An associative array containing the result 
     * of the query if successful, or `false` otherwise.
     */
    protected function querySimpleExecute(string $query) {
        try {
            // Directly execute the query
            return $this -> connector -> query($query);
        } catch (PDOException $e) {
            // Log any PDO-related exceptions
            error_log(
                Constants::ERROR_QUERY_PREPARE 
                . $e -> getMessage()
            );
            return false;
        }
    }

    /**
     * Executes an SQL query with protection against SQL injections. Suitable
     * for write operations with user-provided values.
     * 
     * @param string $query The SQL query string to be executed.
     * @param array $binds An associative array of parameters to bind to the 
     * query. Each array key should match the placeholder in the query string.
     * (e.g. $binds['teaFirstname'] = 'John').
     * @return PDOStatement|false an associative array containing the result
     * of the query if it succeeded, false otherwise.
     */
    protected function queryPrepareExecute(string $query, $binds) {
        try {
            // Prepare the SQL query string by protecting it against SQL
            // injections and binding values
            $req = $this -> connector -> prepare($query);
            foreach ($binds as $name => $value) {
                if (!is_int($value)) {
                    $req -> bindValue($name, $value, PDO::PARAM_STR);
                } else {
                    $req -> bindValue($name, $value, PDO::PARAM_INT);
                }
            }

            // Return the request only if successful
            $req -> execute(); 
            return $req;
        } catch (PDOException $e) {
            // Log any PDO-related exceptions
            error_log(
                Constants::ERROR_QUERY_PREPARE 
                . $e -> getMessage()
            );
        }
    }

    /**
     * Given an executed PDO statement, convert its result into an associative
     * array.
     * 
     * @param PDOStatement $req An executed statement.
     * @return array An associative array containing the data returned by the 
     * statement.
     */
    protected function formatData(PDOStatement $req) {
        return $req -> fetchAll(PDO::FETCH_ASSOC);
    }
}
?>