<?php
	namespace privypaste;

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  PasteTest.php - unit test for Paste() class
     */

	require_once('../conf/pki.php');
	require_once('../lib/cryptkeeper.php');
	require_once('../lib/paste.php');

	class PasteTest extends \PHPUnit_Framework_TestCase
	{
		public function setUp(){ }
		public function tearDown(){ }

		/**
		 * @dataProvider providerTestConstructorGood
		 */
		public function testConstructorGood($plaintext, $ciphertext, $pasteUid, $creationTime, $lastModifiedTime, $pasteId)
		{
			$paste = new Paste($plaintext, $ciphertext, $pasteUid, $creationTime, $lastModifiedTime, $pasteId);

			$this->assertInstanceOf('PrivyPaste\Paste', $paste);
		}

		public function providerTestConstructorGood()
		{
			return array(
				array('', '', 'abcdabcd', 1434835839, 1434835839, 1)
			);
		}

		/**
		 * @expectedException Exception
		 * @expectedExceptionMessage bad paste data supplied!
		 * @dataProvider providerTestConstructorBad
		 */
		public function testConstructorBad($plaintext, $ciphertext, $pasteUid, $creationTime, $lastModifiedTime, $pasteId)
		{
			$paste = new Paste($plaintext, $ciphertext, $pasteUid, $creationTime, $lastModifiedTime, $pasteId);
		}

		public function providerTestConstructorBad()
		{
			return array(
				array('', '', '', '', '', ''),
				array('', '', 'aaaaaaaa', 7273134824, 7273134824, 1),
				array('', '', 'aaaa', 1434835839, 1434835839, 1),
				array('', '', 'aaaaaaaa', 1434835839, 1434835839, -1)
			);
		}

		public function testEncryptPlaintext()
		{
			// plaintext can really be set to anything, so we only need one function for testing here
			// any functions that would make it fail are part of the Cryptkeeper() as well, so are already tested in CryptKeeperTest.php

			$paste = new Paste('abcd', '', 'abcdabcd', 1434835839, 1434835839, 1);

			$this->assertTrue($paste->encryptPlaintext());
		}

		public function testDecryptCiphertextGood()
		{
			$plaintext = 'abcd';

			$paste = new Paste($plaintext, '', 'aaaaaaaa', 1434835839, 1434835839, 1);

			// first we must encrypt the plaintext, then decrypt it
			$paste->encryptPlaintext();
			$this->assertTrue($paste->decryptCiphertext());
		}

		public function testDecryptCiphertextBad()
		{
			// supply bogus ciphertext and try to decrypt it to make sure it fails
			$paste = new Paste('', 'abcdefgh', 'aaaaaaaa', 1434835839, 1434835839, 1);

			$this->assertFalse($paste->decryptCiphertext());
		}

		// TODO: add tests for Paste::sendPasteDataToDb() and Paste::retrievePasteDataFromDb()
	}
