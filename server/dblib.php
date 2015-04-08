<?php

include_once 'sqllib.php';

//Utility Library for SQL
class MyDB extends SQLLib {

    function __construct() {
        parent::__construct();
    }

    /**
     * 
     * @param string $name as table name
     * @param string array $colArray as array of all columns
     * 
     * Creates new table in database. Make $colArray in the format of [colName => colType]
     * 
     */
    function createTable($name, $colArray) {
        //Check type
        $isOk = 1;
        if (is_string($name) && gettype($colArray) == 'array') {
            foreach ($colArray as $key => $value) {
                if (!(is_string($key) || is_string($value))) {
                    $isOk = 0;
                }
            }
        } else {
            $isOk = 0;
        }

        if ($isOk) {
            $sqlStr = 'CREATE TABLE ' . $name . ' (';
            $iter = 0;
            foreach ($colArray as $colName => $colType) {
                if ($iter < count($colArray) - 1) {
                    $sqlStr = $sqlStr . $colName . ' ' . $colType . ', ';
                } else {
                    $sqlStr = $sqlStr . $colName . ' ' . $colType . ')';
                }
                $iter = $iter + 1;
            }
            $this->exec($sqlStr);
        }
    }

    /**
     * 
     * @param string $name as table name
     * 
     * Drops table with $name
     */
    function dropTable($name) {

        if (is_string($name)) {
            if (!strpos($name, ';')) {     //Checks for getting hacked !!!TODO!!! Write dontgethacked.php
                $this->exec('DROP TABLE IF EXISTS ' . $name);
            }
        }
    }

    /**
     * 
     * @param string $tblName as table name (table to be indexed)
     * @param string $colName as column name (column to be indexed by)
     * @param string $indexName as index name (new index)
     * 
     * 
     */
    function createIndex($tblName, $colName, $indexName) {
        if (is_string($tblName) && is_string($colName)) {
            $this->exec('CREATE INDEX ' . $indexName . ' ON ' . $tblName . ' (' . $colName . ')');
        }
    }

    /**
     * Drops all tables in database.
     */
    function dropAllTables() {
        $tblNames = $this->getTableList();
        foreach ($tblNames as $name) {
            $this->exec('DROP TABLE ' . $name);
        }
    }

    /**
     * 
     * @param mixed array $dataArr as array of all input data into entry as [colName => dataValue]
     * @param string $tblName as table name
     */
    function addEntry($dataArr, $tblName) {  //!!!TODO!!! Hacking check???
        //if(is_string($tblName)){
        $sqlStr = 'INSERT INTO ' . $tblName . ' (';
        foreach ($dataArr as $colName => $element) {
            $sqlStr = $sqlStr . $colName . ', ';
        }
        $sqlStr = substr($sqlStr, 0, -2) . ') VALUES (';

        foreach ($dataArr as $colName => $element) {
            if ($element === NULL) {
                $sqlStr = $sqlStr . 'NULL' . ', ';
            } elseif (is_integer($element) || is_float($element) || is_bool($element)) {
                if ($element === TRUE) {
                    $sqlStr = $sqlStr . 'TRUE' . ', ';
                } elseif ($element === FALSE) {
                    $sqlStr = $sqlStr . 'FALSE' . ', ';
                } else {
                    $sqlStr = $sqlStr . $element . ', ';
                }
            } else {
                $sqlStr = $sqlStr . '"' . $element . '", ';
            }
        }
        $sqlStr = substr($sqlStr, 0, -2);
        $sqlStr = $sqlStr . ')';
        #echo $sqlStr . "\n";
        $this->exec($sqlStr);
        //}
    }

    /**
     * 
     * @param int $id id of row
     * @param string $tblName as name of table
     * 
     * Deletes entry (id) in tblName
     */
    function deleteEntry($id, $tblName) {

        $this->query('DELETE FROM ' . $tblName . ' WHERE id=' . $id);
    }

    /**
     * 
     * @param string $tblName as table name
     * @param string $colName as column name
     * @param integer $id as id of cell to be incremented
     */
    function incrementCell($tblName, $colName, $id) {
        #echo 'UPDATE ' . $tblName . ' SET ' . $colName . '=' . $colName . '+1' . ' WHERE id=' . $id;
        $this->query('UPDATE ' . $tblName . ' SET ' . $colName . '=' . $colName . '+1' . ' WHERE id=' . $id);
    }

    /**
     * 
     * @param string $tblName as table name
     * @param string $colName as column name
     * @param integer $id as id of cell to be decremented
     */
    function decrementCell($tblName, $colName, $id) {
        #echo 'UPDATE ' . $tblName . ' SET ' . $colName . '=' . $colName . '-1' . ' WHERE id=' . $id;
        $this->query('UPDATE ' . $tblName . ' SET ' . $colName . '=' . $colName . '-1' . ' WHERE id=' . $id);
    }

    /**
     * 
     * @param string $filepath as filepath of csv
     * @param string $tblName as table name for destination
     * @throws Exception
     * 
     * 
     * For importing CSVs into the database
     */
    function importCSV($filepath, $tblName) {                //turns csv file into COLUMN=>VALUE then does addEntry() into SQL database
        $file = fopen($filepath, "r");
        $cols = $this->getColumnList($tblName);
        while ($line_arr = fgetcsv($file)) {
            $param_arr = [];
            if (count($cols) == count($line_arr)) {
                for ($i = 1; $i < count($line_arr); $i++) {
                    $param_arr[$cols[$i]] = $line_arr[$i];
                }
                $this->addEntry($param_arr, $tblName);
            } else {
                throw new Exception("ERROR: columns don't match up: " . $tblName);
            }
        }
        fclose($file);
    }

    /**
     * 
     * @param string $tbl_name as table name
     * @param string $col as column name
     * @param integer $row as row id
     * @param mixed $new_val as new value
     */
    function changeCellWithRow($tbl_name, $col, $row, $new_val) {
        if (is_string($tbl_name) && is_string($col) && is_integer($row)) {
            $sqlStr = 'UPDATE ' . $tbl_name . ' SET ' . $col . '=' . $new_val . ' WHERE id=' . $row;
            #echo $sqlStr . "\n";
            $this->query($sqlStr);
        }
    }

    /**
     * 
     * @param string $username
     * @return boolean is username unique
     */
    function checkForDuplicatesUser($username) {

        $result = $this->query('SELECT * FROM users WHERE username=' . $username);

        return ($result == FALSE);
    }

    /**
     * 
     * @param string $groupname
     * @return boolean is groupname unique
     */
    function checkForDuplicatesGroup($groupname) {

        $result = $this->query('SELECT * FROM groups WHERE groupname=' . $groupname);

        return ($result == FALSE);
    }

    /**
     * 
     * @param int $user_id as the id of the user.
     * @return string $username as the name of the user.
     */
    function getUsername($user_id) {
        $result = $this->query('SELECT username FROM users WHERE id=' . $user_id);
        $username = $this->fetchArray($result)[0];
        return $username;
    }

    /**
     * 
     * @param string $username as username
     * @return integer id of user
     */
    function getUserId($username) {
        if (is_string($username)) {
            $result = $this->query('SELECT id FROM users WHERE username="' . $username . '"');
            return $this->fetchArray($result)[0];
        }
    }

    /**
     * 
     * @param int $group_id as the id of the group.
     * @return string $groupname as the name of the group.
     */
    function getGroupname($group_id) {
        $result = $this->query('SELECT groupname FROM groups WHERE id=' . $group_id);
        $groupname = $this->fetchArray($result)[0];
        return $groupname;
    }

    /**
     * 
     * @param string $groupname as groupname
     * @return integer id of group
     */
    function getGroupId($groupname) {
        if (is_string($groupname)) {
            $result = $this->query('SELECT id FROM groups WHERE groupname="' . $groupname . '"');
            return $this->fetchArray($result)[0];
        }
    }
ƒ
    /**
     *
     * @param string $name as user name
     * @param string $classes as comma seperated list of classes (hardcode a number of cols?)
     *
     */
    function createUser($name, $classes) {
        // maybe hardcode the max number of classes? can we make a query-able cell?
        // TODO: make all data into an array + fill in what isn't there then feed it to createIndex
        $to_add = array('username' => $name, 'class' => $classes, 'rating' => 0);
        addEntry($to_add, 'users');
    }

    /**
     *
     * @param string $name as the name of the group
     * @param int $user_id as person starting the group
     * @param string $description as group description
     * @param string $filter_settings as class to filter by
     */
    function createGroup($name, $user_id, $description, $filterSettings){
        // TODO: let it filter by other things 
        $to_add = array('groupname' => $name, 'description' => $description, 
                            'class' => $filterSettings);
        addEntry($to_add, 'groups');

        // get group ID so we can add group to user_in_group
        $group_id = getGroupId($name);
        $group_user_pair = array('user_id' => $user_id, 'group_id' => $group_id);
        addEntry($group_user_pair, 'user_in_group');
    }

    /**
     *
     * @param int $user_id name of user to remove
     *
     */
    function removeUser($user_id){
        // TODO: check that the user is in the table, check if its in any groups
        // leave this for now, we can just use drop table

    }

    /** 
     *
     * @param string $groupname name of group to remove
     */
    function removeGroup($group_id){
        // TODO: check that the group is in the table, remove if we can
        // if users hold group, then we will need to remove them from the group
        // make sure to remove everything from user in group

        deleteEntry($group_id, 'groups');
        
    }

    /**
     *
     * @param int $user_id
     * @return int rating of $user_id
     */
    function getUserRating($user_id){
        $result = $this->query('SELECT rating FROM users WHERE id=' . $user_id);
        $rating = $this->fetchArray($results)[0];
        return $rating;
    }

    /**
     *
     * @param int $user_id 
     * @param int $rating   
     */
    function setUserRating($user_id, $rating){
        changeCellWithRow('users', 'rating', $user_id, $rating);
    }

    /**
     *
     * @param int $user_id
     * @return string comma seperated list of user classes
     */
    function getUserClasses($user_id){
        $result = $this->query('SELECT classes FROM users WHERE id=' . $user_id);
        $classes = $this->fetchArray($results)[0];
        return $classes;
    }

    /**
     * 
     * @param int $group_id
     * @return array of people in the group
     */
    function getGroupMembers($group_id){
        // get this info from the UserInGroup table
        // do we need to create another get function to get user
        $result = $this->query('SELECT user_id FROM user_in_group WHERE group_id=' . $group_id);
        $members = $this->fetchArray($result);
        return $members;
    }

    /**
     *
     * @param int $user_id
     */
    function removeUserFromGroup($user_id, $group_id){
        $result = $this->query('SELECT id FROM user_in_group WHERE user_id=' . $user_id . 
                    ' AND group_id=' . $group_id);
        $id = $this->fetchArray($results)[0];
        deleteEntry($id, 'user_in_group');
    }

    /**
     *
     * @param int $user_id 
     * @param int $group_id
     */
    function addUserToGroup($user_id, $group_id){
        $group_user_pair = array('user_id' => $user_id, 'group_id' => $group_id);
        addEntry($group_user_pair, 'user_in_group');
    }


   
    
}

?>