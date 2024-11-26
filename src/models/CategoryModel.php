<?php

include_once('DatabaseModel.php');

class CategoryModel extends DatabaseModel
{
    

    public function getAllCategory(){
        $query = "SELECT * FROM t_category";
        $req = $this->querySimpleExecute($query);
        if ($req) return $this -> formatData($req);
        else return false;
    }

    public function getCateoryById(int $id){
        $sql = "select * from t_category where category_id = :category_id";
        $binds = array(':category_id' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)[0];
    }
    
     /**
     * Insert a new author in the database
     * @param string $firstName Author's first name
     * @param string $lastName Author's last name
     */
    public function insertAuthor(string $firstName, string $lastName){
        $sql = "insert into t_author (first_name, last_name) VALUES (:first_name, :last_name)";
        $binds = array(
            ':first_name'=> $firstName,
            ':last_name' => $lastName,
        );
        $query = $this->queryPrepareExecute($sql, $binds);

        return;
    }
}
