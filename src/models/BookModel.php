<?php

include('DatabaseModel.php');

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
}
