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

$post->id = isset($_GET['id']) ? $_GET['id'] : die();

$post->readSingle();
$postsArr = [
  'id' => $post->id,
  'title' => $post->title,
  'body' => $post->body,
  'author' => $post->author,
  'category_id' => $post->category_id,
  'category_name' => $post->category_name
];

echo json_encode($postsArr);
