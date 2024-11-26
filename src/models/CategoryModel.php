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
}
