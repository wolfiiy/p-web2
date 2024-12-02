<?php

/**
 * ETML
 * Authors: Valentin Pignat, Sébastien Tille
 * Date: November 26th, 2024
 */

include_once('../models/ReviewModel.php');
include_once('../models/AuthorModel.php');
include_once('../models/CategoryModel.php');
include_once('../models/UserModel.php');

/**
 * Helper class used to complete and format data.
 */
class DataHelper {
    
    /**
     * Gets all details regarding a specific book.
     * @param mixed $book The book to get details from.
     */
    static function getOneBookDetails($book) {
        $reviewModel = new ReviewModel();
        $authorModel = new AuthorModel();
        $categoryModel = new CategoryModel();
        $userModel = new UserModel();

        $book = self::fillDetails(
            $book, $authorModel, $categoryModel, $reviewModel, $userModel
        );

        return $book;
    }

    /**
     * Gets all details regarding an array of books.
     * @param array $books An array of books.
     * @return array The same array of books with additionnal details.
     */
    static function BookPreview($books){
        $reviewModel = new ReviewModel();
        $authorModel = new AuthorModel();
        $categoryModel = new CategoryModel();
        $userModel = new UserModel();

        foreach ($books as &$book){
            $book = self::fillDetails(
                $book, $authorModel, $categoryModel, $reviewModel, $userModel
            );
        }

        return $books;
    }

    /**
     * Fills a book with its details.
     * @param TODO $book Book to be filled.
     * @param AuthorModel $authorModel Author model used to get details about 
     * the author of the book.
     * @param CategoryModel $categoryModel Cateogry model used to get details
     * about the book's category.
     * @param ReviewModel $reviewModel Review model to be used to get details
     * about reviews posted on the book.
     * @param UserModel $userModel User model to be used to get details about
     * the user who added the book to the database.
     * @return TODO The book filled with its details.
     */
    private static function fillDetails($book, AuthorModel $authorModel, 
        CategoryModel $categoryModel, ReviewModel $reviewModel,
        UserModel $userModel) {
        // Get the average rating or use a placeholder text
        $bookRating = $reviewModel->getAverageRating($book["book_id"]);
                    
        if (!is_null($bookRating)) 
            $book["average_rating"] = $bookRating;
        else 
            $book["average_rating"] = Constants::NO_RATING;

        // Convert date to printable format
        $book['release_date'] = FormatHelper::getFullDate($book['release_date']);

        // Get the author's full name
        $author = $authorModel->getAuthorById($book["author_fk"]);
        $book["author_name"] = $author['first_name'] . " " . $author['last_name'];

        // Get category name
        $category = $categoryModel->getCateoryById($book["category_fk"]);
        $book["category_name"] = $category["name"];

        // Get user who added the book
        $user = $userModel->getUserById($book["user_fk"]);
        $book["username_name"] = $user["username"];

        return $book;
    }
}