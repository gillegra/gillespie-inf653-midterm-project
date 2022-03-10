<?php

class Author
{
  private $conn;
  private $table = 'authors';

  public int $id;
  public string $author;

  public function __construct(PDO $db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = "SELECT a.author,
        a.id
      FROM {$this->table} AS a
      ORDER BY a.author ASC";

    //Prepare statment
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  public function readSingle()
  {
    $query = "SELECT a.author,
        a.id
      FROM {$this->table} AS a
      WHERE a.id = :authorId
      ORDER BY a.author ASC
      LIMIT 0,1";

    //Prepare statment
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam('authorId', $this->id);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($row['author'])) {
      $this->author = $row['author'];
      return true;
    } else {
      return false;
    }
  }

  public function create()
  {
    $query = "INSERT INTO {$this->table} SET author = :author";

    $stmt = $this->conn->prepare($query);

    $this->author = htmlspecialchars(strip_tags($this->author));

    $stmt->bindParam(':author', $this->author);
    // var_dump([$query, $stmt]);

    try {
      if ($stmt->execute()) {
        $this->id = $this->conn->lastInsertId();
        return true;
      }
    } catch (PDOException $e) {
      printf("Error: %s.\n", $e->getMessage());
    }

    return false;
  }

  public function update()
  {
    $query = "UPDATE {$this->table} SET author = :author WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->author = htmlspecialchars(strip_tags($this->author));
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':author', $this->author);
    $stmt->bindParam(':id', $this->id);
    // var_dump([$query, $stmt]);

    try {
      if ($stmt->execute()) {
        return true;
      }
    } catch (PDOException $e) {
      printf("Error: %s.\n", $e->getMessage());
    }
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
