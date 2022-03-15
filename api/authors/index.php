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
  $status = 200;
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
    $status = 404;
  }

  return [$result, $status];
}

function readSingle(Author $author)
{
  [$result, $status] = read($author);

  //expected response for readSingle call is identical to plain read call, but no array
  if ($status = 200) {
    $result = $result[0];
  }

  return [$result, $status];
}

function create(Author $author)
{
  $result = [];
  $status = 200;

  try {
    if ($author->create()) {
      $result[] = ['id' => $author->id, 'author' => $author->author];
    } else {
      $result[] = ['message' => 'authorId Not Found'];
      $status = 404;
    }
  } catch (Exception $e) {
    $result[] = ['message' => $e->getMessage()];
    $status = 400;
  }

  return [$result, $status];
}

function update(Author $author)
{
  $result = [];
  $status = 200;

  try {
    if ($author->update()) {
      $result[] = ['id' => $author->id, 'author' => $author->author];
    } else {
      $result[] = ['message' => 'authorId Not Found'];
      $status = 404;
    }
  } catch (Exception $e) {
    $result[] = ['message' => $e->getMessage()];
    $status = 400;
  }

  return [$result, $status];
}

function delete(Author $author)
{
  $result = [];
  $status = 200;

  try {
    if ($author->delete()) {
      $result[] = ['id' => $author->id];
    } else {
      $result[] = ['message' => 'authorId Not Found'];
      $status = 404;
    }
  } catch (Exception $e) {
    $result[] = ['message' => $e->getMessage()];
    $status = 400;
  }

  return [$result, $status];
}

switch ($method) {
  case 'GET':
    if (isset($_REQUEST['id'])) {
      $author->id = $_REQUEST['id'];
      [$response, $status] = readSingle($author);
    } else {
      [$response, $status] = read($author);
    }
    break;
  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['author'])) {
      $author->author = $data['author'];
      [$response, $status] = create($author);
    } else {
      $response = ['message' => 'Missing "author" parameter'];
      $status = 400;
    }
    break;
  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id']) && isset($data['author'])) {
      $author->id = $data['id'];
      $author->author = $data['author'];
      [$response, $status] = update($author);
    } else {
      $response = ['message' => 'Missing required parameter(s)'];
      $status = 400;
    }
    break;
  case 'DELETE':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'])) {
      $author->id = $data['id'];
      [$response, $status] = delete($author);
    } else {
      $response = ['message' => 'Missing required parameter(s)'];
      $status = 400;
    }
    break;
}

http_response_code($status);
echo json_encode($response);

//close DB connection
$dbConn = null;
