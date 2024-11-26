<?php

include_once('DatabaseModel.php');

class ReviewModel extends DatabaseModel {
    
    /**
     * Get x latest books
     * @param int $count Number of books to get
     * @return int
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
        $sql = "insert into reviews (book_fk, user_fk, grade) VALUES (:book_fk, :user_fk, :grade)";
        $binds = array(
            ':book_fk'=> $bookFk,
            ':user_fk' => $userFk,
            ':grade' => $grade
        );
        $query = $this->queryPrepareExecute($sql, $binds);

        return;
    }
}