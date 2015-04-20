<? php
	
include_once 'dblib.php'
// script to set up the tables

// create the database
$db = new StudyrDB('studyrmaster');

// TODO: double check with john to make sure I set up the ini correctly

// build the user table
$user_properties = array('id' => 'INT', 
							'username' => 'varchar(1000)', 
							'class' => 'varchar(1000)', 
							'rating' => 'INT',
							'num_ratings' => 'INT');

$db->createTable('users', $user_properties);

// build the group table
$group_properties = array('id' => 'INT', 
							'groupname' => 'varchar(1000)', 
							'description' => 'varchar(1000)', 
							'class' => 'varchar(1000)');

$db->createTable('groups', $group_properties);

$user_group_pair_properties = array('id' => 'INT', 
									'group_id' => 'INT', 
									'user_id' => 'INT');

$db->createTable('user_in_group', $user_group_pair_properties);


?>