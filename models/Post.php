<?php

class Post
{
  private $conn;
  private $table = 'posts';

  public $id;
  public $categoryId;
  public $categoryName;
  public $title;
  public $body;
  public $author;
  public $createdAt;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = "SELECT c.name AS category_name,
        p.id,
        p.category_id,
        p.title,
        p.body,
        p.author,
        p.created_at
      FROM {$this->table} AS p
      LEFT JOIN categories AS c
        on p.category_id = c.id
      ORDER BY p.created_at DESC";

    //Prepare statment
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  public function readSingle()
  {
    $query = "SELECT c.name AS category_name,
        p.id,
        p.category_id,
        p.title,
        p.body,
        p.author,
        p.created_at
      FROM {$this->table} AS p
      LEFT JOIN categories AS c
        on p.category_id = c.id
      WHERE p.id = :postId
      ORDER BY p.created_at DESC
      LIMIT 0,1";

    //Prepare statment
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam('postId', $this->id);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->title = $row['title'];
    $this->body = html_entity_decode($row['body']);
    $this->author = $row['author'];
    $this->category_id = $row['category_id'];
    $this->category_name = $row['category_name'];
  }

  public function create()
  {
    $query = "INSERT INTO {$this->table}
      SET
        title = :title,
        body = :body,
        author = :author,
        category_id = :category_id";

    $stmt = $this->conn->prepare($query);

    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->body = htmlspecialchars(strip_tags($this->body));
    $this->author = htmlspecialchars(strip_tags($this->author));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));

    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':body', $this->body);
    $stmt->bindParam(':author', $this->author);
    $stmt->bindParam(':category_id', $this->category_id);
    // var_dump([$query, $stmt]);

    if ($stmt->execute()) {
      return true;
    }

    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  public function update()
  {
    $query = "UPDATE {$this->table}
      SET
        title = :title,
        body = :body,
        author = :author,
        category_id = :category_id
      WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->title = htmlspecialchars(strip_tags($this->title));
    $this->body = htmlspecialchars(strip_tags($this->body));
    $this->author = htmlspecialchars(strip_tags($this->author));
    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':body', $this->body);
    $stmt->bindParam(':author', $this->author);
    $stmt->bindParam(':category_id', $this->category_id);
    $stmt->bindParam(':id', $this->id);
    // var_dump([$query, $stmt]);

    if ($stmt->execute()) {
      return true;
    }

    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  public function delete()
  {
    $query = "DELETE FROM {$this->table} WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':id', $this->id);

    if ($stmt->execute()) {
      return true;
    }

    printf("Error: %s.\n", $stmt->error);

    return false;
  }
}
