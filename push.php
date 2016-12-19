<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);

date_default_timezone_set('Asia/Tokyo');

echo "xxx";

//DATABASE_URL = postgres://cbpwfjdfgminnm:8b610ee14ab82f5af2f0ee50932155a08744a2854c34b488469316cde84ff28c@ec2-54-235-90-107.compute-1.amazonaws.com:5432/do7eaa2rdtvvc

try {
  $url = parse_url(getenv('DATABASE_URL'));
  $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));

  $pdo = new PDO($dsn, $url['user'], $url['pass']);
  var_dump($pdo->getAttribute(PDO::ATTR_SERVER_VERSION));

  //insert into sample (crtuser,) values( );
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $pdo->query("select * from sample");
  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ( $users as $user ) {
    var_dump($user);
  }

  echo $stmt->rowCount();


} catch (PDOException $e ) {
  echo $e->getMessage();
}


?>