<?php

/**
 * ETML
 * Author: Sebastien Tille
 * Date: December 3rd, 2024
 */

/**
 * Helper used to generate reusable HTML elements such as a book's preview.
 */
class HtmlWriter {
    
    /**
     * Writes the required HTML to display a book's preview.
     * @param array $book An array containing the book's details.
     */
    public static function writeBookPreview(array $book) {
        $html = "";

        $html .= '<div class="book-preview">';
        $html .= '<img src="assets/img/placeholders/cover-placeholder.png">';

        $html .= '<div class="book-preview-about">';
        $html .= $book['title'];
        $html .= $book['author_name'];
        $html .= $book['category_name'];
        $html .= '</div>';

        $html .= '<div class="book-preview-passion-lecture">';
        $html .= $book['average_rating'];
        $html .= $book['username_name'];
        $html .= '<a href="index.php?controller=book&action=detail&id=';
        $html .= $book['book_id']; 
        $html .= '">';
        $html .= 'Détails';
        $html .= '</a>';
        $html .= '</div>';

        $html .= '</div>';

        echo $html;
    }

    /**
     * Writes the required HTML to display a collection of book's previews.
     * @param array $books An array containing multiple books (arrays that 
     * contains details about the book).
     */
    public static function writeBooksPreview(array $books) {
        foreach ($books as $b) {
            self::writeBookPreview($b);
        }
    }
}