<?php

class Category
{
  private $conn;
  private $table = 'categories';

  public int $id;
  public string $category;

  public function __construct(PDO $db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = "SELECT c.category,
        c.id
      FROM {$this->table} AS c
      ORDER BY c.id ASC";

    //Prepare statment
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  public function readSingle()
  {
    $query = "SELECT c.category,
        c.id
      FROM {$this->table} AS c
      WHERE c.id = :categoryId
      ORDER BY c.id ASC
      LIMIT 0,1";

    //Prepare statment
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam('categoryId', $this->id);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($row['category'])) {
      $this->category = $row['category'];
      return true;
    } else {
      return false;
    }
  }

  public function create()
  {
    $query = "INSERT INTO {$this->table} SET category = :category";

    $stmt = $this->conn->prepare($query);

    $this->category = htmlspecialchars(strip_tags($this->category));

    $stmt->bindParam(':category', $this->category);
    // var_dump([$query, $stmt]);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
      $this->id = $this->conn->lastInsertId();
      return true;
    }

    return false;
  }

  public function update()
  {
    $query = "UPDATE {$this->table} SET category = :category WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->category = htmlspecialchars(strip_tags($this->category));
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':category', $this->category);
    $stmt->bindParam(':id', $this->id);
    // var_dump([$query, $stmt]);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
      return true;
    }

    return false;
  }

  public function delete()
  {
    $query = "DELETE FROM {$this->table} WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':id', $this->id);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
      return true;
    }

    return false;
  }
}
