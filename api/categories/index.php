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
require_once '../../models/Category.php';

//Instantiate DB
$db = new Database();
$dbConn = $db->connect();

$category = new Category($dbConn);

function read(Category $category)
{
  $result = [];
  $status = 200;
  $query = $category->read();
  $rowCount = $query->rowCount();

  if ($rowCount > 0) {
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $categoryItem = [
        'id' => $id,
        'category' => $category
      ];

      array_push($result, $categoryItem);
    }
  } else {
    $result = ['message' => 'categoryId Not Found'];
    $status = 404;
  }

  return [$result, $status];
}

function readSingle(Category $category)
{
  [$result, $status] = read($category);

  //expected response for readSingle call is identical to plain read call, but no array
  if ($status === 200) {
    $result = $result[0];
  }

  return [$result, $status];
}

function create(Category $category)
{
  $result = [];
  $status = 200;

  try {
    if ($category->create()) {
      $result[] = ['id' => $category->id, 'category' => $category->category];
    } else {
      $result[] = ['message' => 'categoryId Not Found'];
      $status = 404;
    }
  } catch (Exception $e) {
    $result[] = ['message' => $e->getMessage()];
    $status = 400;
  }

  return [$result, $status];
}

function update(Category $category)
{
  $result = [];
  $status = 200;

  try {
    if ($category->update()) {
      $result[] = ['id' => $category->id, 'category' => $category->category];
    } else {
      $result[] = ['message' => 'categoryId Not Found'];
      $status = 404;
    }
  } catch (Exception $e) {
    $result[] = ['message' => $e->getMessage()];
    $status = 400;
  }

  return [$result, $status];
}

function delete(Category $category)
{
  $result = [];
  $status = 200;

  try {
    if ($category->delete()) {
      $result[] = ['id' => $category->id];
    } else {
      $result[] = ['message' => 'categoryId Not Found'];
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
      $category->id = $_REQUEST['id'];
      [$response, $status] = readSingle($category);
    } else {
      [$response, $status] = read($category);
    }
    break;
  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['category'])) {
      $category->category = $data['category'];
      [$response, $status] = create($category);
    } else {
      $response = ['message' => 'Missing "category" parameter'];
      $status = 400;
    }
    break;
  case 'PUT':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id']) && isset($data['category'])) {
      $category->id = $data['id'];
      $category->category = $data['category'];
      [$response, $status] = update($category);
    } else {
      $response = ['message' => 'Missing required parameter(s)'];
      $status = 400;
    }
    break;
  case 'DELETE':
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['id'])) {
      $category->id = $data['id'];
      [$response, $status] = delete($category);
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
