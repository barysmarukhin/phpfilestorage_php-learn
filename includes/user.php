<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once(LIB_PATH.DS.'database.php');

class User extends DatabaseObject {
	
	protected static $table_name="users";//имя таблицы базы данных, с которой работаем
  protected static $db_fields = array('id', 'usermail', 'password', 'first_name', 'last_name');
	public $id;
	public $usermail;
	public $password;
	public $first_name;
	public $last_name;
	
  public function full_name() {
    if(isset($this->first_name) && isset($this->last_name)) {
      return $this->first_name . " " . $this->last_name;
    } else {
      return "";
    }
  }

	public static function authenticate($usermail="", $password="") {
    global $database;
    $usermail = $database->escape_value($usermail);
    $password = $database->escape_value($password);

    $sql  = "SELECT * FROM users ";
    $sql .= "WHERE usermail = '{$usermail}' ";
    $sql .= "AND password = '{$password}' ";
    $sql .= "LIMIT 1";
    $result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

  public function files() {
    return File::find_files_on($this->usermail);
  }

}

?>