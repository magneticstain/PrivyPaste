<?php
	namespace privypaste;

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  DatabaserTest.php - phpunit test for Databaser() class
     */

	require_once('../conf/db.php');
	require_once('../lib/databaser.php');

	class DatabaserTest extends \PHPUnit_Framework_TestCase
	{
		public function setUp(){ }
		public function tearDown(){ }

		/**
		 * @dataProvider providerTestConstructorGood
		 */
		public function testConstructorGood($username, $password, $hostname, $dbName)
		{
			$dbaser = new Databaser($username, $password, $hostname, $dbName);

			$this->assertInstanceOf('PrivyPaste\Databaser', $dbaser);
		}

		public function providerTestConstructorGood()
		{
			return array(
				array(DB_USER,DB_PASS,DB_HOST,DB_NAME)
			);
		}

		/**
		 * @expectedException Exception
		 * @expectedExceptionMessage invalid database info supplied!
		 * @dataProvider providerTestConstructorBad
		 */
		public function testConstructorBad($username, $password, $hostname, $dbName)
		{
			$dbaser = new Databaser($username, $password, $hostname, $dbName);
		}

		public function providerTestConstructorBad()
		{
			return array(
				array('', '', '', ''),
				array(DB_USER, '', '', ''),
				array('', DB_PASS, '', ''),
				array('', '', DB_HOST, ''),
				array('', '', '', DB_NAME),
				array('aaaaaaa', 'aaaaaa', 'ȪȪȪȪȪȪȪȪ', '')
			);
		}

		/**
		 * @dataProvider providerTestCreateDbConnectionGood
		 */
		public function testCreateDbConnectionGood($username, $password, $hostname, $dbName)
		{
			$dbaser = new Databaser($username, $password, $hostname, $dbName);

			$this->assertTrue($dbaser->createDbConnection());

			return $dbaser;
		}

		public function providerTestCreateDbConnectionGood()
		{
			return array(
				array(DB_USER, DB_PASS, DB_HOST, DB_NAME)
			);
		}

		/**
		 * @dataProvider providerTestCreateDbConnectionBad
		 */
		public function testCreateDbConnectionBad($username, $password, $hostname, $dbName)
		{
			// incoming data should pass the setters, but not be able to connect to the database
			$dbaser = new Databaser($username, $password, $hostname, $dbName);

			$this->assertFalse($dbaser->createDbConnection());
		}

		public function providerTestCreateDbConnectionBad()
		{
			return array(
				array(DB_USER, DB_PASS, DB_HOST, 'aaa'),
				array(DB_USER, DB_PASS, 5, DB_HOST),
				array(DB_USER, '☭☭☭☭☭☭☭', DB_HOST, DB_HOST),
				array('a', DB_PASS, 5, DB_HOST)
			);
		}

		/**
		 * @dataProvider providerTestQueryDbGood
		 */
		public function testQueryDbGood($sql, $action, $sqlParams)
		{
			// create Databaser() object and connect to db
			$dbaser = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			// connect to db
			$dbaser->createDbConnection();

			// query db
			$dbResult = $dbaser->queryDb($sql, $action, $sqlParams);

			$this->assertTrue($dbResult !== false);
		}

		public function providerTestQueryDbGood()
		{
			return array(
				array('SELECT count(*) FROM pastes', 'select', array()),
				array('INSERT INTO pastes SET id = 111111, uid = :paste_uid, created = NOW(), last_modified = NOW(), ciphertext = :ciphertext', 'insert', array('paste_uid' => 'aaaabbbb', 'ciphertext' => 'abcd')),
				array('UPDATE pastes SET last_modified = NOW(), ciphertext = :ciphertext WHERE id = 111111', 'update', array('ciphertext' => 'dcba')),
				array('DELETE FROM pastes WHERE id = 111111', 'delete', array())
			);
		}

		/**
		 * @dataProvider providerTestQueryDbBad
		 */
		public function testQueryDbBad($sql, $action, $sqlParams)
		{
			// NOTE: this function will print error_log output to stdout. It is expected and means that everything is working as it should. I have not found a way to prevent this.

			// create Databaser() object and connect to db
			$dbaser = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			// connect to db
			$dbaser->createDbConnection();

			// query db
			$dbResult = @$dbaser->queryDb($sql, $action, $sqlParams);

			$this->assertFalse($dbResult);
		}

		public function providerTestQueryDbBad()
		{
			return array(
				array('', 'select', array()),
				array('INSERT INTO pastes id = 111111, uid = :paste_uid, created = NOW(), last_modified = NOW(), ciphertext = :ciphertext', 'insert', array('paste_uid' => 'aaaabbbb', 'ciphertext' => 'abcd')),
				array('UPDATE pastes SET last_modified = NOW(), ciphertext = "" WHERE id = 111111', 'update', array('ciphertext' => 0))
			);
		}
	}
