<?php

namespace Core\Models;

use Core\Models\Database;

/**
 * The base model class to be extended by other models.
 */

abstract class BaseModel
{

  public static $index;
  public static $query;
  public static $info;
  public static $displayQuery = false;
  private static $connection = null;
  public static $table;

  private $columns = '*';
  private $join = '';
  private $joinCriteria;
  private $where = '';
  private $groupBy = '';
  private $having = '';
  private $orderBy = '';
  private $limit = '';
  private $columnsToUpdate;


  /**
   * Constrtuctor.
   */

  public function __construct($conditions, $type = 'where')
  {
    $this->$type = $conditions;
  }


  /**
   * Executes select query, returning fields whose id matches
   * the one provided.
   * 
   * @var mixed id
   * 
   * @return array result
   */

  public static function find($id)
  {
    static::$query  = 'SELECT * FROM '. static::$table;
    if(gettype($id) != 'array') {
      static::$query .= ' where '. static::$index .'="'. self::escape($id) .'"';
    } else {
      static::$query .= self::conditions($id);
    }
    $result = self::conn()->query(static::$query);
    static::onQueryDone();
    return self::parseResult($result);
  }


  /**
   * A shortcut for find method and flatening the result.
   * 
   * @var mixed id
   * 
   * @return object result
   */

  public static function one($id)
  {
    $result = self::find($id);
    
    return count($result) ? $result[0] : false;
  }


  /**
   * Fetches the first N rows.
   * 
   * @var int count
   * 
   * @return array result 
   */

  public static function first($count = 1)
  {
    static::$query = 'SELECT * FROM ' . static::$table;
    static::$query .= ' ORDER BY id ASC LIMIT ' . $count;
    $result = self::parseResult(self::conn()->query(static::$query));
    static::onQueryDone();
    return $result;
  }


   /**
   * Fetches the last N rows.
   * 
   * @var int count
   * 
   * @return array result 
   */

  public static function last($count = 1)
  {
    static::$query = 'SELECT * FROM ' . static::$table;
    static::$query .= ' ORDER BY id DESC LIMIT ' . $count;
    $result = self::parseResult(self::conn()->query(static::$query));
    static::onQueryDone();
    return $result;
  }


  /**
   * Executes the insert query.
   * 
   * @var array data
   * 
   * @return mixed
   */

  public static function store($data)
  {
    $data = self::prepareData($data);
    $columns = '('. implode(',', $data['keys']) .')';
    $values  = '('. implode(',', $data['values']) .')';
    static::$query = 'INSERT INTO '. static::$table;
    static::$query .= $columns .' values '. $values; 
    $result = self::conn()->query(static::$query);
    static::onQueryDone();
    return $result;
  }


  /**
   * Returns all rows.
   * 
   * @return array rows
   */

  public static function all($columns = [])
  {
    return self::where([])->get($columns);
  }


  /**
   * Creates a model instance with a where clause.
   * 
   * @var array conditions
   * 
   * @return object
   */

  public static function where($conditions)
  {
    if(gettype($conditions) != 'array') {
      $conditions = [static::$index => $conditions];
    }
    return new static(self::conditions($conditions));
  }


   /**
   * Creates a model instance with a having clause.
   * 
   * @var array conditions
   * 
   * @return object
   */

  public static function having($conditions)
  {
    if(gettype($conditions) != 'array') {
      $conditions = [static::$index => $conditions];
    }
    return new static(self::conditions($conditions, 'having'), 'having');
  }

  /**
   * A shorter way of doing where([]) or having([]).
   * 
   * @return object
   */

  public static function each()
  {
    return new static(self::conditions([]));
  }


  /**
   * Wraps the provided closure in a transaction, rolling it
   * back on error, commiting if there were no errors.
   * 
   * @var object closure
   * 
   * @return bool success
   */

  public static function transaction($closure)
  {
    self::conn()->query('START TRANSACTION');
    try {
      $closure();
    } catch(\Exception $e) {
      self::conn()->query('ROLLBACK');
      return false;
    }
    self::conn()->query('COMMIT');
    return true;
  }


  /**
   * Initializes 'group by' clause.
   * 
   * @var mixed input
   * 
   * @return object this
   */

  public function groupBy($input)
  {
    if(gettype($input) == 'string') {
      $this->groupBy = ' GROUP BY '. self::escape($input);
    } else {
      $this->groupBy = ' GROUP BY '. implode(',', self::escape($input));
    }
    return $this;
  }


  /**
   * Initializes 'ORDER BY' clause.
   * 
   * @var mixed input
   * 
   * @return object this
   */

  public function orderBy($input)
  {
    if(gettype($input) == 'string') {
      $this->orderBy = ' ORDER BY '. self::escape($input);
    } else {
      $this->orderBy = ' ORDER BY '. implode(',', self::escape($input));
    }
    return $this;
  }


  /**
   * Initializes 'LIMIT' clause.
   * 
   * @var int start
   * @var int end
   * 
   * @return object this
   */

  public function limit($start, $end = 0)
  {
    if($end) {
      $this->limit = ' LIMIT ' . self::escape("$start, $end");
    } else {
      $this->limit = ' LIMIT ' . self::escape("$start");
    }
    return $this;
  }


  /**
   * Initializes first part of join clause and aplies default join
   * criteria which can be overriden later with the on() method.
   * 
   * @var string table
   * 
   * @return object this
   */

  public function join($table)
  {
    $this->join = " JOIN $table on ";
    $self = static::$table;
    $this->joinCriteria = " $self.id_$table=$table.id";
    return $this;
  }


  /**
   * Same as normal join, except it's left.
   * 
   * @var string table
   * 
   * @return object this
   */

  public function leftJoin($table)
  {
    $this->join = " LEFT JOIN $table on ";
    $self = static::$table;
    $this->joinCriteria = " $self.id_$table=$table.id";
    return $this;
  }


  /**
   * Same as normal join, except it's right.
   * 
   * @var string table
   * 
   * @return object this
   */

  public function rightJoin($table)
  {
    $this->join = " RIGHT JOIN $table on ";
    $self = static::$table;
    $this->joinCriteria = " $self.id_$table=$table.id";
    return $this;
  }


  /**
   * Alters default join criteria on join clause.
   * 
   * @var string criteria
   * 
   * @return object this
   */

  public function on($criteria)
  {
    $this->joinCriteria = " $criteria ";
    return $this;
  }


  /**
   * Forms the select query.
   * 
   * @return string query
   */

  private function formSelectQuery()
  {
    return (
      'SELECT '. $this->columns .' FROM '. static::$table 
      .$this->join . $this->joinCriteria . $this->where 
      .$this->groupBy . $this->having 
      .$this->orderBy . $this->limit
    ); 
  }


  /**
   * Executes the select query.
   * 
   * @var mixed columns
   * 
   * @return mixed result
   */

  public function get($columns = [])
  {
    if(count($columns)) $this->columns = self::formColumns($columns);
    static::$query = $this->formSelectQuery();
    $result = self::parseResult(self::conn()->query(static::$query));
    static::onQueryDone();
    return $result;
  }


  /**
   * A shorter way of doing where([])->delete() to delete all rows.
   * 
   * @return mixed result
   */

  public static function clear()
  {
    return self::where([])->delete();
  }


  /**
   * Deletes rows that match 'where' property of current instance.
   * 
   * @return mixed queryResult
   */

  public function delete()
  {
    static::$query = 'DELETE FROM '. static::$table;
    static::$query .= $this->where;
    $result = self::conn()->query(static::$query);
    static::onQueryDone();
    if($this->where == '') {
      self::conn()->query('TRUNCATE '. static::$table);
    }
    return $result;
  }


  /**
   * Initializes columns that will be updated.
   * 
   * @var array columns
   * 
   * @return object this
   */

  public function update($columns)
  {
    $this->columnsToUpdate = $columns;
    return $this;
  }


  /**
   * Performs the update query. Must be called after update() method.
   * 
   * @var array values
   * 
   * @return mixed
   */

  public function with($values)
  {
    static::$query = 'UPDATE ' . static::$table . ' SET ';
    foreach($values as $index => $value) {
      static::$query .= self::escape($this->columnsToUpdate[$index]);
      static::$query .= '="' . self::escape($value) . '",';
    }
    static::$query = substr(static::$query, 0, -1);
    static::$query .= $this->where;
    $result = self::conn()->query(static::$query);
    static::onQueryDone();
    return $result;
  }


  /**
   * A shorter way of doing update()->with().
   * 
   * @var array data
   * 
   * @return mixed
   */

  public function updateWith($data)
  {
    static::$query = 'UPDATE ' . static::$table . ' SET ';
    foreach($data as $column => $value) {
      static::$query .= self::escape($column);
      static::$query .= '="' . self::escape($value) . '",';
    }
    static::$query = substr(static::$query, 0, -1);
    static::$query .= $this->where;
    $result = self::conn()->query(static::$query);
    static::onQueryDone();
    return $result;
  }
  

  /**
   * Concatenates columns from provided array, applying aliases.
   * 
   * @var array columns
   * 
   * @return string columns
   */

  private function formColumns($columns) {
    if(gettype($columns) != 'array') {
      return $this->formColumn($columns);
    }
    //Initializing
    $result = $this->formColumn($columns[0]);
    //Concatenating columns and optional aliases
    for($i = 1; $i < count($columns); $i++) {
      $result .= ',' . $this->formColumn($columns[$i]);
    }
    return $result;
  }


  /**
   * Returns single column name and aliases it if needed.
   * 
   * @var string column
   * 
   * @return string column
   */

  private function formColumn($column) {
    $args = explode('|', self::escape($column));
    return count($args) == 2 ? $args[0] .' AS '. $args[1] : $args[0];
  }


  /**
   * Executes a custom query.
   * 
   * @var string query
   * 
   * @return mixed
   */

  public static function customQuery($query)
  {
    static::$query = $query;
    $result = self::conn()->query($query);
    static::onQueryDone();
    return $result;
  }


  /**
   * Escapes special characters to prevent SQL injection.
   * 
   * @var string input
   * 
   * @return string escaped
   */

  private static function escape($input, $escapeWildcards = false)
  {
    if(gettype($input) == 'array') {
      $escaped = [];
      foreach($input as $single) {
        if($escapeWildcards) $single = addcslashes($single, '%_');
        $escaped[] = self::conn()->real_escape_string($single);
      }
      return $escaped;
    }
    if($escapeWildcards) $input = addcslashes($input, '%_');
    return self::conn()->real_escape_string($input);
  }


  /**
   * A getter for connection.
   * 
   * @return object connection
   */

  private static function conn()
  {
    if(self::$connection) return self::$connection;
    
    self::$connection = Database::getInstance()->connection;

    return self::$connection; 
  }


  /**
   * Returns an array needed to construct key value based query.
   * Escapes unwated characters and puts quotes around values.
   * 
   * @var array data
   * 
   * @return array preparedData
   */

  private static function prepareData($data)
  {
    $prepared = ['keys' => [], 'values' => []];
    foreach($data as $key => $value) {
      $prepared['keys'][] = self::escape($key);
      $prepared['values'][] = '"'. self::escape($value) .'"';
    }
    return $prepared;
  }


  /**
   * Updates info class field with data from last query.
   * Checks for error and handles it to Logger class.
   * 
   * @return void
   */

  private static function onQueryDone()
  {
    if(self::$displayQuery) var_dump(static::$query);
    
    static::$info = [
      'insert_id' => self::conn()->insert_id,
      'affected_rows' => self::conn()->affected_rows
    ];

    if(self::conn()->error != '') {
      throw new \Exception(self::conn()->error);
    }
  }


  /**
   * Parses query result and returns a collection of rows.
   * 
   * @return array result
   */

  private static function parseResult($result)
  {
    $collection = [];
    if($result) {
      while($row = $result->fetch_object()) {
        $collection[] = $row;
      }
    }
    return $collection;
  }


  /**
   * Builds conditions clause out of provided conditions array.
   * 
   * @var array conditions
   * 
   * @return string where|having
   */

  protected static function conditions($conditions, $type = 'where') {
    if(count($conditions) == 0) return '';
    $clause = " $type "; // " where " | " having "
    //In first iteration there is no need to prepend a chain
    $chained = true;
    foreach($conditions as $key => $value) {
      /**
       * Testing for manualy provided chain via input, for example:
       * [key1 => value1, --> 'OR' <-- , key2 => value2].
       */
      if(gettype($key) == 'integer') {
        /**
         * When custom chain was manualy provided via input, we'll mark that
         * so that there is no need to prepend it in next iteration.
         */
        $chained = true;
        $clause .= " $value "; //$value = AND|OR|NOT
      } else {
        /**
         * If we come at next key value pair and it wasn't chained in
         * previous iteration, we prepend default chain (AND).
         */
        if(!$chained) $clause .= ' AND ';
        //Appending column[operator]value
        $clause .= self::condition($key, $value); 
        /**
         * Since column[operator]value pair is appended, chain should
         * be added in next iteration.
         */
        $chained = false;
      }
    }
    return $clause;
  }


  /**
   * Builds a single condition in form column[operator]value.
   * 
   * @var string column
   * @var mixed value
   * 
   * @return string condition
   */
  
  private static function condition($column, $value) {
    /**
     * By default (=) operator is used between column and value.
     * To override that, column can be piped with operator.
     */
    if(strrpos($column, '|')) {
      $args = explode('|', $column);
      $column = $args[0]; 
      $operator = $args[1] == '*' ? ' LIKE ' : $args[1];
      $value = '"%'. self::escape($value, true) .'%"';
    //'IN' operator is indicated by providing array as value.
    } else if(gettype($value) == 'array') { 
      $operator = ' IN ';
      $value = '('. implode(',', self::escape($value)) .')';
    //'BETWEEN' operator is indicated with ':' inside the value.
    } else if(strpos($value, ':')) {
      $range = explode(':', $value); 
      $operator = ' BETWEEN ';
      $value  = self::escape($range[0]) .' AND ';
      $value .= self::escape($range[1]);
    } else {
      $operator = '=';
      $value = '"'. self::escape($value) .'"';
    }
    return $column . $operator . $value;
  }

}