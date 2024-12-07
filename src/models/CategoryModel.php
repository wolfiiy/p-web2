<?php

/** 
 * Authors: Abigaël Périsset, Sébasten Tille, Valentin Pignat
 * Date: November 25th, 2024
 */

include_once('DatabaseModel.php');


/**
 * This class holds database queries relative to categories.
 */
class CategoryModel extends DatabaseModel
{
    
    /**
     * Get all categories
     * @return array Array containing every category
     */
    public function getAllCategory(){
        $query = "SELECT * FROM t_category";
        $req = $this->querySimpleExecute($query);
        if ($req) return $this -> formatData($req);
        else return false;
    }

    /**
     * Given an ID, gets the coresponding category or returns null if it does not
     * exists.
     * @param int $id Unique ID of the category.
     * @return array|null An array that contains the category's details if found,
     * false otherwise.
     */
    public function getCateoryById(int $id){
        $sql = "SELECT * from t_category where category_id = :category_id";
        $binds = array('category_id' => $id);
        $req = $this->queryPrepareExecute($sql, $binds);

        if ($req) return $this->formatData($req)[0];
        else return false;
    }
    
}
