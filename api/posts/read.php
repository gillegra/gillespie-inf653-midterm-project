<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

//Instantiate DB
$db = new Database();
$dbConn = $db->connect();

//Instantiate blog post object
$post = new Post($dbConn);

$result = $post->read();
$rowCount = $result->rowCount();

if ($rowCount > 0) {
  $postsArr = [];
  $postsArr['data'] = [];

  while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);

    $post_item = [
      'id' => $id,
      'title' => $title,
      'body' => html_entity_decode($body),
      'author' => $author,
      'category_id' => $category_id,
      'category_name' => $category_name
    ];

    //Push to "data"
    array_push($postsArr['data'], $post_item);
  }

  echo json_encode($postsArr);
} else {
  echo json_encode(['message' => 'No Posts Found']);
}
