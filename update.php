<?php
/**
 * update.php - Update an attendee's status in the database.
 */

$log_file = '/tmp/my.log';

// Load configuration
$config = json_decode(file_get_contents('config.json'), /* assoc */ true);
extract($config);

// Grab JSON data from post
$name = file_get_contents('php://input');

// Establish DB Connection
$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

$query = <<<EOT
   UPDATE ? SET attended = TRUE
   WHERE name = ?;
EOT;

$statement = $db->prepare($query);
$result = $statement->execute([$db_table, $name]);

// If error, log it
if (!$result) {
   error_log($result, 3, $log_file);
}
