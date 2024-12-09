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
     * Get latest books, with pagination and category filter
     * @param int $count Number of books to get
     * @param int $result Pagination offset
     * @param int $genre Category of books filter
     * @param string $keyword Search string, matched with book titles
     * @return array Array containing the x latest books
     */
    public function getLatestBooks(int $count, int $page = 1, int $genre = 0, string $keyword="")
    {
        if ($genre != 0){
            $query = "SELECT * from t_book WHERE title LIKE :varKeyword AND category_fk = :varCategory ORDER BY book_id DESC LIMIT :varCount OFFSET :varPage";
            $binds = array(
                "varCount" => $count,
                "varPage" => ($page-1)*$count,
                "varCategory" => $genre,
                "varKeyword" => "%" . $keyword . "%"
            );
        }
        else{
            $query = "SELECT * from t_book WHERE title LIKE :varKeyword ORDER BY book_id DESC LIMIT :varCount OFFSET :varPage";
            $binds = array(
                "varCount" => $count,
                "varPage" => ($page-1)*$count,
                "varKeyword" => "%" . $keyword . "%"
            );
        }
        $req = $this->queryPrepareExecute($query, $binds);
        
        if ($req) return $this -> formatData($req);
        else return false;
    }


    /**
     * Get the total number of books in total or in category
     * @param int $genre Category of books filter
     * @param string $keyword Search string, matched with book titles
     * @return int Number of book
     */
    public function resultCount (int $genre = 0, string $keyword=""){
        if ($genre != 0){
            $query = "SELECT COUNT(*) from t_book WHERE title LIKE :varKeyword AND category_fk = :varCategory ORDER BY book_id";
            $binds = array(
                "varCategory" => $genre,
                "varKeyword" => "%" . $keyword . "%"
            );
        }
        else{
            $query = "SELECT COUNT(*) from t_book WHERE title LIKE :varKeyword ORDER BY book_id";
            $binds = array(
                "varKeyword" => "%" . $keyword . "%"
            );
        }
        
        $req = $this->queryPrepareExecute($query, $binds);
        if ($req) return $this -> formatData($req)[0]["COUNT(*)"];
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

    
        $sql = "INSERT INTO t_book (`title`, `excerpt`, `summary`, `release_date`, `cover_image`, `number_of_pages`, `user_fk`, `category_fk`, `publisher_fk`, `author_fk`) VALUES (:title, :exerpt, :summary, :release_date, :cover_image, :number_of_pages, :user_fk, :category_fk, :publisher_fk, :author_fk)";

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

     /**
     * Get the total number of books published by a user
     * @param int $id fk_id of User
     * @return int Number of book published by user
     */
    public function countUserPublishedBookById($id)
    {
        $sql = "select count(*) from t_book where user_fk = :user_fk;";
        $binds = array(':user_fk' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query);
    }

     /**
     * Get the total number of books reviewed by a user
     * @param int $id fk_id of User
     * @return int Number of book reviewed by user
     */
    public function countUserReviewBookById($id)
    {
        $sql = "select count(*) from review where user_fk = :user_fk;";
        $binds = array(':user_fk' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)    ;
    }

    /**
     * Get the list of books reviewed by given user
     * @param int $id User's id
     * @return array List of books revied by the user
     */ 
    public function booksReviewedByUser($id){
        $sql = "SELECT * FROM t_book b JOIN review r ON b.book_id=r.book_fk JOIN t_user u ON r.user_fk = u.user_id WHERE u.user_id = :user_fk;";
        $binds = array(':user_fk' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)    ;
    }
}
