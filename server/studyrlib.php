<?php

include_once 'dblib.php';

// library holding all the Studyr related functions
// TODO: add user/group --> json and json --> user/group
class Studyr {

    private $myDB;

	function __construct() {
        $this->myDB = new MyDB();
    }

	/**
     * 
     * @param string $username
     * @return boolean is username unique
     */
    function checkForDuplicatesUser($username) {

        $result = $this->$myDB->query('SELECT * FROM users WHERE username=' . $username);

        return ($result == FALSE);
    }

    /**
     * 
     * @param string $groupname
     * @return boolean is groupname unique
     */
    function checkForDuplicatesGroup($groupname) {

        $result = $this->$myDB->query('SELECT * FROM groups WHERE groupname=' . $groupname);

        return ($result == FALSE);
    }

    /**
     * 
     * @param int $user_id as the id of the user.
     * @return string $username as the name of the user.
     */
    function getUsername($user_id) {
        $result = $this->$myDB->query('SELECT username FROM users WHERE id=' . $user_id);
        $username = $this->$myDB->fetchArray($result)[0];
        return $username;
    }

    /**
     * 
     * @param string $username as username
     * @return integer id of user
     */
    function getUserId($username) {
        if (is_string($username)) {
            $result = $myDB->query('SELECT id FROM users WHERE username="' . $username . '"');
            return $this->$myDB->fetchArray($result)[0];
        }
    }

    /**
     * 
     * @param int $group_id as the id of the group.
     * @return string $groupname as the name of the group.
     */
    function getGroupname($group_id) {
        $result = $this->$myDB->query('SELECT groupname FROM groups WHERE id=' . $group_id);
        $groupname = $this->$myDB->fetchArray($result)[0];
        return $groupname;
    }

    /**
     * 
     * @param string $groupname as groupname
     * @return integer id of group
     */
    function getGroupId($groupname) {
        if (is_string($groupname)) {
            $result = $this->$myDB->query('SELECT id FROM groups WHERE groupname="' . $groupname . '"');
            return $this->$myDB->fetchArray($result)[0];
        }
    }

    /**
     *
     * @param string $name as user name
     * @param string $classes as comma seperated list of classes (hardcode a number of cols?)
     *
     */
    function createUser($name, $classes) {
        // maybe hardcode the max number of classes? can we make a query-able cell?
        $to_add = array('username' => $name, 'class' => $classes, 
                        'rating' => 0, 'num_ratings' => 0);
        $this->$myDB->addEntry($to_add, 'users');
    }

    /**
     *
     * @param string $name as the name of the group
     * @param int $user_id as person starting the group
     * @param string $description as group description
     * @param string $filter_settings as class to filter by
     */
    function createGroup($name, $user_id, $description, $filterSettings){
        $to_add = array('groupname' => $name, 'description' => $description, 
                            'class' => $filterSettings);
        $this->$myDB->addEntry($to_add, 'groups');

        // get group ID so we can add group to user_in_group
        $group_id = $this->$myDB->getGroupId($name);
        $this->$myDB->addUserToGroup($user_id, $group_id);
    }

    /**
     *
     * @param int $user_id name of user to remove
     *
     */
    function removeUser($user_id){
        $this->$myDB->deleteEntry($user_id, $tblName);
    
        // remove the user from all groups they are in
        $groups = $this->$myDB->getUserGroups($user_id);
        foreach ($group as $group_user_pair_id) {
            $this->$myDB->deleteEntry($group_user_pair_id, 'user_in_group');
        }
    }

    /** 
     *
     * @param string $groupname name of group to remove
     */
    function removeGroup($group_id){
        // if users hold group, then we will need to remove them from the group

        // remove the group from the group table
        $this->$myDB->deleteEntry($group_id, 'groups');

        // remove all instances of the group form the associative table
        $result = $this->$myDB->query('SELECT id FROM user_in_group where group_id=' . $group_id);
        $to_remove = $this->$myDB->fetchArray($results)[0];
        foreach ($to_remove as $id_to_remove) {
            $this->$myDB->deleteEntry($id_to_remove, 'user_in_group');
        }

    }

    /**
     *
     * @param int $user_id
     * @return int rating of $user_id
     */
    function getUserRating($user_id){
        $result = $this->$myDB->query('SELECT rating FROM users WHERE id=' . $user_id);
        $rating = $this->$myDB->fetchArray($results)[0];
        return $rating;
    }

    /**
     *
     * @param int $user_id 
     * @param int $rating   
     */
    function setUserRating($user_id, $rating){
        $this->$myDB->changeCellWithRow('users', 'rating', $user_id, $rating);
    }

    /**
     *
     * @param int $user_id 
     * @param int $rating new review of user to be factored in
     */
    function updateUserRating($user_id, $rating){
        // adds a new rating to a users rating

        // get the current average review and how many reviews there are total
        $result = $this->$myDB->query('SELECT num_ratings FROM users WHERE user_id=' . $user_id);
        $num_reviews = $this->$myDB->fetchArray($result)[0];
        $current_rating = $this->$myDB->getUserRating($user_id);

        // find the new average value
        $numerator = ($current_rating * $num_reviews) + $rating;
        $denominator = $num_reviews + 1; // won't div by zero
        $new_rating = $numerator / $denominator;

        // update the tables
        $this->$myDB->setUserRating($user_id, $new_rating);
        $this->$myDB->changeCellWithRow('user', 'num_ratings', $user_id, $denominator);
    }

    /**
     *
     * @param int $user_id
     * @return string comma seperated list of user classes
     */
    function getUserClasses($user_id){
        $result = $this->$myDB->query('SELECT classes FROM users WHERE id=' . $user_id);
        $classes = $this->$myDB->fetchArray($result)[0];
        return $classes;
    }

    /**
     *
     * @param int $group_id
     * @param string the class the group was made for
     */
    function getGroupClass($group_id) {
        // get the class the group was made for
        $result = $this->$myDB->query('SELECT class FROM groups WHERE id=' . $group_id);
        $class = $this->$myDB->fetchArray($result)[0];
        return $class;
    }

    /**
     * 
     * @param int $group_id
     * @return array of people in the group
     */
    function getGroupMembers($group_id){
        // get this info from the UserInGroup table
        $result = $this->$myDB->query('SELECT user_id FROM user_in_group WHERE group_id=' . $group_id);
        $members = $this->$myDB->fetchArray($result);
        return $members;
    }

    /**
     *
     * @param int user_id
     * @return int array with the id of each group/user pair in user_in_group
     */
    function getUserGroups($user_id){
        $result = $this->$myDB->query('SELECT id FROM user_in_group WHERE user_id=' . $user_id);
        $to_return = $this->$myDB->fetchArray($result);
        return $to_return;
    }

    /**
     *
     * @param int $user_id
     */
    function removeUserFromGroup($user_id, $group_id){
        $result = $this->$myDB->query('SELECT id FROM user_in_group WHERE user_id=' . $user_id . 
                    ' AND group_id=' . $group_id);
        $id = $this->$myDB->fetchArray($result)[0];
        $this->$myDB->deleteEntry($id, 'user_in_group');
    }

    /**
     *
     * @param int $user_id 
     * @param int $group_id
     */
    function addUserToGroup($user_id, $group_id){
        $group_user_pair = array('user_id' => $user_id, 'group_id' => $group_id);
        $this->$myDB->addEntry($group_user_pair, 'user_in_group');
    }

    /**
     *
     * @param string $class
     * @return int arrray of group ids with that class
     */
    function getGroupsWithClass($class){
        $result = $this->$myDB->query('SELECT id FROM groups where class=' . $class);
        return $this->$myDB->fetchArray($result);
    }

    /**
     *
     * @param int array $group_ids
     * @param string array list of groups
     */
    function listOfIdsToGroups($group_ids){
        $group_names[];
        $i = 0;
        foreach ($group_ids as $id) {
            $group_names[i] = $this->$myDB->getGroupname($id);
            $i = $i + 1;
        }
        return $group_names;
    }

    /**
     *
     * @param int array $user_ids
     * @param string array list of users
     */
    function listOfIdsToUsers($user_ids){
        $user_names[];
        $i = 0;
        foreach ($user_ids as $id) {
            $user_names[i] = $this->$myDB->getUsername($id);
            $i = $i + 1;
        }
        return $user_names;
    }

    /**
     * returns whole user object to make handling on the other size easier
     * @param int $user_id
     * @return string array all the data for that user
     */
    function getUserAsJson($user_id){
        $result = $this->$myDB->query('SELECT * FROM users WHERE id=' . $user_id);
        return $this->$myDB->fetchArray($result);
    }

    /**
     * returns whole group object to make handling on the other side easier
     * @param int $group_id
     * @return string array all the data for that group
     */
    function getGroupAsJson($group_id){
        $result = $this->$myDB->query('SELECT * FROM groups WHERE id=' . $group_id);
        $result = $this->$myDB->fetchArray($result);
        // get the group members, put them in an array and add the 
        // array onto the gorup data
        $users_in_group = $this->getGroupMembers($group_id);
        $user_jsons[];
        $i = 0;
        foreach($users_in_group as $user_id){
            $user_jsons[i] = $this->getUserAsJson($user_id);
            $i = $i + 1
        }
        // add it to the end
        $result[] = $user_jsons;
        return $result;
    }
}
?>