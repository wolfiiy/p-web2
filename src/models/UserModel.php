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
        $binds = array('user_id' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)[0];
    }

    /**
     * Request to the database to get all existing users
     * @return array|null returns the data of all existing users.
     * if it does not exist, returns null
     */
    public function getAllUsers(){

        $sql = "select * from t_user;";
        $query = $this->querySimpleExecute($sql);

        return $this->formatData($query);
    }

    /**
     * Verification of data if the user exists.
     * @param string It is used to check if the user exists with its user
     * @return array|null Returns information about the existing user, such as the user and password.
     * if not found it returns false
     */
    public function checkUser($userAttemp) {
        $users = $this->getAllUsers();
        foreach($users as $user)
        {
            if($user['username'] === $userAttemp)
            {
                return $user['username'] && $user['pass'];
            }
            else
            {
                return null;
            }
        }
    }
}