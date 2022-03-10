<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method ===  'OPTIONS') {
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
  exit();
}

require_once '../../config/Database.php';
require_once '../../models/Author.php';

//Instantiate DB
$db = new Database();
$dbConn = $db->connect();

$author = new Author($dbConn);

function read(Author $author)
{
  $result = $author->read();
  $rowCount = $result->rowCount();

  if ($rowCount > 0) {
    $authorsArr = [];
    $authorsArr['data'] = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $authorItem = [
        'id' => $id,
        'author' => $author
      ];

      //Push to "data"
      array_push($authorsArr['data'], $authorItem);
    }

    echo json_encode($authorsArr);
  } else {
    echo json_encode(['message' => 'No Authors Found']);
  }
}

function readSingle(Author $author)
{
  $author->readSingle();
  $authorsArr = [
    'id' => $author->id,
    'author' => $author->author
  ];

  echo json_encode($authorsArr);
}

switch ($method) {
  case 'GET':
    if (isset($_REQUEST['id'])) {
      $author->id = $_REQUEST['id'];
      readSingle($author);
    } else {
      read($author);
    }
    break;
    // case 'POST':
}

echo $response;

//close DB connection
$dbConn = null;
