<?php

    class MySQLLib{

        private static $assoc_table = ['both' => MYSQLI_BOTH, 'assoc' => MYSQLI_ASSOC, 'num' => MYSQLI_NUM];
        private $connection;

        function __construct($user="master"){
            $properties=  parse_ini_file("chorus.ini");

            $this->connection=new mysqli(
                    $properties["chorus.database.mysql.hostname"], 
                    $properties["chorus.database.mysql.user." . $user],
                    $properties["chorus.database.mysql.password." . $user],
                    $properties["chorus.database.mysql.db"],
                    $properties["chorus.database.mysql.port"]
            );
            if($this->connection->connect_errno){
                echo json_encode(["Database Connectivity Error: " . $this->connection->connect_errno]);
                die;
            }

            $my_schema = $properties["chorus.database.mysql.schema"];
            $this->connection->select_db($my_schema);
            if($this->connection->connect_errno){
                echo json_encode(["Database Connectivity Error: " . $this->connection->connect_errno]);
                die;
            }
        }

        function __destruct(){
            $this->connection->close();
        }

        function exec($command){
            $tmp = $this->connection->query($command);
            return $tmp;
        }

        function query($query){
            return $this->connection->query($query);
        }

        function fetchArray($result, $assoc = 'both'){
            if(!$result){
                return FALSE;
            }else{
                return $result->fetch_array(MySQLLib::$assoc_table[$assoc]);
            }

        }

        function getColumnList($tblName){
            $res = $this->query("DESCRIBE " . $tblName);
            $colNames = fetchArray($res, 'assoc')['Field'];
            return $colNames;
        }

        function getTableList(){
            $result = $this->connection->query('SHOW TABLES');
            $tblNames = [];
            while($res = $this->fetchArray($result)){
                array_push($tblNames, $res[0]);
            }
            return $tblNames;
        }

        function getLastInsertedID(){
            if(TRUE){
                return mysqli_insert_id($this->connection);
            }
        }
        
        function getMaxCol($tblName, $colName){
            if(is_string($tblName) && is_string($colName)){
                return $this->fetchArray($this->query('SELECT MAX(' . $colName . ') FROM ' . $tblName))[0];
            }
        }


    }




?>


