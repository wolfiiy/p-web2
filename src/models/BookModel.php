<?php

/** 
 * Authors: Abigaël Périsset, Sébasten Tille, Valentin Pignat
 * Date: November 19th, 2024
 */

include_once('DatabaseModel.php');

/**
 * This class holds database queries relative to books.
 */
class BookModel extends DatabaseModel
{
    /**
     * Get all books 
     * @return array Array containing every book
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
        $sql = "SELECT * from t_book where book_id = :book_id";
        $binds = array('book_id' => $id);
        $req = $this->queryPrepareExecute($sql, $binds);

        if ($req) return $this -> formatData($req)[0];
        else return false;
    }

    /**
     * Get x latest books
     * @param int $count Number of books to get
     * @return array Array containing the x latest books
     */
    public function getLatestBooks(int $count)
    {
        $query = "SELECT * from t_book ORDER BY book_id DESC LIMIT :varCount";
        $binds = array("varCount" => $count);
        $req = $this->queryPrepareExecute($query, $binds);
        if ($req) return $this -> formatData($req);
        else return false;
    }

     /**
     * Insert a new book in the database
     * @param string $title Book's title
     * @param string $exerpt Book's exerpt, link to a PDF
     * @param string $summary Book's summary
     * @param string $releaseDate Book's release date
     * @param string $coverImage Book's cover, link to the local ressource
     * @param int $userFk Id of the user who added this book
     * @param int $categoryFk Id of the category the book belongs to
     * @param int $publisherFk Id of the book's publisher
     * @param int $authorFk Id of the book's author
     */
    public function insertBook(string $title, string $exerpt, string $summary, string $releaseDate, string $coverImage, int $numberOfPages, int $userFk, int $categoryFk, int $publisherFk, int $authorFk){
        $sql = "INSERT INTO t_book (title, excerpt, summary, release_date, cover_image, number_of_pages, user_fk, category_fk, publisher_fk, author_fk) VALUES (:title, :exerpt, :summary, :release_date, :cover_image, :number_of_pages, :user_fk, :category_fk, :publisher_fk, :author_fk)";
        $binds = array(
            'title'=> $title,
            'exerpt' => $exerpt,
            'summary' => $summary,
            'release_date' => $releaseDate,
            'cover_image' => $coverImage,
            'number_of_pages' => $numberOfPages,
            'user_fk' => $userFk,
            'category_fk' => $categoryFk, 
            'publisher_fk' => $publisherFk, 
            'author_fk' => $authorFk,
        );
        $this->queryPrepareExecute($sql, $binds);
    }
}
