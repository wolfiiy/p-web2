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
            // Get the average rating or use a placeholder text
            $bookRating = $reviewModel->getAverageRating($book["book_id"]);
            
            if (!is_null($bookRating)) 
                $book["average_rating"] = $bookRating;
            else 
                $book["average_rating"] = Constants::NO_RATING;

            // Get the author's full name
            $author = $authorModel->getAuthorById($book["author_fk"]);
            $book["author_name"] = $author['first_name'] . " " . $author['last_name'];

            // Get category name
            $category = $categoryModel->getCateoryById($book["category_fk"]);
            $book["category_name"] = $category["name"];

            // Get user who added the book
            $user = $userModel->getUserById($book["user_fk"]);
            $book["username_name"] = $user["username"];
        }

        return $books;
    }
}