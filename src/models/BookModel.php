<?php

include_once('DatabaseModel.php');

class BookModel extends DatabaseModel
{
    /**
     * Summary of getAllBooks
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
     * Given an ID, gets the coresponding book or returns null if it does not
     * exists.
     * @param int $id Unique ID of the book.
     * @return array|null An array that contains the book's details if found,
     * false otherwise.
     */
    public function getBookById(int $id) {
        $sql = "select * from t_book where book_id = $id";
        $req = $this->querySimpleExecute($sql);
        if ($req) return $this -> formatData($req);
        else return false;
    }
}
