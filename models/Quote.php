<?php

class Quote
{
  private $conn;
  private $table = 'quotes';
  private $authorsTable = 'authors';
  private $categoriesTable = 'categories';

  public int $id;
  public string $quote;
  public int $authorId;
  public int $categoryId;

  public function __construct(PDO $db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query =
      "SELECT q.quote,
        q.id,
        a.author,
        c.category
      FROM {$this->table} AS q
      LEFT JOIN {$this->authorsTable} AS a
        on q.authorId = a.id
      LEFT JOIN {$this->categoriesTable} AS c
        on q.categoryId = c.id\n";

    $whereClauses = [];
    $whereValues = [];
    if (isset($this->id)) {
      $whereClauses[] = 'q.id = ?';
      $whereValues[] = $this->id;
    }
    if (isset($this->authorId)) {
      $whereClauses[] = 'a.id = ?';
      $whereValues[] = $this->authorId;
    }
    if (isset($this->categoryId)) {
      $whereClauses[] = 'c.id = ?';
      $whereValues[] = $this->categoryId;
    }

    if (count($whereClauses) > 0) {
      $query .= 'WHERE ' . implode(' AND ', $whereClauses) . PHP_EOL;
    }

    $query .= 'ORDER BY q.id ASC';

    // var_dump([$whereArr, $query]);

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

  public function readSingle()
  {
    $query = "SELECT q.quote,
        q.id
      FROM {$this->table} AS q
      WHERE q.id = :quoteId
      ORDER BY q.quote ASC
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
