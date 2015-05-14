<?php
    namespace privypaste;

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

	    public function __construct($username, $password, $hostname, $dbName)
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

			if(
				!$this->setUsername($username)
				|| !$this->setPassword($password)
				|| !$this->setHost($hostname)
				|| !$this->setDbName($dbName)
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

		    // create db connection
		    try
		    {
			    $dbConn = new \PDO('mysql:host='.$this->host.';dbname='.$this->dbName, $this->username, $this->password);
		    } catch(\PDOException $e)
		    {
			    return false;
		    }

		    // if connection was successful, attempt paste insertion
		    if($dbConn !== '')
		    {
			    $this->dbConn = $dbConn;

			    return true;
		    }

		    return false;
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
		     *          - associative array of any parameters that need to be binded to the query statement
		     *          - should be setup as:
		     *              - key = variable name in sql query
		     *              - value = value to be binded to the variable in sql query
		     *              - e.g.
		     *                  - SQL Query: "SELECT * FROM Test WHERE id = :test_id"
		     *                  - PARAM ARRAY
		     *                      - ['test_id','1']
			 *
			 *  Usage:
			 *      - tries to query the PrivyPaste db
			 *
			 *  Returns:
			 *      - result of query or insert ID
		     *      - depends on actions
		     */

		    // normalize params
		    $action = strtolower($action);

		    // prepare db statement
		    $dbStmt = $this->dbConn->prepare($sql);

			// bind any params
		    if(count($sqlParams) > 0)
		    {
			    // params were sent, start binding
				foreach($sqlParams as $sqlParamName => $bindVal)
				{
//					echo "PARAM_NAME: ".$sqlParamName."\n";
//					echo "PARAM: ".$bindVal."\n";
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

			    // query produced results, return it
			    return $dbResults;
		    }

		    return false;
	    }
    }
?>