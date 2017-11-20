<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('DATABASE', 'gcs23');
define('USERNAME', 'gcs23');
define('PASSWORD', 'grimes78');
define('CONNECTION', 'sql1.njit.edu');

class dbConn

{
	
	protected static $db;

	private function __construct()
	
	{
		try

		{
			self::$db = new PDO('mysql:host=' . CONNECTION .';dbname=' .DATABASE, USERNAME, PASSWORD );
			self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		}

		catch (PDOexception $e)

		{

		echo "Connection Error: " . $e->getMessage();

		}
	}


public static function getConnection()

	{
		if(!self::$db)

		{
			new dbConn();
		}

	return self:: $db;
	
	}

}


Class collection


//create new model name


{

	static public function create()

	{

		$model = new static::$modelName;
		return $model;
	}


	public function findAll()
// find all records

	{
		$db = dbConn::getConnection();
		$tableName = get_called_class();
// select statement from a specific table name

		$sql = 'SELECT * FROM ' . $tableName;
		$statement = $db->prepare($sql);
		$statement->execute();
		$class = static::$modelName;
		$statement->setFetchMode(PDO::FETCH_CLASS, $class);
		$recordsSet =  $statement->fetchAll();
		return $recordsSet;

	}


// find a single ID


	public function findOne($id)

	{
		$db = dbConn::getConnection();
		$tableName = get_called_class();
		
		// select statement that queries for one entry dependant on id number


		$sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
		$statement = $db->prepare($sql);
		$statement->execute();
		$class = static::$modelName;
		$statement->setFetchMode(PDO::FETCH_CLASS,$class);
		$recordsSet  =  $statement->fetchAll();
		//return that single record

		
		return $recordsSet;
	}
}



class accounts extends collection


	{
		protected static $modelName = 'accounts';
	}

class todos extends collection

	{
		protected static $modelName = 'todos';
	}

class model

{

	static $columnString;
	static $valueString;

	public function save()

	{
		if (static::$id == '')

		{
			$db = dbConn::getConnection();
			$array = get_object_vars($this);
			static::$columnString = implode(', ', $array);
			static::$valueString = implode(', ',array_fill(0,count($array),'?'));
			$sql = $this->insert();
			$stmt=$db->prepare($sql);
			$stmt->execute(static::$data);

			}
				else
			{

			$db = dbConn::getConnection();
			$array = get_object_vars($this);
			$sql = $this->update();
			$stmt=$db->prepare($sql);
			$stmt->execute();


			}
			
		}




public function update()


{

	$sql = "Update ".static::$tableName. " SET ".static::$columnToUpdate."='".static::$newInfo."' WHERE id=".static::$id;
	
	return $sql;
}


public function delete()

{

	$db=dbConn::getConnection();
	$sql = 'Delete From '.static::$tableName.' WHERE id='.static::$id;
	$stmt=$db->prepare($sql);
	$stmt->execute();
	echo'deleted record  :'.static::$id;
}

}


	
class account extends model

{

	public $email = 'email';
	public $fname = 'fname';
	public $lname = 'lname';
	public $phone = 'phone';
	public $birthday = 'birthday';
	public $gender = 'gender';
	public $password = 'password';
	static $tableName = 'accounts';
	static $id = '123';

	static $columnToUpdate = 'fname';
	static $newInfo = 'thejoker';

}


class todo extends model

{

	public $owneremail = 'owneremail';
	public $ownerid = 'ownerid';
	public $createddate = 'createddate';
	public $duedate = 'duedate';
	public $message = 'message';
	public $isdone = 'isdone';
	static $tableName = 'todos';
	static $id = '1';

}


class table

{


	static function makeTable($result)

	{
		echo '<table>';
		foreach ($result as $column)

		{
			echo '<tr>';
			foreach ($column as $row)
			
			{
				echo '<td>';
				echo $row;
				echo '</td>';
			}

			echo '</tr>';
		}

		echo '</table>';
	}
}




echo '<h1> Select all from accounts table <h1>';

$records = accounts::create();
$result = $records->findAll();
table::makeTable($result);

echo '<br>';

echo '<h1> Select a single  ID from accounts where ID is 123 <h1>';

$result= $records->findOne(123);
table::makeTable($result);

echo '<br>';

echo '<br>';

echo '<h1> Update first name in accounts where ID is 123  <h1>';

$obj = new account;
$obj->save();
$records = accounts::create();
$result = $records ->findAll();
table::makeTable($result);

echo '<br>';

echo '<h1> Delete ID  1 from Todo <h1>';

$obj = new todo;
$obj->delete();
$records = todos::create();
$result = $records->findAll();
table::makeTable($result);


echo '<br>';




?>















