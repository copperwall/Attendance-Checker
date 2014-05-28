<?php
// I'm not sure if this is the best way to read JSON data from posts, but I was
// having trouble with $_POST

$log_file = '/tmp/my.log';

// Load configuration
$config = json_decode(file_get_contents('config.json'));
// Grab JSON data from post
$name = file_get_contents('php://input');
// Establish DB Connection
$con = mysqli_connect($config->{'db_host'}, $config->{'db_user'},
 $config->{'db_password'}, $config->{'db_name'});

if (mysqli_connect_errno($con)) {
   error_log(mysqli_connect_error, 3, $log_file);
}

// Sanitize input
$cleaned_name = mysqli_real_escape_string($con, $name);
$db_table = $config->{'db_table'};

$result = mysqli_query($con, <<<EOT
   UPDATE $db_table SET attended = TRUE
   WHERE name = '$cleaned_name';
EOT
);

// If error, log it
if (!$result) {
   error_log($result, 3, $log_file);
}
