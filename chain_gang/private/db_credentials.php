<?php 

//keep db cred in a seperate file 
// 1. Easy to exclude this file from source code managers 
// 2. Unique credentials on development and production servers
// 3. Unique credentials if working with multiple developers

define("DB_SERVER", "localhost");
define("DB_USER", "webuser");
define("DB_PASS", "secretpassword");
define("DB_NAME", "chain_gang");

?>