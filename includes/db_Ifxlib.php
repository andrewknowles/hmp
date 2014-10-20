
<?PHP

	class IfxConfig
	{
		public $odbc;
		public $username;
		public $password;
	
		function __construct(
		$odbc = null,
		$username = null,
		$password = null)
	
		{
			$this->odbc = !empty($odbc) ? $odbc : "";
			$this->username = !empty($username) ? $username : "";
			$this->password = !empty($password) ? $password : "";
		}
	
		function __destruct()
		{
	
		}
	}

	class IfxDb
	{
		private $connection;
		private $selectdb;
		private $lastQuery;
		private $config;
		private $andrew;
	
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
					$this->connection = odbc_connect($this->config->odbc, $this->config->username, $this->config->password);
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
					odbc_close($this->connection);
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

		public function query1($enteredquery)
		{
				if(empty($this->connection))
				{
					$this->openConnection();
				}
				$this->lastQuery = odbc_exec($this->connection, $enteredquery);
				if(!$this->lastQuery)
				{
					error_log($enteredquery);
					error_log(odbc_errormsg($this->connection));
					echo $enteredquery;
					exit;
				}
				return $this->lastQuery;
			
		}

		public function lastQuery()
		{
			return $this->lastQuery;
		}



		public function hasRows($result)
		{
			try
			{
				if(odbc_num_rows($result)>0);
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
				return odbc_num_rows($result);
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
			
			
	}
	
?>