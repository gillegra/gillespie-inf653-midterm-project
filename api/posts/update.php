<?php

//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Post.php';

//Instantiate DB
$db = new Database();
$dbConn = $db->connect();

//Instantiate blog post object
$post = new Post($dbConn);

$data = json_decode(file_get_contents("php://input"));

//TODO probably should read in the post to be updated first, partly so these null coalesces will do something useful, but also to throw an error before trying to update a non-existant post
$post->id = $data->id;
$post->title = $data->title ?? $post->title;
$post->body = $data->body ?? $post->body;
$post->author = $data->author ?? $post->author;
$post->category_id = $data->category_id ?? $post->category_id;
// var_dump([$data, $post]);

if ($post->update()) {
  echo json_encode(['message' => 'Post Updated']);
} else {
  echo json_encode(['message' => 'Post NOT Updated']);
}
