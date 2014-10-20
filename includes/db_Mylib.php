
<?PHP

	class MyConfig
	{
		public $hostname;
		public $username;
		public $password;
		public $database;
		public $prefix;
		public $connector;
	
		function __construct(
		$hostname = null,
		$username = null,
		$password = null,
		$database = null,
		$prefix = null,
		$connector = null)
	
		{
			$this->hostname = !empty($hostname) ? $hostname : "";
			$this->username = !empty($username) ? $username : "";
			$this->password = !empty($password) ? $password : "";
			$this->database = !empty($database) ? $database : "";
			$this->prefix = !empty($prefix) ? $prefix : "";
			$this->connector = !empty($connector) ? $connector : "mysqli";
		}
	
		function __destruct()
		{
	
		}
	}

	class MyDb
	{
		private $connection;
		private $selectdb;
		private $lastQuery;
		private $config;
	
		function __construct($config)
		{
			$this->config = $config;
		}
	
		function __destruct()
		{
	
		}
		
		function __tostring()
		{
		return $this->query;
		}
		
		public function openConnection()
		{
			try
			{
				if($this->config->connector == "mysql")
				{
					$this->connection = mysql_connect($this->config->hostname, $this->config->username, $this->config->password);
					$this->selectdb = mysql_select_db($this->config->database);
				}
				elseif($this->config->connector == "mysqli")
				{
					$this->connection = mysqli_connect($this->config->hostname, $this->config->username, $this->config->password);
					$this->selectdb = mysqli_select_db($this->connection, $this->config->database);	
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}

		public function closeConnection()
		{
			try
				{
					if($this->config->connector == "mysql")
					{
						mysql_close($this->connection);
					}
					elseif($this->config->connector == "mysqli")
					{
					mysqli_close($this->connection);
					}
				}
			catch(exception $e)
				{
					return $e;
				}
		}

		public function escapeString($string)
		{
			return addslashes($string);
		}

		public function query($enteredquery)
		{
			$enteredquery = str_replace("}", "", $enteredquery);
			$enteredquery = str_replace("{", $this->config->prefix, $enteredquery);
			try
			{
				if(empty($this->connection))
				{
					$this->openConnection();
					if($this->config->connector == "mysql")
					{
						$this->lastQuery = mysql_query($this->ecapeString($enteredquery));
					}
				elseif($this->config->connector == "mysqli")
				{
					$this->lastQuery = mysqli_query($this->connection, $this->ecapeString($enteredquery));
				}
//				$this->closeConnection();
				return $this->lastQuery;
				}
				else
				{
					if($this->config->connector == "mysql")
					{
						$this->lastQuery = mysql_query($this->ecapeString($enteredquery));
					}
					elseif($this->config->connector == "mysqli")
					{
						$this->lastQuery = mysqli_query($this->connection, $this->escapeString($enteredquery));
					}
					return $this->lastQuery;
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
				public function query1($enteredquery)
		{
			$enteredquery = str_replace("}", "", $enteredquery);
			$enteredquery = str_replace("{", $this->config->prefix, $enteredquery);
			try
			{
				if(empty($this->connection))
				{
					$this->openConnection();
					if($this->config->connector == "mysql")
					{
						$this->lastQuery = mysql_query($this->ecapeString($enteredquery));
					}
				elseif($this->config->connector == "mysqli")
				{
					$this->lastQuery = mysqli_query($this->connection, $this->ecapeString($enteredquery));
				}
//				$this->closeConnection();
				return $this->lastQuery;
				}
				else
				{
					if($this->config->connector == "mysql")
					{
						$this->lastQuery = mysql_query($this->ecapeString($enteredquery));
					}
					elseif($this->config->connector == "mysqli")
					{
//					echo $enteredquery;
//					break;
						$this->lastQuery = mysqli_query($this->connection, $this->escapeString($enteredquery));
					}
					return $this->lastQuery;
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}

		public function lastQuery()
		{
			return $this->lastQuery;
		}

		public function pingServer()
		{
			try
			{
				if($this->config->connector == "mysql")
				{
					if(!mysql_ping($this->connection))
					{
						return false;
					}
					else
					{
						return true;
					}
				}
				elseif($this->config->connector == "mysqli")
				{
					if(!mysqli_ping($this->connection))
					{
						return false;
					}
					else
					{
						return true;
					}
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}

		public function hasRows($result)
		{
			try
			{
				if($this->config->connector == "mysql")
				{
					if(mysql_num_rows($result)>0)
					{	
						return true;
					}
					else
					{
						return false;
					}
				}
				elseif($this->config->connector == "mysqli")
				{
					if(mysqli_num_rows($result)>0)
					{
						return true;
					}
					else
					{
						return false;
					}
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
 
		public function countRows($result)
		{
			try
			{
				if($this->config->connector == "mysql")
				{
					return mysql_num_rows($result);
				}
				elseif($this->config->connector == "mysqli")
				{
					return mysqli_num_rows($result);
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
 
		public function fetchAssoc($result)
		{
			try
			{
				if($this->config->connector == "mysql")
				{
					return mysql_fetch_assoc($result);
				}
				elseif($this->config->connector == "mysqli")
				{
					return mysqli_fetch_assoc($result);
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
 
		public function fetchArray($result)
		{
			try
			{
				if($this->config->connector == "mysql")
				{
					return mysql_fetch_array($result);
				}
				elseif($this->config->connector == "mysqli")
				{
					return mysqli_fetch_array($result);
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function print_array($sql)
		{
			try
			{
			echo "<br />";
				foreach($sql as $row)
				{
					foreach($row as $detail)
					{
						echo " ".$detail;
					}
				echo "<br />";
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
			public function freeResult($result)
		{
			try
			{
				if($this->config->connector == "mysql")
				{
					return mysql_free_result($result);
				}
				elseif($this->config->connector == "mysqli")
				{
					return mysqli_free_result($result);
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function load_values($table)
		{
//this function returns all values from the selected table as an array
			try
			{
				if($this->config->connector == "mysql")
				{
				//mysql version not defined			
				}
				elseif($this->config->connector == "mysqli")
				{
					$Mysql = $this->query("select old, count(*) from ".$table." group by 1 having count(*) > 1");
					if($this->countRows($Mysql) != 0)
					{
						echo 'Duplicates in the ".$table."  table';
					}
				$this->freeResult($Mysql);

//query the selected table
				$Mysql = $this->query("select old,new from ".$table);
				$arraylist = array();
				while($row = mysqli_fetch_assoc($Mysql))
				{
					$arraylist[] = $row;
				}
				return $arraylist;
				$this->freeResult($Mysql);
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function load_values_model($table)
		{
			//this function returns all values from the selected table as an array
			try
			{
				if($this->config->connector == "mysql")
				{
					//mysql version not defined
				}
				elseif($this->config->connector == "mysqli")
				{
					$Mysql = $this->query("select old, count(*) from ".$table." group by 1 having count(*) > 1");
					if($this->countRows($Mysql) != 0)
					{
						echo 'Duplicates in the ".$table."  table';
					}
					$this->freeResult($Mysql);
		
					//query the selected table
					$Mysql = $this->query("select old, new, groupe, series from ".$table);
					$arraylist = array();
					while($row = mysqli_fetch_assoc($Mysql))
					{
						$arraylist[] = $row;
					}
					return $arraylist;
					$this->freeResult($Mysql);
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function insert_values($table,$val1,$val2,$flag)
		{
			//this function inserts values into control table
			try
			{
				if($this->config->connector == "mysql")
				{
					//mysql version not defined
				}
				elseif($this->config->connector == "mysqli")
				{
					$this->query("insert into ".$table." (old, new, flag) values(".$val1.", ".$val2.", ".$flag.")");				
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
		
		public function update_values($table,$col1,$val1,$val2)
		{
			//this function inserts values into control table
			try
			{
				if($this->config->connector == "mysql")
				{
					//mysql version not defined
				}
				elseif($this->config->connector == "mysqli")
				{
//					$sql =  "update ".$table."  set ".$col1." = '".$val1."' where new = '".$val2. "'";
//					echo $sql;
//					exit;
					$this->query("update ".$table."  set ".$col1." = '".$val1."' where new = '".$val2. "'");
				}
			}
			catch(exception $e)
			{
				return $e;
			}
		}
			
			
	}
	
?>