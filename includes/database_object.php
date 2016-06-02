<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class DatabaseObject {

	public static function find_all() {
    return static::find_by_sql("SELECT * FROM ".static::$table_name);
  }
  
  public static function find_by_id($id=0) {
    global $database;
    $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1");
    return !empty($result_array) ? array_shift($result_array) : false;
  }
  
  public static function find_by_sql($sql="") {
    global $database;
    $result_set = $database->query($sql);//Получаем данные в виде матрицы
    $object_array = array();
    while ($row = $database->fetch_array($result_set)) {
      $object_array[] = static::instantiate($row);//Получаем массив объектов,где каждый элемент массива вмещает в себя отдельную строку из таблицы (каждая строка таблицы обрабатывается функцией instantiate)
    }
    return $object_array;//Возвращаем заполненный из базы данных массив объектов
  }

  public static function count_all(){//Функция считает количество строк в таблице
    global $database;
    $sql = "SELECT COUNT(*) FROM".static::$table_name;//Формируем sql запрос (подсчет строк)
    $result_set = $database->query($sql);//Делаем запрос к базе данных
    $row = $database->fetch_array($result_set);//Присваиваем первую строку результата запроса переменной $row
    return array_shift($row);//Получаем информацию о количестве строк в виде числа путем извлечения первого элемента из массива $row
  }

  private static function instantiate($record) {//Переменная $record представляет каждую отдельную строчку таблицы в виде ассоциативного массива атрибут=>значение
    // Could check that $record exists and is an array
    $object = new static;//создаем объект
    // Simple, long-form approach:
    // $object->id        = $record['id'];
    // $object->username  = $record['username'];
    // $object->password  = $record['password'];
    // $object->first_name = $record['first_name'];
    // $object->last_name   = $record['last_name'];
    
    // More dynamic, short-form approach:
    foreach($record as $attribute=>$value){//Проходим по каждой паре атрибут=>значение в строке таблицы (массив $record)
      if($object->has_attribute($attribute)) {
        $object->$attribute = $value;
      }
    }
    return $object;
  }
  
  private function has_attribute($attribute) {//Функция принимает атрибут, получаемый из базы данных
    // get_object_vars returns an associative array with all attributes 
    // (incl. private ones!) as the keys and their current values as the value
    $object_vars = $this->attributes();//Получаем массив значений из существующих в массиве $db_fields атрибутов
    // We don't care about the value, we just want to know if the key exists
    // Will return true or false
    return array_key_exists($attribute, $object_vars);
  }

  protected function attributes() { 
    // return an array of attribute keys and their values
    $attributes = array();
    foreach (static::$db_fields as $field) {
      if(property_exists($this, $field)){
        $attributes[$field] = $this->$field;//Например $comment->photograph_id
      }
    }
    return $attributes;
  }
  
  protected function sanitized_attributes() {
    global $database;
    $clean_attributes = array();
    // sanitize the values before submitting
    // Note: does not alter the actual value of each attribute
    foreach($this->attributes() as $key => $value){
      $clean_attributes[$key] = $database->escape_value($value);
    }
    return $clean_attributes;
  }

  public function save() {//Сохраненине измененных существующих или добавление новых записей
    // A new record won't have an id yet.
    return isset($this->id) ? $this->update() : $this->create();
  }
  
  public function create() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - INSERT INTO table (key, key) VALUES ('value', 'value')
    // - single-quotes around all values
    // - escape all values to prevent SQL injection
    $attributes = $this->sanitized_attributes();
    $sql = "INSERT INTO ".static::$table_name." (";
    $sql .= join(", ", array_keys($attributes));
    $sql .= ") VALUES ('";
    $sql .= join("', '", array_values($attributes));
    $sql .= "')";
    if($database->query($sql)) {
      $this->id = $database->insert_id();
      return true;
    } else {
      return false;
    }
  }

  public function update() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - UPDATE table SET key='value', key='value' WHERE condition
    // - single-quotes around all values
    // - escape all values to prevent SQL injection
    $attributes = $this->sanitized_attributes();
    $attribute_pairs = array();
    foreach($attributes as $key => $value) {
      $attribute_pairs[] = "{$key}='{$value}'";
    }
    $sql = "UPDATE ".static::$table_name." SET ";
    $sql .= join(", ", $attribute_pairs);
    $sql .= " WHERE id=". $database->escape_value($this->id);
    $database->query($sql);
    return ($database->affected_rows() == 1) ? true : false;
  }

  public function delete() {
    global $database;
    // Don't forget your SQL syntax and good habits:
    // - DELETE FROM table WHERE condition LIMIT 1
    // - escape all values to prevent SQL injection
    // - use LIMIT 1
    $sql = "DELETE FROM ".static::$table_name;
    $sql .= " WHERE id=". $database->escape_value($this->id);
    $sql .= " LIMIT 1";
    $database->query($sql);
    return ($database->affected_rows() == 1) ? true : false;
  
    // NB: After deleting, the instance of User still 
    // exists, even though the database entry does not.
    // This can be useful, as in:
    // echo $user->first_name . " was deleted";
    // but, for example, we can't call $user->update() 
    // after calling $user->delete().
  }
}