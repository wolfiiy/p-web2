<?php

include('DatabaseModel.php');

Class UserModel extends DatabaseModel {
    
    public function getDetailUser($id)
    {    
        $req = $this->querySimpleExecute("SELECT * FROM t_user WHERE user_id = $id");
        $detail = $this->formatData($req);
        return $detail;
    }
}