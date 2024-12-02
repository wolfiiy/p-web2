<?php

/**
 * Author: Sébastien Tille
 * Date: November 25th, 2024
 */

include_once('DatabaseModel.php');

/**
 * This class holds database querries relative to publishers.
 */
class PublisherModel extends DatabaseModel {

    /**
     * Given an ID, will attempt to get the corresponding publisher from the 
     * database. This method is safe to use with user-provided inputs.
     * @param int $id Unique identifier of the publisher. Used as a foreign key
     * in the t_book table.
     * @return array|null An array that contains information about the 
     * publisher (name) if it has been found and false otherwise.
     */
    public function getPublisherById(int $id) {
        $sql = "select * from t_publisher where publisher_id = :publisher_id";
        $binds = array(':publisher_id' => $id);
        $query = $this->queryPrepareExecute($sql, $binds);

        return $this->formatData($query)[0];
    }

    /**
     * Insert a new publisher in the database
     * @param string $name Publisher's name
     */
    public function insertAuthor(string $name){
        $sql = "insert into t_publisher (name) VALUES (:name)";
        $binds = array(':name'=> $name);
        $query = $this->queryPrepareExecute($sql, $binds);

        return;
    }

    public function getPublisherByName(string $namePublisher){
        $sql = "SELECT publisher_id FROM t_publisher WHERE  name = :name";
        $binds = array(":name" => $namePublisher);
        $query = $this->queryPrepareExecute($sql, $binds);

        if(empty($query)){
            return 0;
        }
        else{
        return $this->formatData($query)[0];
        }
    }

}