<?php

/**
 * Authors: Sébastien Tille, Abigaël Périsset
 * Date: December 17, 2024
 */

include_once('DatabaseModel.php');

/**
 * This class holds database queries relative to authors.
 */
Class AuthorModel extends DatabaseModel {
    
    /**
     * Given an ID, will attempt to get the corresponding author from the 
     * database. This method is safe to use with author-provided inputs.
     * @param int $id Unique identifier of the author. Used as a foreign key
     * in the t_book table.
     * @return array|null An array that contains information about the 
     * author (first and last names) if it has been found and false otherwise.
     */
    public function getAuthorById(int $id) {
        $sql = "select * from t_author where author_id = :author_id";
        $binds = array(':author_id' => $id);
        $req = $this->queryPrepareExecute($sql, $binds);

        if ($req) return $this->formatData($req)[0];
        else return false;
    }

    /**
     * Inserts a new author in the database.
     * @param string $firstName The author's first name.
     * @param string $lastName The author's last name.
     * @return PDOStatement|false The result of the query or false if it did not
     * go through.
     */
    public function insertAuthor(string $firstName, string $lastName){
        $sql = "
            insert into t_author (`first_name`, `last_name`) 
            values (:first_name, :last_name)
        ";

        $binds = array(
            'first_name'=> $firstName,
            'last_name' => $lastName,
        );

        return $query = $this->queryPrepareExecute($sql, $binds);
    }

    /**
     * Given first and last names, gets an author.
     * @param string $firstname The first name.
     * @param string $lastname The last name.
     * @return int The ID of that author or 0 if it could not be found.
     */
    public function getAuthorByNameAndFirstname(string $firstname, string $lastname){
        $sql = "
            SELECT author_id 
            FROM t_author 
            WHERE first_name = :fistname AND last_name = :lastname
        ";
        
        $binds = array(
            "fistname" => $firstname,
            "lastname" => $lastname
        );

        $query = $this->queryPrepareExecute($sql,$binds);
        $author = $this->formatData($query);

        if (empty($author))
            return 0;
        else
            return (int)$author[0]["author_id"];
    }
}