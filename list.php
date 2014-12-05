<?php

/**
 * list.php - Print all guests who have not been marked as 'Here'
 */

$config_file = file_get_contents('config.json');
$config = json_decode($config_file, /* assoc */ true);
$db_config = $config['db'];

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
$db = new PDO("mysql:host={$db_config['host']};dbname={$db_config['name']}",
 $db_config['user'], $db_config['password']);

// If query parameter all is set, then include those we are 'Invited'
if ($all) {
   $query = <<<EOT
      SELECT name, status FROM ?
      WHERE attended = FALSE
      ORDER BY status, name;
EOT;
} else {
   $query = <<<EOT
      SELECT name, status FROM ?
      WHERE attended = FALSE AND status != 'Invited'
      ORDER BY status, name;
EOT;
}

$people = $db->prepare($query);

// List all attendants
if ($people->execute([$db_config['table']])) {
   while ($row = $people->fetch()) {
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
      echo "<button id='$name_marker' type='button' class='attended btn btn-default btn-lg $btn_color'>" . $row['status'] . "</button>\n";
      echo "<h1 class='name'>" . $row['name'] . "</h1>\n";
      echo "</div>\n";
      echo "</div>\n";
   }
}
