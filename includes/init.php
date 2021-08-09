<?php
include_once("includes/db.php");
$db = init_sqlite_db('db/init.sqlite', 'db/init.sql');


include_once("includes/sessions.php");
$session_messages = array();
process_session_params($db, $session_messages);


define('ADMIN_GROUP_ID', 1);
$is_admin = is_user_member_of($db, ADMIN_GROUP_ID);
