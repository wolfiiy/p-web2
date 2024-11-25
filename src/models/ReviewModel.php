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
        if ($req) return $this -> formatData($req)[0]["averageGrade"];
        else return false;
    }
}