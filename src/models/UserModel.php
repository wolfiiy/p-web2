<?php

/**
 * Authors: Valentin Pignat, Sébastien Tille, Abigaël Périsset
 * Date: December 17, 2024
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
     * Sets the user rating of a specific book.
     * @param int $bookId Unique ID of the book.
     * @param int $userId Unique ID of the user.
     * @param mixed $rating Rating given by the user.
     */
    public function setBookRating(int $bookId, int $userId, mixed $rating) {
        $currentReview = $this->getBookRating($bookId, $userId);
        if ($currentReview){
            $sql = "
            update review set grade=:rating where book_fk=:book_fk AND user_fk=:user_fk
        ";
        }
        else{
            $sql = "
            insert into review (book_fk, user_fk, grade)
            values (:book_fk, :user_fk, :rating)
        ";
        }
        
        $binds = array(
            ':book_fk' => $bookId,
            ':user_fk' => $userId,
            ':rating' => $rating
        );
        $this->queryPrepareExecute($sql, $binds);
    }

    /**
     * Gets the rating submitted by the user on a specific book.
     * @param int $bookId Unique ID of the book.
     * @param int $userId Unique ID of the user.
     * @return int The rating the user gave to the book.
     */
    public function getBookRating(int $bookId, int $userId) {
        $sql = "
            select * 
            from review 
            where book_fk = :book_fk
            and user_fk = :user_fk
        ";

        $binds = array(
            ':book_fk' => $bookId,
            ':user_fk' => $userId
        );

        $query = $this->queryPrepareExecute($sql, $binds);

        if ($query) {
            $results = $this->formatData($query);
            if (isset($results[0])){
                return $results[0]["grade"];
            }
        }
        else {
            return 0;
        }
    }

    /**
     * Given a username, will attempt to get the corresponding user from the 
     * database. This method is safe to use with user-provided inputs.
     * @param string $username The username.
     * @return array|null An array that contains information about the 
     * user (username, account creation date) if it has been found and false
     * otherwise.
     */
    public function getUserByUsername(string $username) {
        $sql = "select * from t_user where username = :username";
        $binds = array('username' => $username);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)[0];
    }

    /**
     * Verification of data if the user exists.
     * @param string $userAttemp used to find the requested user
     * @return array|null Returns information about the existing user, such as the user and password.
     * if not found it returns null
     */
    public function checkUser($userAttemp) {
        $users = $this->getAllUsers();
        foreach($users as $user)
        {
            if($user['username'] === $userAttemp)
            {
                $credentials['user_id'] = $user['user_id'];
                $credentials['username'] = $user['username'];
                $credentials['password'] = $user['password'];
                $credentials['sign_up_date'] = $user['sign_up_date'];
                $credentials['is_admin'] = $user['is_admin'];
                return $credentials;
            }
        }
        return null;
    }

    /**
     * Checks whether a user exists.
     * @param string $username Username to check.
     * @return true|false True if a user with the given username exists, 
     * false otherwise.
     */
    public function userExists(string $username) {
        $sql = "
            select 1 from t_user 
            where lower(username) = lower(:username)
        ";

        $binds = array(':username' => $username);
        $query = $this->queryPrepareExecute($sql, $binds);
        $query = $this->formatData($query);

        return $query != null ? true : false;
    }

    /**
     * Creates a new user account.
     * @param string $username The username.
     * @param string $hash The hashed password.
     */
    public function createAccount(string $username, string $hash) {
        $today = date('Y-m-d');
    
        $sql = "
            insert into t_user (username, password, sign_up_date) 
            values (:username, :hash, :sign_up_date)
        ";
        
        $binds = array(
            'username' => $username,
            'hash' => $hash,
            'sign_up_date' => $today
        );
    
        $this->queryPrepareExecute($sql, $binds);
    }
}