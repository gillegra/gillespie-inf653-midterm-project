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
  $result = [];
  $query = $author->read();
  $rowCount = $query->rowCount();

  if ($rowCount > 0) {
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $authorItem = [
        'id' => $id,
        'author' => $author
      ];

      array_push($result, $authorItem);
    }
  } else {
    $result = ['message' => 'authorId Not Found'];
  }

  return $result;
}

function readSingle(Author $author)
{
  $result = [];
  if ($author->readSingle()) {
    $result = [
      'id' => $author->id,
      'author' => $author->author
    ];
  } else {
    $result = ['message' => 'authorId Not Found'];
  }

  return $result;
}

function create(Author $author)
{
  $result = [];

  if ($author->create()) {
    $result = ['id' => $author->id, 'author' => $author->author];
  } else {
    $result = ['message' => 'authorId Not Found'];
  }

  return $result;
}

function update(Author $author)
{
  $result = [];

  if ($author->update()) {
    $result = ['id' => $author->id, 'author' => $author->author];
  } else {
    $result = ['message' => 'authorId Not Found'];
  }

  return $result;
}

switch ($method) {
  case 'GET':
    if (isset($_REQUEST['id'])) {
      $author->id = $_REQUEST['id'];
      $response = readSingle($author);
    } else {
      $response = read($author);
    }
    break;
  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['author'])) {
      $author->author = $data['author'];
      $response = create($author);
    } else {
      $response = ['message' => 'Missing "author" parameter'];
    }
    break;
  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id']) && isset($data['author'])) {
      $author->id = $data['id'];
      $author->author = $data['author'];
      $response = update($author);
    } else {
      $response = ['message' => 'Missing rquired parameter(s)'];
    }
    break;
}

echo json_encode($response);

//close DB connection
$dbConn = null;
