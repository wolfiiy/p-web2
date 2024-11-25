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
}
