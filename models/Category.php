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
        FROM {$this->table} AS c \n";

    $whereClauses = [];
    $whereValues = [];
    if (isset($this->id)) {
      $whereClauses[] = 'c.id = ?';
      $whereValues[] = $this->id;
    }

    if (count($whereClauses) > 0) {
      $query .= 'WHERE ' . implode(' AND ', $whereClauses) . PHP_EOL;
    }

    $query .= 'ORDER BY c.id ASC';

    //Prepare statment
    $stmt = $this->conn->prepare($query);

    //MySQL gets angry when you try to bind parameters which don't exist
    for ($i = 0; $i < count($whereValues); $i++) {
      $stmt->bindParam($i + 1, $whereValues[$i]);
    }

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  public function create()
  {
    $query = "INSERT INTO {$this->table} SET category = :category";

    $stmt = $this->conn->prepare($query);

    $this->category = htmlspecialchars(strip_tags($this->category));

    $stmt->bindParam(':category', $this->category);

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

  public function exists()
  {
    $query = "SELECT COUNT(*) FROM {$this->table} WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':id', $this->id);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
      return true;
    }

    return false;
  }
}
