<?php

/**
 * Authors: Valentin Pignat, Sébastien Tille
 * Date: November 25th, 2024
 */

include_once('DatabaseModel.php');

/**
 * This class holds database queries relative to reviews.
 */
class ReviewModel extends DatabaseModel {
    
    /**
     * Get the average rating for a book
     * @param int $bookId The unique ID of a book.
     * @return int|false The average grade for that book or false if it could
     * not be found.
     */
    public function getAverageRating(int $bookId) {
        $query = "
            select avg(grade) 
            as averageGrade 
            from review 
            where book_fk = :varBookId
        ";

        $binds = array("varBookId" => $bookId);
        $req = $this->queryPrepareExecute($query, $binds);

        if ($req) return $this -> formatData($req)[0]["averageGrade"];
        else return false;
    }

    /**
     * Gets the number of ratings for a specific book.
     * @param int $count Unique ID of a book.
     * @return int|false The number of ratings given to that book or false if it
     * could not be found.
     */
    public function getNumberRating(int $bookId) {
        $query = "
            select count(*) 
            as nb_rating 
            from review 
            where book_fk = :varBookId
        ";

        $binds = array("varBookId" => $bookId);
        $req = $this->queryPrepareExecute($query, $binds);

        if ($req) return $this -> formatData($req)[0]["nb_rating"];
        else return false;
    }

    /**
     * Insert a new review in the database
     * @param int $bookFk Id of the book receiving the review
     * @param int $userFk Id of the user rating
     * @param int $grade Grade (1-5) given to the book by the user
     * @return PDOStatement|false The querried data or false if it could not be
     * fetched.
     */
    public function insertReview(int $bookFk, int $userFk, int $grade) {
        $sql = "
            insert into reviews (book_fk, user_fk, grade) 
            values (:book_fk, :user_fk, :grade)
        ";

        $binds = array(
            'book_fk'=> $bookFk,
            'user_fk' => $userFk,
            'grade' => $grade
        );
        
        return $query = $this->queryPrepareExecute($sql, $binds);
    }
}