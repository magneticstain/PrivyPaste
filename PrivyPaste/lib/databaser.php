<?php
    namespace PrivyPaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  databaser.php - a class for database connections [CURRENTLY: MYSQL]
	 */

    class Databaser
    {
		protected $username = '';
	    protected $password = '';
	    protected $host = '';
	    protected $dbName = '';
	    private $dbConn;
	    private $logger;

	    public function __construct($username, $password, $hostname, $dbName, $logger = '')
	    {
		    /*
		     * Params:
			 *      - $username
			 *          - username of PrivyPaste database user
			 *      - $password
			 *          - password of PrivyPaste database user
		     *      - $host
		     *          - hostname of database
		     *      - $dbName
		     *          - name of database that PrivyPaste should use
			 *
			 *  Usage:
			 *      - sets variables of and creates a new Db() object
			 *
			 *  Returns:
			 *      - NONE
		     */

		    # start logging if a previous Logger() instance was not provided
		    if(empty($logger) || !isset($logger))
		    {
			    $logger = new Logger('');
		    }

			if(
				!$this->setUsername($username)
				|| !$this->setPassword($password)
				|| !$this->setHost($hostname)
				|| !$this->setDbName($dbName)
				|| !$this->setLogger($logger)
			)
			{
				throw new \Exception('invalid database info supplied!');
			}
	    }

	    // SETTERS
	    public function setUsername($username)
	    {
		    /*
		     * Params:
			 *      - $username
			 *          - username of PrivyPaste database user
			 *
			 *  Usage:
			 *      - sets username variable of Db() object
			 *
			 *  Returns:
			 *      - bool
		     */

		    // no restraints except being blank
		    if($username !== '')
		    {
			    $this->username = $username;

		        return true;
		    }

		    return false;
	    }

	    public function setPassword($password)
	    {
		    /*
		     * Params:
			 *      - $password
			 *          - password of PrivyPaste database user
			 *
			 *  Usage:
			 *      - sets password variable of Db() object
			 *
			 *  Returns:
			 *      - bool
		     */

		    // no restraints except being blank
		    if($password !== '')
		    {
			    $this->password = $password;

			    return true;
		    }

		    return false;
	    }

	    public function setHost($hostname)
	    {
		    /*
		     * Params:
			 *      - $host
			 *          - host of PrivyPaste database user
			 *
			 *  Usage:
			 *      - sets host variable of Db() object
			 *
			 *  Returns:
			 *      - bool
		     */

		    // no restraints except being blank
		    if($hostname !== '')
		    {
			    $this->host = $hostname;

			    return true;
		    }

		    return false;
	    }

	    public function setDbName($dbName)
	    {
		    /*
		     * Params:
			 *      - $dbName
			 *          - database name that PrivyPaste should use for data
			 *
			 *  Usage:
			 *      - sets database name variable of Db() object
			 *
			 *  Returns:
			 *      - bool
		     */

		    // no restraints except being blank
		    if($dbName !== '')
		    {
			    $this->dbName = $dbName;

			    return true;
		    }

		    return false;
	    }

	    public function setLogger($logger)
	    {
		    /*
		     * Params:
			 *      - $logger
			 *          - Logging object used for logging messages to syslog
			 *
			 *  Usage:
			 *      - sets logging object
			 *
			 *  Returns:
			 *      - bool
		     */

		    // no restraints as any errors will be shown once the variable tries to be used
		    if(!is_null($logger))
		    {
			    $this->logger = $logger;

			    return true;
		    }

		    return false;
	    }

	    // GETTERS
	    public function getUsername()
	    {
		    /*
		     * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - gets username variable of Db() object
			 *
			 *  Returns:
			 *      - string
		     */

		    return $this->username;
	    }

	    public function getPassword()
	    {
		    /*
		     * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - gets password variable of Db() object
			 *
			 *  Returns:
			 *      - string
		     */

		    return $this->password;
	    }

	    public function getHost()
	    {
		    /*
		     * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - gets host variable of Db() object
			 *
			 *  Returns:
			 *      - string
		     */

		    return $this->host;
	    }

	    public function getDbName()
	    {
		    /*
		     * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - gets database name variable of Db() object
			 *
			 *  Returns:
			 *      - string
		     */

		    return $this->username;
	    }

	    public function getLogger()
	    {
		    /*
		     * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - gets logging object
			 *
			 *  Returns:
			 *      - Logger()
		     */

		    return $this->logger;
	    }

	    // OTHER FUNCTIONS
	    public function createDbConnection()
	    {
		    /*
		     * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - tries to create a database connection using object variables
			 *
			 *  Returns:
			 *      - bool
		     */

		    $dbConn = '';

		    // create db connection
		    try
		    {
			    $dbConn = new \PDO('mysql:host='.$this->host.';dbname='.$this->dbName, $this->username, $this->password);
		    } catch(\PDOException $e)
		    {
			    $this->logger->setLogMsg('could not create new PDO() object :: '.$e->getMessage());
			    $this->logger->setLogSrcFunction('Databaser() -> createDbConnection()');
			    $this->logger->writeLog();
		    }

		    // if connection was successful, set object variable
		    if($dbConn !== '')
		    {
			    $this->dbConn = $dbConn;

			    return true;
		    }

		    return false;
	    }

	    public function destroyDbConnection()
	    {
		    /*
		     * Params:
			 *      - NONE
			 *
			 *  Usage:
			 *      - tries to destroy this objects database connection
			 *
			 *  Returns:
			 *      - bool
		     */

		    $this->dbConn = NULL;

		    return true;
	    }

	    public function queryDb($sql, $action = 'select', $sqlParams = [])
	    {
		    /*
		     * Params:
			 *      - $sql
		     *          - sql query to be executed, in PDO-compatible form
		     *      - $action
		     *          - action type of sql query
		     *          - can be 'select', 'insert', 'update', or 'delete'
		     *          - determines what should be done post-query
		     *      - $sqlParams
		     *          - associative array of any parameters that need to be bound to the query statement
		     *          - should be setup as:
		     *              - key = variable name in sql query
		     *              - value = array of data related to the key (sqlParamName)
		     *                  -value to be bound to the variable in sql query
		     *                  - data type of bind variable
		     *                      - i = int
		     *                      - s = string
		     *              - e.g.
		     *                  - SQL Query: "SELECT * FROM Test WHERE id = :test_id"
		     *                  - PARAM ARRAY
		     *                      - ['test_id' => '1']
			 *
			 *  Usage:
			 *      - tries to query the PrivyPaste db
			 *
			 *  Returns:
			 *      - result of query or insert ID
		     *      - depends on actions
		     */

		    // normalize params
		    $action = strtolower((string) $action);

		    // prepare db statement
		    $dbStmt = $this->dbConn->prepare($sql);

			// bind any params
			foreach($sqlParams as $sqlParamName => $bindVal)
			{
				// check if data type of bind value was set and is valid
				if(isset($bindVal[1]) && ($bindVal[1] === 'i' || $bindVal[1] === 's'))
				{
					// data type was explicitly set, binding value based on data type
					if($bindVal[1] === 'i')
					{
						// bind as integer
						$dbStmt->bindValue($sqlParamName, $bindVal[0], \PDO::PARAM_INT);
					}
					elseif($bindVal[1] === 's')
					{
						// bind as string
						$dbStmt->bindValue($sqlParamName, $bindVal[0], \PDO::PARAM_STR);
					}
				}
				else
				{
					// no explicit data type set, assuming default
					$dbStmt->bindValue($sqlParamName, $bindVal);
				}
			}

		    // execute and get result
		    if($dbStmt->execute())
		    {
			    // query was executed successfully
			    // return what is needed based on action
			    if($action === 'select')
			    {
				    // return results
				    $dbResults = $dbStmt->fetchAll();
			    }
			    elseif($action === 'insert')
			    {
				    // return insert ID
				    $dbResults = $this->dbConn->lastInsertId();
			    }
			    else
			    {
				    // in cases of DELETE or UPDATE, simply return bool for success or failure
				    if($dbStmt->rowCount() > 0)
				    {
					    $dbResults = true;
				    }
				    else
				    {
					    $dbResults = false;
				    }
			    }

			    // query produced results, return them to user
			    return $dbResults;
		    }
		    else
		    {
			    // get PDO error
			    $dbStmtErrorInfo = $dbStmt->errorInfo();

			    // log error
			    $this->logger->setLogMsg('BAD SQL QUERY :: '.$dbStmtErrorInfo[2]);
			    $this->logger->setLogSrcFunction('Databaser() -> queryDb()');
			    $this->logger->writeLog();
			    $this->logger->setLogMsg('QUERY :: '.$sql);
			    $this->logger->writeLog();
		    }

		    return false;
	    }
    }
?>