<?php
    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  CryptKeeperTest.php - unit test for CryptKeeper() class
     */

	require_once('../lib/cryptkeeper.php');

	class CryptKeeperTest extends PHPUnit_Framework_TestCase
	{
		public function setUp(){ }
		public function tearDown(){ }

		/**
		 * @dataProvider providerTestGetPkiKeyFromFileGood
		 */
		public function testGetPkiKeyFromFileGood($keyType, $keyFile)
		{
			$ck = new \privypaste\CryptKeeper();

			$this->assertTrue($ck->getPkiKeyFromFile($keyType, $keyFile) !== false);
		}

		public function providerTestGetPkiKeyFromFileGood()
		{
			return array(
				array('public', '/opt/privypaste/certs/public_key.pem'),
				array('private', '/opt/privypaste/certs/private_key.pem')
			);
		}

		/**
		 * @dataProvider providerTestGetPkiKeyFromFileBad
		 */
		public function testGetPkiKeyFromFileBad($keyType, $keyFile)
		{
			$ck = new \privypaste\CryptKeeper();

			$this->assertFalse($ck->getPkiKeyFromFile($keyType, $keyFile));
		}

		public function providerTestGetPkiKeyFromFileBad()
		{
			return array(
				array('public', '/opt/privypaste/certs/public_key.p'),
				array('', '/opt/privypaste/certs/private_key.pem'),
				array('public', ''),
				array('', ''),
				array('1', 'aaaaaaaaa5555')
			);
		}

		/**
		 * @dataProvider providerTestEncryptString
		 */
		public function testEncryptString($publicKeyResource, $plaintext, $isBase64Encoded)
		{
			$ck = new \privypaste\CryptKeeper();

			$this->assertTrue($ck->encryptString($publicKeyResource, $plaintext, $isBase64Encoded) !== '');
		}

		public function providerTestEncryptString()
		{
			$ck = new \privypaste\CryptKeeper();
			$keyFile = '/opt/privypaste/certs/public_key.pem';

			$publicKeyResource = $ck->getPkiKeyFromFile('public', $keyFile);

			return array(
				array($publicKeyResource, 'aaaa', true),
				array($publicKeyResource, 'aaaa', false),
				array($publicKeyResource, 'ȪȪȪȪȪȪȪȪ', true),
				array($publicKeyResource, 'ȪȪȪȪȪȪȪȪ', false),
				array($publicKeyResource, '', true),
				array($publicKeyResource, '', false)
			);
		}

		/**
		 * @dataProvider providerTestDecryptString
		 */
		public function testDecryptString($plaintext, $useBase64Encoding)
		{
			$ck = new \privypaste\CryptKeeper();
			$publicKeyFile = '/opt/privypaste/certs/public_key.pem';
			$privateKeyFile = '/opt/privypaste/certs/private_key.pem';

			// read in keys
			$publicKeyResource = $ck->getPkiKeyFromFile('public', $publicKeyFile);
			$privateKeyResource = $ck->getPkiKeyFromFile('private', $privateKeyFile);

			// encrypt the plaintext string
			$ciphertext = $ck->encryptString($publicKeyResource, $plaintext, $useBase64Encoding);

			// decrypt the ciphertext
			$decryptedText = $ck->decryptString($privateKeyResource, $ciphertext, $useBase64Encoding);

			// assert that decrypted text is the same as the original plaintext
			$this->assertEquals($plaintext, $decryptedText);
		}

		public function providerTestDecryptString()
		{
			return array(
				array('aaaaaa', true),
				array('aaaaaa', false),
				array('ȪȪȪȪȪȪȪȪ', true),
				array('ȪȪȪȪȪȪȪȪ', false),
				array('', true),
				array('', false)
			);
		}

		public function testGenerateUniquePasteID()
		{
			$ck = new \privypaste\CryptKeeper();

			$this->assertTrue($ck->generateUniquePasteID() !== '');
		}
	}
