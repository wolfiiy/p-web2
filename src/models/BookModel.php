<?php

include_once('DatabaseModel.php');

class BookModel extends DatabaseModel
{
    /**
     * Get all books 
     * @return array
     */
    public function getAllBooks()
    {
        $query = "SELECT * from t_book";
        $req = $this->querySimpleExecute($query);
        if ($req) return $this -> formatData($req);
        else return false;
    }

    /**
     * Get x latest books
     * @param int $count Number of books to get
     * @return array
     */
    public function getLatestBooks(int $count)
    {
        $query = "SELECT * from t_book ORDER BY book_id DESC LIMIT :varCount";
        $binds = array("varCount" => $count);
        $req = $this->queryPrepareExecute($query, $binds);
        if ($req) return $this -> formatData($req);
        else return false;
    }


}
