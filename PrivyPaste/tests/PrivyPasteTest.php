<?php
	namespace privypaste;

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  PrivyPasteTest.php - PHPUnit test for PrivyPaste() class
     */

	require_once('../conf/global.php');
	require_once('../conf/db.php');
	require_once('../conf/pki.php');
	require_once('../lib/databaser.php');
	require_once('../lib/cryptkeeper.php');
	require_once('../lib/paste.php');
	require_once('../lib/privypaste.php');

	class PrivyPasteTest extends \PHPUnit_Framework_TestCase
	{
		public function setUp(){ }
		public function tearDown(){ }

		/**
		 * @param $dbConn
		 * @param $content
		 * @param $errorMsg
		 * @param $url
		 * @param $subTitle
		 * @dataProvider providerTestConstructionGood
		 */
		public function testContructorGood($dbConn, $content, $errorMsg, $url, $subTitle)
		{
			// requirements are: $content cannot be blank, URL must be valid
			$pp = new PrivyPaste($dbConn, $content, $errorMsg, $url, $subTitle);

			$this->assertInstanceOf('PrivyPaste\PrivyPaste', $pp);
		}

		public function providerTestConstructionGood()
		{
			// create Databaser() object
			$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			return array(
				array('','test','','http://www.test.org/',''),
				array('','test','test error message','http://www.test.org/','HomeTest'),
				array('','test','test error message','https://www.test.org/','HomeTest'),
				array($db,'test','test error message','http://www.test.org/','HomeTest')
			);
		}

		/**
		 * @param $dbConn
		 * @param $content
		 * @param $errorMsg
		 * @param $url
		 * @param $subTitle
		 * @dataProvider providerTestContructionBad
		 * @expectedException Exception
		 * @expectedExceptionMessage could not set main privypaste object!
		 */
		public function testConstructorBad($dbConn, $content, $errorMsg, $url, $subTitle)
		{
			// requirements are: $content cannot be blank,
			$pp = new PrivyPaste($dbConn, $content, $errorMsg, $url, $subTitle);
		}

		public function providerTestContructionBad()
		{
			return array(
				array('', '', '', '', ''),
				array('', 'test', '', '', ''),
				array('', '', '', 'http://www.example.org/', ''),
				array('', '', '', 'https://www.example.org/', '')
			);
		}

		/**
		 * @param $basePath
		 * @dataProvider providerTestGetServerUrlGood
		 */
		public function testGetServerUrlGood($basePath)
		{
			$pp = new PrivyPaste('','test','','http://www.test.org/','');

			$this->assertTrue($pp->getServerUrl($basePath) !== '');
		}

		public function providerTestGetServerUrlGood()
		{
			return array(
				array('/'),
				array('/PrivyPaste'),
				array('/PrivyPaste/test/direction/multi/levels/')
			);
		}

		/**
		 * @param $basePath
		 * @dataProvider providerTestGetServerUrlBad
		 */
		public function testGetServerUrlBad($basePath)
		{
			$pp = new PrivyPaste('','test','','http://www.test.org/','');

			// the only way this function can return invalid is if a blank basePath is sent
			$this->assertTrue($pp->getServerUrl($basePath) === '');
		}

		public function providerTestGetServerUrlBad()
		{
			return array(
				array(''),
				array(null)
			);
		}

		/**
		 * @param $datetime
		 * @dataProvider providerTestGetRelativeTimeFromTimestampGood
		 */
		public function testGetRelativeTimeFromTimestampGood($datetime)
		{
			$pp = new PrivyPaste('','test','','http://www.test.org/','');

			$this->assertTrue($pp->getRelativeTimeFromTimestamp($datetime) !== '');
		}

		public function providerTestGetRelativeTimeFromTimestampGood()
		{
			return array(
				array('2015-01-01 00:00:00'),
				array('1970-01-01 00:00:00'),
				array('now'),
				array('+1 week'),
				array('+1 week 2 days 4 hours 2 seconds'),
				array(' last Friday'),
				array('')
			);
		}

		/**
		 * @param $numPastes
		 * @dataProvider providerTestGetMostRecentlyModifiedPastesGood
		 */
		public function testGetMostRecentlyModifiedPastesGood($numPastes)
		{
			// create Databaser() object
			$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			// create PP object
			$pp = new PrivyPaste($db, 'content', '', 'https://www.example.com/', '');

			$recentPastes = $pp->getMostRecentlyModifiedPastes($numPastes);

			$this->assertTrue($recentPastes !== 0);
		}

		public function providerTestGetMostRecentlyModifiedPastesGood()
		{
			return array(
				array(10),
				array('10'),
				array(50),
				array('100')
			);
		}

		public function testGenerateMostRecentlyModifiedPastesHtml()
		{
			// create Databaser() object
			$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			// create PP object
			$pp = new PrivyPaste($db, 'content', '', 'https://www.example.com/', '');

			$this->assertTrue($pp->generateMostRecentlyModifiedPastesHtml() !== '<strong>Most Recent Pastes: </strong>');
		}

		public function testGetTotalPastes()
		{
			// create Databaser() object
			$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			// create PP object
			$pp = new PrivyPaste($db, 'content', '', 'https://www.example.com/', '');

			$this->assertTrue($pp->getTotalPastes() > -1);
		}

		public function testGeneratePasteContentHtml()
		{
			/* function should always return text
			 * what text it returns is dependant on if the API call fails or not
			 * We can't run a test that can simulate a good API call since the paste UID differs between installations, so we are only checking that it returns either of the two strings
			 */

			// get base URL for API call
			$baseUrl = PrivyPaste::getServerUrl(BASE_URL_DIR);

			// create Databaser() object
			$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			$pp = new PrivyPaste($db, 'content', '', $baseUrl, '');

			$pasteHtml = $pp->generatePasteContentHtml('ffffffff');

			$this->assertRegExp('/pasteTextHtmlHeading/', $pasteHtml);
		}

		public function testGenerateHtmlPage()
		{
			// this function returns valid HTML, no matter if the dynamic contents fail or not
			// failure of this would depend on what the user is looking for

			// get base URL for API call
			$baseUrl = PrivyPaste::getServerUrl(BASE_URL_DIR);

			// create Databaser() object
			$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);

			$pp = new PrivyPaste($db, 'content', '', $baseUrl, '');

			$htmlPage = $pp->generateHtmlPage();

			$this->assertRegExp('/Store your text securely and safely/', $htmlPage);
		}
	}
