<?php

/**
 * Authors: Valentin Pignat, Sébastien Tille
 * Date: November 25th, 2024
 */

include_once('DatabaseModel.php');

/**
 * This class holds database queries relative to users.
 */
Class UserModel extends DatabaseModel {
    
    /**
     * Given an ID, will attempt to get the corresponding user from the 
     * database. This method is safe to use with user-provided inputs.
     * @param int $id Unique identifier of the user. Used as a foreign key
     * in the t_book table.
     * @return array|null An array that contains information about the 
     * user (username, account creation date) if it has been found and false
     * otherwise.
     */
    public function getUserById(int $id) {
        $sql = "select * from t_user where user_id = :user_id";
        $binds = array(':user_id' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)[0];
    }
}