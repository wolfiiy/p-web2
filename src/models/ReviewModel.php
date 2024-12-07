<?php

/**
 * Author: Valentin Pignat, Sébastien Tille
 * Date: Date: November 25th, 2024
 */

include_once('DatabaseModel.php');

/**
 * This class holds database queries relative to reviews.
 */
class ReviewModel extends DatabaseModel {
    
    /**
     * Get the average rating for a book
     * @param int $count Id of the rated book
     * @return int Average rating for the book
     */
    public function getAverageRating(int $bookId)
    {
        $query = "SELECT AVG(grade) AS averageGrade from review where book_fk = :varBookId";
        $binds = array("varBookId" => $bookId);
        $req = $this->queryPrepareExecute($query, $binds);
        // var_dump($this->formatData($req));
        if ($req) return $this -> formatData($req)[0]["averageGrade"];
        else return false;
    }

    /**
     * Insert a new review in the database
     * @param int $bookFk Id of the book receiving the review
     * @param int $userFk Id of the user rating
     * @param int $grade Grade (1-5) given to the book by the user
     */
    public function insertReview(int $bookFk, int $userFk, int $grade){
        $sql = "INSERT into reviews (book_fk, user_fk, grade) VALUES (:book_fk, :user_fk, :grade)";
        $binds = array(
            'book_fk'=> $bookFk,
            'user_fk' => $userFk,
            'grade' => $grade
        );
        $query = $this->queryPrepareExecute($sql, $binds);

        return;
    }
}