<?php

/** 
 * Authors: Abigaël Périsset, Sébasten Tille, Valentin Pignat
 * Date: November 19th, 2024
 */

include_once('DatabaseModel.php');

/**
 * This class holds database queries relative to books.
 */
class BookModel extends DatabaseModel {

    /**
     * Gets all books.
     * @return array An array containing all books.
     */
    public function getAllBooks() {
        $query = "SELECT * from t_book";
        $req = $this->querySimpleExecute($query);

        if ($req) return $this -> formatData($req);
        else return false;
    }

    /**
     * Gets the latest book added by a user given an ID.
     * @param int $idUser The unique ID of the user.
     * @return int The latest book from that user.
     */
    public function getIdBook($idUser) {
        $query = "SELECT MAX(book_id) FROM t_book WHERE user_fk = :user_fk";
        $binds = array("user_fk" => $idUser);

        $req = $this->queryPrepareExecute($query, $binds);
        $req = $this->formatData($req);

        return $req[0]['MAX(book_id)'];
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
     * Gets the latest books, with pagination and category filter applied.
     * @param int $count The number of books to get.
     * @param int $result The pagination offset.
     * @param int $genre The category of books to filter.
     * @param string $keyword Search string, matched with book title or author 
     * first and last names.
     * @return array|false An array containing the x latest books, false if it
     * could not be fetched.
     */
    public function getLatestBooks(int $count, int $page = 1, int $genre = 0, 
        string $keyword="") {
        $keyword = trim($keyword);
        
        if ($genre != 0) {
            // Modified with ChatGPT
            $query = "
                SELECT 
                    b.* 
                FROM 
                    t_book b
                JOIN 
                    t_author a ON b.author_fk = a.author_id
                WHERE 
                    (b.title LIKE :varKeyword OR CONCAT(a.first_name, ' ', a.last_name) LIKE :varKeyword) 
                    AND b.category_fk = :varCategory
                ORDER BY 
                    b.book_id DESC 
                LIMIT 
                    :varCount OFFSET :varPage";
        
            $binds = array(
                "varCount" => $count,
                "varPage" => ($page - 1) * $count,
                "varCategory" => $genre,
                "varKeyword" => "%" . $keyword . "%"
            );
        } else {
            // Modified with ChatGPT
            $query = "
                SELECT 
                    b.* 
                FROM 
                    t_book b
                JOIN 
                    t_author a ON b.author_fk = a.author_id
                WHERE 
                    b.title LIKE :varKeyword OR CONCAT(a.first_name, ' ', a.last_name) LIKE :varKeyword
                ORDER BY 
                    b.book_id DESC 
                LIMIT 
                    :varCount OFFSET :varPage";
        
            $binds = array(
                "varCount" => $count,
                "varPage" => ($page - 1) * $count,
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
    public function resultCount (int $genre = 0, string $keyword="") {
        if ($genre != 0){
            $query = "
                SELECT COUNT(*) 
                from t_book 
                WHERE title 
                LIKE :varKeyword AND category_fk = :varCategory 
                ORDER BY book_id
            ";

            $binds = array(
                "varCategory" => $genre,
                "varKeyword" => "%" . $keyword . "%"
            );
        } else {
            $query = "
                SELECT COUNT(*) 
                from t_book 
                WHERE title 
                LIKE :varKeyword 
                ORDER BY book_id
            ";

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
    public function insertBook(string $title, string $excerpt, string $summary, 
        string $releaseDate, string $coverImage, int $numberOfPages, 
        int $userFk, int $categoryFk, int $publisherFk, int $authorFk) {
        
        $sql = "
            INSERT INTO t_book (`title`, `excerpt`, `summary`, `release_date`, `cover_image`, `number_of_pages`, `user_fk`, `category_fk`, `publisher_fk`, `author_fk`) 
            VALUES (:title, :excerpt, :summary, :release_date, :cover_image, :number_of_pages, :user_fk, :category_fk, :publisher_fk, :author_fk)
        ";

        $binds = array(
            'title'=> $title,
            'excerpt' => $excerpt,
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
     * Update a book in the database
     * @param int $bookId Book's ID
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
    public function updateBook(int $bookId, string $title, string $excerpt, 
        string $summary, string $releaseDate, string $coverImage, 
        int $numberOfPages, int $categoryFk, int $publisherFk, int $authorFk) {
        
        if ($coverImage != ""){
            $sql = "
                UPDATE t_book 
                SET title = :title, excerpt = :excerpt, summary = :summary, release_date = :releaseDate, cover_image = :coverImage, number_of_pages = :numberOfPages, category_fk = :categoryFk, publisher_fk = :publisherFk, author_fk = :authorFk 
                WHERE book_id = :book_id
            ";

            $binds = array(
                'title'=> $title,
                'excerpt' => $excerpt,
                'summary' => $summary,
                'releaseDate' => $releaseDate,
                'coverImage' => $coverImage,
                'numberOfPages' => $numberOfPages,
                'categoryFk' => $categoryFk, 
                'publisherFk' => $publisherFk, 
                'authorFk' => $authorFk,
                'book_id' => $bookId,
            );
        } else {
            $sql = "
                UPDATE t_book 
                SET title = :title, excerpt = :excerpt, summary = :summary, release_date = :releaseDate, number_of_pages = :numberOfPages, category_fk = :categoryFk, publisher_fk = :publisherFk, author_fk = :authorFk 
                WHERE book_id = :book_id
            ";

            $binds = array(
                'title'=> $title,
                'excerpt' => $excerpt,
                'summary' => $summary,
                'releaseDate' => $releaseDate,
                'numberOfPages' => $numberOfPages,
                'categoryFk' => $categoryFk, 
                'publisherFk' => $publisherFk, 
                'authorFk' => $authorFk,
                'book_id' => $bookId,
            );
        }

        $this->queryPrepareExecute($sql, $binds);
        header("Location: index.php?controller=book&action=detail&id=" . $bookId);
    }

     /**
     * Get the total number of books published by a user.
     * @param int $id fk_id of user.
     * @return int The number of book published by user.
     */
    public function countUserPublishedBookById($id) {
        $sql = "select count(*) from t_book where user_fk = :user_fk;";
        $binds = array(':user_fk' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query);
    }

    /**
     * Get the books published by use with given id
     * @param int $id user id
     * @return array Books published by the user
     */
    public function userPublishedBookByID($id) {
        $sql = "SELECT * from t_book where user_fk = :user_fk";
        $binds = array(':user_fk' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query);
    }

     /**
     * Get the total number of books reviewed by a user
     * @param int $id fk_id of User
     * @return int Number of book reviewed by user
     */
    public function countUserReviewBookById($id) {
        $sql = "select count(*) from review where user_fk = :user_fk;";
        $binds = array(':user_fk' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)    ;
    }

    /**
     * Get the list of books reviewed by given user
     * @param int $id The unique ID of a user.
     * @return array The list of books reviewed by that user.
     */ 
    public function booksReviewedByUser($id) {
        $sql = "
            SELECT *, b.user_fk AS user_fk, r.user_fk AS review_user_fk 
            FROM t_book b JOIN review r ON b.book_id=r.book_fk JOIN t_user u 
            ON r.user_fk = u.user_id WHERE u.user_id = :user_fk;
        ";

        $binds = array(':user_fk' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)    ;
    }

    /**
     * Delete book with given ID
     * @param int $id Book's id
     * @return null Nothing.
     */
    public function deleteBook($id) {
        $sql = "
            DELETE FROM t_book 
            WHERE book_id = :book_id
        ";

        $binds = array("book_id" => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return;
    }
}
