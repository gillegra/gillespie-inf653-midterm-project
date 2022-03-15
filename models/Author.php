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
      FROM {$this->table} AS a \n";

    $whereClauses = [];
    $whereValues = [];
    if (isset($this->id)) {
      $whereClauses[] = 'a.id = ?';
      $whereValues[] = $this->id;
    }

    if (count($whereClauses) > 0) {
      $query .= 'WHERE ' . implode(' AND ', $whereClauses) . PHP_EOL;
    }

    $query .= 'ORDER BY a.id ASC';

    // var_dump([$whereClauses, $whereValues, $query]);

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
    $query = "INSERT INTO {$this->table} SET author = :author";

    $stmt = $this->conn->prepare($query);

    $this->author = htmlspecialchars(strip_tags($this->author));

    $stmt->bindParam(':author', $this->author);
    // var_dump([$query, $stmt]);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
      $this->id = $this->conn->lastInsertId();
      return true;
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
