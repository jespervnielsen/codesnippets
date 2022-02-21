<?php

echo '<p>hello world</p>';

$servername = "database:3306";
$username = "root";
$password = "root";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "<p>Connected successfully</p>";

$redis = new Redis();
$redis->connect('redis', 6379);
echo "<p>Connection to server sucessfully</p>";
//check whether server is running or not
echo "<p>Server is running: ".$redis->ping()."</p>";

printf( '<pre>%s</pre>', var_export( $_SERVER, true ) );

$files = array_diff(scandir(__DIR__), array('.', '..'));

printf( '<pre>%s</pre>', var_export( $files, true ) );