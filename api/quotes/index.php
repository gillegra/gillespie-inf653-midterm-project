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
require_once '../../models/Quote.php';

//Instantiate DB
$db = new Database();
$dbConn = $db->connect();

$quote = new Quote($dbConn);

function read(Quote $quote)
{
  $result = [];
  $status = 200;
  $query = $quote->read();
  $rowCount = $query->rowCount();

  if ($rowCount > 0) {
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $quoteItem = [
        'id' => $id,
        'quote' => $quote,
        'author' => $author,
        'category' => $category
      ];

      array_push($result, $quoteItem);
    }
  } else {
    $result = ['message' => 'quoteId Not Found'];
    $status = 404;
  }

  return [$result, $status];
}

function readSingle(Quote $quote)
{
  [$result, $status] = read($quote);

  //expected response for readSingle call is identical to plain read call, but no array
  if ($status === 200) {
    $result = $result[0];
  }

  return [$result, $status];
}

function create(Quote $quote)
{
  $result = [];
  $status = 200;

  try {
    if ($quote->create()) {
      $result[] = ['id' => $quote->id, 'quote' => $quote->quote];
    } else {
      $result[] = ['message' => 'quoteId Not Found'];
      $status = 404;
    }
  } catch (Exception $e) {
    $result[] = ['message' => $e->getMessage()];
    $status = 400;
  }

  return [$result, $status];
}

function update(Quote $quote)
{
  $result = [];
  $status = 200;

  try {
    if ($quote->update()) {
      $result[] = ['id' => $quote->id, 'quote' => $quote->quote];
    } else {
      $result[] = ['message' => 'quoteId Not Found'];
      $status = 404;
    }
  } catch (Exception $e) {
    $result[] = ['message' => $e->getMessage()];
    $status = 400;
  }

  return [$result, $status];
}

function delete(Quote $quote)
{
  $result = [];
  $status = 200;

  try {
    if ($quote->delete()) {
      $result[] = ['id' => $quote->id];
    } else {
      $result[] = ['message' => 'quoteId Not Found'];
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
    $justOne = false;

    if (isset($_REQUEST['id'])) {
      $quote->id = $_REQUEST['id'];
      $justOne = true;
    }
    if (isset($_REQUEST['authorId'])) {
      $quote->authorId = $_REQUEST['authorId'];
    }
    if (isset($_REQUEST['categoryId'])) {
      $quote->categoryId = $_REQUEST['categoryId'];
    }

    if ($justOne) {
      [$response, $status] = readSingle($quote);
    } else {
      [$response, $status] = read($quote);
    }
    break;
  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['quote']) && isset($data['authorId']) && isset($data['categoryId'])) {
      $quote->quote = $data['quote'];
      $quote->authorId = $data['authorId'];
      $quote->categoryId = $data['categoryId'];
      [$response, $status] = create($quote);
    } else {
      $response = ['message' => 'Missing required parameter(s)'];
      $status = 400;
    }
    break;
  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id']) && isset($data['quote']) && isset($data['authorId']) && isset($data['categoryId'])) {
      $quote->id = $data['id'];
      $quote->quote = $data['quote'];
      $quote->authorId = $data['authorId'];
      $quote->categoryId = $data['categoryId'];
      [$response, $status] = update($quote);
    } else {
      $response = ['message' => 'Missing required parameter(s)'];
      $status = 400;
    }
    break;
  case 'DELETE':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'])) {
      $quote->id = $data['id'];
      [$response, $status] = delete($quote);
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
