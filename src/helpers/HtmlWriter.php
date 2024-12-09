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
     * Path to the placeholder cover.
     */
    const DEFAULT_COVER_PATH = "assets/img/placeholders/cover-placeholder.png";

    /**
     * Writes the required HTML to display a book's preview.
     * @param array $book An array containing the book's details.
     * @param bool $isCompact Whether the preview should be compact.
     */
    public static function writeBookPreview(array $book, bool $isCompact) {
        $html = "";
        $detailsUrl = "index.php?controller=book&action=detail&id=" 
                    . $book['book_id'];

        if (file_exists($book['cover_image']))
            $cover = $book['cover_image'];
        else
            $cover = self::DEFAULT_COVER_PATH;

        $html .= '<div class="preview wrap-row shadow shadow-hover';

        if ($isCompact)
            $html .= ' compact';
        $html .= '"';

        $html .= 'onclick="window.location.href=\'' . $detailsUrl . '\'">';
        $html .= '<div class="cover">';
        $html .= '<img src="' . $cover . '">';
        $html .= '</div>';

        $html .= '<div class="preview-content wrap-col">';
        $html .= '<div class="preview-about">';
        $html .= '<h2>' . $book['title'] . '</h2>';
        $html .= '<p>' . $book['author_name'] . '</p>';
        $html .= '<p>' . $book['category_name'] . '</p>';
        $html .= '</div>';

        $html .= '<div class="preview-community wrap-row">';
        $html .= '<p>' . $book['average_rating'] . '</p>';
        $html .= '<p>Ajouté par ' . $book['username_name'] . '</p>';
        $html .= '<p><a href="' . $detailsUrl . '">';
        $html .= 'Détails';
        $html .= '</a></p>';
        $html .= '</div>';
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
            self::writeBookPreview($b, false);
        }
    }

    /**
     * Writes the required HTML to display a collection of book's previews.
     * @param array $books An array containing multiple books (arrays that 
     * contains details about the book).
     */
    public static function writeCompactBooksPreview(array $books) {
        foreach ($books as $b) {
            self::writeBookPreview($b, true);
        }
    }
}