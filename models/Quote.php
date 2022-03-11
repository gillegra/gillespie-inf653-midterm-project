<?php

class Quote
{
  private $conn;
  private $table = 'quotes';

  public int $id;
  public string $quote;

  public function __construct(PDO $db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = "SELECT c.quote,
        c.id
      FROM {$this->table} AS c
      ORDER BY c.quote ASC";

    //Prepare statment
    $stmt = $this->conn->prepare($query);

    //Execute query
    $stmt->execute();

    return $stmt;
  }

  public function readSingle()
  {
    $query = "SELECT c.quote,
        c.id
      FROM {$this->table} AS c
      WHERE c.id = :quoteId
      ORDER BY c.quote ASC
      LIMIT 0,1";

    //Prepare statment
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam('quoteId', $this->id);

    //Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($row['quote'])) {
      $this->quote = $row['quote'];
      return true;
    } else {
      return false;
    }
  }

  public function create()
  {
    $query = "INSERT INTO {$this->table} SET quote = :quote";

    $stmt = $this->conn->prepare($query);

    $this->quote = htmlspecialchars(strip_tags($this->quote));

    $stmt->bindParam(':quote', $this->quote);
    // var_dump([$query, $stmt]);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
      $this->id = $this->conn->lastInsertId();
      return true;
    }

    return false;
  }

  public function update()
  {
    $query = "UPDATE {$this->table} SET quote = :quote WHERE id = :id";

    $stmt = $this->conn->prepare($query);

    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(':quote', $this->quote);
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
