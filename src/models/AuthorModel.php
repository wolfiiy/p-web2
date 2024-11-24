<?php

/**
 * Authors: Sébastien Tille
 * Date: November 25th, 2024
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
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)[0];
    }
}