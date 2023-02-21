<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is for for common database stuff.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License
*/

function snat_framework_query_db($pdo, $query, $params = array()) {
  // Execute a query on the database
  $stmt = $pdo->prepare($query);
  $stmt->execute($params);
  return $stmt;
}

function snat_framework_fetch_one($pdo, $query, $params = array()) {
  // Fetch one row from the database
  $stmt = snat_framework_query_db($pdo, $query, $params);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function snat_framework_fetch_all($pdo, $query, $params = array()) {
  // Fetch all rows from the database
  $stmt = snat_framework_query_db($pdo, $query, $params);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function snat_framework_insert($pdo, $table, $data) {
  // Insert data into a table
  $columns = implode(', ', array_keys($data));
  $values = implode(', ', array_fill(0, count($data), '?'));
  $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
  $params = array_values($data);
  snat_framework_query_db($pdo, $query, $params);
  return $pdo->lastInsertId();
}

function snat_framework_update($pdo, $table, $data, $where) {
  // Update data in a table
  $set = implode('=?, ', array_keys($data)) . '=?';
  $query = "UPDATE {$table} SET {$set} WHERE {$where}";
  $params = array_values($data);
  snat_framework_query_db($pdo, $query, $params);
}

function snat_framework_delete($pdo, $table, $where) {
  // Delete data from a table
  $query = "DELETE FROM {$table} WHERE {$where}";
  snat_framework_query_db($pdo, $query);
}

function snat_framework_transaction($pdo, $callback) {
    // Execute a transaction with a callback function
    $pdo->beginTransaction();
    try {
      $callback();
      $pdo->commit();
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
  
  function snat_framework_table_exists($pdo, $table_name) {
    // Check if a table exists in the database
    $query = "SELECT 1 FROM {$table_name} LIMIT 1";
    try {
      $pdo->query($query);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  function snat_framework_get_columns($pdo, $table_name) {
    // Get the column names of a table
    $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array($table_name));
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $result;
  }
  
  function snat_framework_escape_like($value) {
    // Escape special characters in a string for use in a LIKE query
    $value = str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $value);
    return $value;
  }
  
  function snat_framework_count_rows($pdo, $table_name, $where = '', $params = array()) {
    // Count the number of rows in a table matching a condition
    $query = "SELECT COUNT(*) FROM {$table_name}";
    if ($where) {
      $query .= " WHERE {$where}";
    }
    $stmt = snat_framework_query_db($pdo, $query, $params);
    $result = $stmt->fetchColumn();
    return $result;
  }

  function snat_framework_sanitize_input($input, $filter = FILTER_SANITIZE_STRING)
  {
      // Sanitize the input data using the specified filter
      $sanitized_input = filter_input(INPUT_POST, $input, $filter);
  
      // Return the sanitized input data
      return $sanitized_input;
  }

  function snat_framework_validate_input($input, $regex)
{
    // Validate the input data using the specified regular expression
    $valid = preg_match($regex, $input);

    // Return true if the input data is valid, false otherwise
    return $valid === 1;
}

function snat_framework_backup_database(PDO $pdo, string $type, string $database, array $tables = []): string|false
{
    switch ($type) {
        case 'mysql':
            $stmt = $pdo->query("SHOW TABLES FROM `$database`");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            break;

        case 'pgsql':
            $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            break;

            // TO-DO

        default:
            throw new Exception("Unsupported database type: $type");
    }

    if (empty($tables)) {
        return false;
    }

    $output = '';
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SELECT * FROM `$table`");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $output .= "DROP TABLE IF EXISTS `$table`;\n\n";
        $output .= "CREATE TABLE `$table` (\n";

        $stmt = $pdo->prepare("SHOW CREATE TABLE `$table`");
        $stmt->execute();
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $output .= $createTable['Create Table'] . ";\n\n";

        foreach ($rows as $row) {
            $output .= "INSERT INTO `$table` VALUES (";
            $values = array_map(function ($value) use ($pdo) {
                return $pdo->quote($value);
            }, array_values($row));
            $output .= implode(',', $values) . ");\n";
        }

        $output .= "\n";
    }

    return $output;
}


?>
