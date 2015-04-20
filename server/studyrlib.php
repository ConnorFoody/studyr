<?php

include_once 'dblib.php';

// library holding all the Studyr related functions
class StudyrDB extends MyDB {

	function __construct() {
        parent::__construct();
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
        $this->addEntry($to_add, 'users');
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
        $this->addEntry($to_add, 'groups');

        // get group ID so we can add group to user_in_group
        $group_id = $this->getGroupId($name);
        $this->addUserToGroup($user_id, $group_id);
    }

    /**
     *
     * @param int $user_id name of user to remove
     *
     */
    function removeUser($user_id){
        $this->deleteEntry($user_id, $tblName);
    
        // remove the user from all groups they are in
        $groups = $this->getUserGroups($user_id);
        foreach ($group as $group_user_pair_id) {
            $this->deleteEntry($group_user_pair_id, 'user_in_group');
        }
    }

    /** 
     *
     * @param string $groupname name of group to remove
     */
    function removeGroup($group_id){
        // if users hold group, then we will need to remove them from the group

        // remove the group from the group table
        $this->deleteEntry($group_id, 'groups');

        // remove all instances of the group form the associative table
        $result = $this->query('SELECT id FROM user_in_group where group_id=' . $group_id);
        $to_remove = $this->fetchArray($results)[0];
        foreach ($to_remove as $id_to_remove) {
            $this->deleteEntry($id_to_remove, 'user_in_group');
        }

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
        $this->changeCellWithRow('users', 'rating', $user_id, $rating);
    }

    /**
     *
     * @param int $user_id 
     * @param int $rating new review of user to be factored in
     */
    function updateUserRating($user_id, $rating){
        // adds a new rating to a users rating

        // get the current average review and how many reviews there are total
        $result = $this->query('SELECT num_ratings FROM users WHERE user_id=' . $user_id);
        $num_reviews = $this->fetchArray($result)[0];
        $current_rating = $this->getUserRating($user_id);

        // find the new average value
        $numerator = ($current_rating * $num_reviews) + $rating;
        $denominator = $num_reviews + 1; // won't div by zero
        $new_rating = $numerator / $denominator;

        // update the tables
        $this->setUserRating($user_id, $new_rating);
        $this->changeCellWithRow('user', 'num_ratings', $user_id, $denominator);
    }

    /**
     *
     * @param int $user_id
     * @return string comma seperated list of user classes
     */
    function getUserClasses($user_id){
        $result = $this->query('SELECT classes FROM users WHERE id=' . $user_id);
        $classes = $this->fetchArray($result)[0];
        return $classes;
    }

    /**
     *
     * @param int $group_id
     * @param string the class the group was made for
     */
    function getGroupClass($group_id) {
        // get the class the group was made for
        $result = $this->query('SELECT class FROM groups WHERE id=' . $group_id);
        $class = $this->fetchArray($result)[0];
        return $class;
    }

    /**
     * 
     * @param int $group_id
     * @return array of people in the group
     */
    function getGroupMembers($group_id){
        // get this info from the UserInGroup table
        $result = $this->query('SELECT user_id FROM user_in_group WHERE group_id=' . $group_id);
        $members = $this->fetchArray($result);
        return $members;
    }

    /**
     *
     * @param int user_id
     * @return int array with the id of each group/user pair in user_in_group
     */
    function getUserGroups($user_id){
        $result = $this->query('SELECT id FROM user_in_group WHERE user_id=' . $user_id);
        $to_return = $this->fetchArray($result);
        return $to_return;
    }

    /**
     *
     * @param int $user_id
     */
    function removeUserFromGroup($user_id, $group_id){
        $result = $this->query('SELECT id FROM user_in_group WHERE user_id=' . $user_id . 
                    ' AND group_id=' . $group_id);
        $id = $this->fetchArray($result)[0];
        $this->deleteEntry($id, 'user_in_group');
    }

    /**
     *
     * @param int $user_id 
     * @param int $group_id
     */
    function addUserToGroup($user_id, $group_id){
        $group_user_pair = array('user_id' => $user_id, 'group_id' => $group_id);
        $this->addEntry($group_user_pair, 'user_in_group');
    }

    /**
     *
     * @param string $class
     * @return int arrray of group ids with that class
     */
    function getGroupsWithClass($class){
        $result = $this->query('SELECT id FROM groups where class=' . $class);
        return $this->fetchArray($result);
    }

    /**
     *
     * @param int array $group_ids
     */

}
?>