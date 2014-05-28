<?php

// Print all guests who have not been marked as 'Here'
// Filter is for filtering types of invites, none is going

$config_file = file_get_contents('config.json');
$config = json_decode($config_file);

// Use cookies to check if user has permissions
// (not ideal, but fine if guests aren't familiar with cookies.)
$cookie = $_GET['can_has_cookie'];

// Query parameter 'all' for guests who aren't listed as 'Going' or 'Maybe'
$all = isset($_GET['all']);

// If user has 'can_has_cookie' query parameter, give them a cookie
// Otherwise, if they do not have the correct cookie, print message and return.
if (isset($cookie)) {
   $cookie_result = setcookie("party", "win");
} elseif ($_COOKIE['party'] != 'win') {
   echo "<h1>No Permssions</h1>";
   return;
}

// Connect to MySQL database
$con = mysqli_connect($config->{'db_host'}, $config->{'db_user'},
 $config->{'db_password'}, $config->{'db_name'});

if (mysqli_connect_errno($con)) {
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$db_table = $config->{'db_table'};

// If query parameter all is set, then include those we are 'Invited'
if ($all) {
   $result = mysqli_query($con, <<<EOT
      SELECT name, status FROM $db_table
      WHERE attended = FALSE
      ORDER BY status, name;
EOT
   );

} else {
   $result = mysqli_query($con, <<<EOT
      SELECT name, status FROM $db_table
      WHERE attended = FALSE AND status != 'Invited'
      ORDER BY status, name;
EOT
   );
}


// List all attendants
while ($row = mysqli_fetch_array($result)) {
   // Replace all spaces with underscores
   $name_marker = str_replace(' ', '_', $row['name']);
   $btn_color = 'btn-success';

   // Assign button color depending on invite status
   // Green    -> Going
   // Yellow   -> Maybe
   // Red      -> Invited
   if ($row['status'] == 'Maybe') {
      $btn_color = 'btn-warning';
   } else if ($row['status'] == 'Invited') {
      $btn_color = 'btn-danger';
   }

   // Print attendant row
   echo "<div class='row'>\n";
   echo "<div class='well'>\n";
   echo "<button id='$name_marker' type='button' class='attended btn btn-default btn-lg $btn_color'>Attended</button>\n";
   echo "<h1 class='name'>" . $row['name'] . "</h1>\n";
   echo "</div>\n";
   echo "</div>\n";
}
