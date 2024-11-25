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
        if ($req) return $this -> formatData($req)[0];
        else return false;
    }

    /**
     * Given an ID, gets the corresponding author or returns false if it could
     * not be found.
     * @param int $id Unique ID of the author.
     * @return array|null An array that contains the author's details if found,
     * false otherwise.
     */
    public function getAuthorById(int $id) {
        $sql = <<< SQL
            select
                *
            from
                t_author
            where
                author_id = :author_id;
        SQL;

        $binds = array(
            ':author_id' => $id
        );

        $req = $this->queryPrepareExecute($sql, $binds);
        return $this->formatData($req)[0];
    }
}
