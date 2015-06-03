# PrivyPaste

## Description
PrivyPaste is a standalone, private pastebin solution that utilizes encryption to increase privacy and security.

PrivyPaste can be easily scaled, making it a great option for both SMB's and enterprise users alike.

## Requirements
To install PrivyPaste, you must meet the following hardware and software requirements:

* Apache (latest version is preferable)
* PHP 5.3<=
* MySQL or MariaDB, v 5.1<=
* OpenSSL 1.0.1<=

## Getting Started
### Preparation
1. Generate RSA keys to your liking using openssl (if you do not want the installation script to automatically generate them for you)
```bash
    # generate private key
    openssl genrsa -out private_key.pem 4096

    # derive public key
    openssl rsa -pubout -in private_key.pem -out public_key.pem
```
2. After installing all prerequisite software, create the database and database service account.
```bash
    # best practice is to set the host field to the narrowest subset possible
    mysql -e "CREATE USER 'privypaste'@'%' IDENTIFIED BY '<password>';"
    
    # create database
    mysql -e "CREATE DATABASE privypaste;"
    
    # set permissions for database service account
    mysql -e "GRANT SELECT, INSERT, UPDATE, DELETE ON privypaste.* TO 'privypaste'@'%'; FLUSH PRIVILEGES;"
```

3. Configure Apache how you would like. You can set it up as virtual hosts, SSL or non-SSL (we recommend SSL), etc.
  * The only requirements are that
    * the DocumentRoot directive must be set to the application file directory (/opt/privypaste/web by default)
    * the directory section for the application files has the `AllowOverride All` directive. [More info on that directive can be found here.](https://httpd.apache.org/docs/2.4/mod/core.html#allowoverride)
  * An example Apache config is show below, and should work with most installations.
```
<IfModule mod_ssl.c>
	<VirtualHost _default_:443>
	    # this is important, and must point towards this directory
		DocumentRoot /opt/privypaste/web

		LogLevel warn

		ErrorLog ${APACHE_LOG_DIR}/error.log
		CustomLog ${APACHE_LOG_DIR}/access.log combined

		SSLEngine on

        # Replace these with your own TLS certificate
		SSLCertificateFile	/etc/ssl/certs/ssl-cert-snakeoil.pem
		SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key

		<FilesMatch "\.(cgi|shtml|phtml|php)$">
				SSLOptions +StdEnvVars
		</FilesMatch>

		BrowserMatch "MSIE [2-6]" \
				nokeepalive ssl-unclean-shutdown \
				downgrade-1.0 force-response-1.0
		# MSIE 7 and newer should be able to use keepalive
		BrowserMatch "MSIE [17-9]" ssl-unclean-shutdown

		# Cipherlist [ via cipherli.st ]
		SSLCipherSuite EECDH+AESGCM:EDH+AESGCM:AES256+EECDH:AES256+EDH
		SSLProtocol All -SSLv2 -SSLv3
		SSLHonorCipherOrder On
		# Requires Apache >= 2.4
		SSLCompression off 
	</VirtualHost>
</IfModule>
```

4. Verify that PHP is installed along with the proper packages for it to work with Apache, and we should be set to install the application files.
  
### Installation
Once the public and private keys have been created, and the prereq software installed, run the installation script which will guide you through the rest of the process of setting up PrivyPaste. 

The installation script can be found at *TODO: ADD INSTALL SCRIPT LOCATION*

## Usage
Once you have the PrivyPaste webapp completely installed, browse to the URL that you have configured and you should see the home page. This is where new pastes are submitted, along with other various info.

### Submit a New Paste
To submit a new paste, enter your text into the main textarea, and once you've added all your text, press the "Upload Text" button right above it. If all goes well, it should redirect you to your new paste where you can grab the URL for sharing. 
If any errors occur, you will be notified via the error message box at the top of the page (below the information ticker).

### View a Paste
To view a previous uploaded page, simply enter the URL into your browser. It will automatically display the text - both in HTML and plaintext format - along with the paste's metadata.

## Contributing
We would love it if you decided to help contribute to PrivyPaste! Whether it's by fixing/creating bug reports, suggesting ideas, improving documentation - whatever it is you're good at.

### Pull Requests
If you would like to submit a pull request, we only ask that you follow the coding standards:

#### Variables
1. Naming convention must use camelCase fomatting.
2. Variable assignments must have surrounding spaces around the assignment operator.
```
    Example:
    $testVar = 'test';
    var testVar = 'test string';
```

#### If statements, for loops, while loops, etc
1. Brackets go on the same line as the function declaration if they are CSS or JS. Otherwise, brackets go on their own line (e.g. when creating a PHP script.)
2. There must be no spaces between the statement type and the first left parentheses.
3. There must be a space surrounding all operators in the statement declaration.
4. It is *preferred* that you order inequality operators in logical order (e.g. 0 < $positiveNumber instead of $positiveNumber < 0)
5. Logical operator symbols are *preferred* rather than keywords (|| instead of OR)
```
    Example:
    if(0 < $test && $test < 5)
    {
        // do things
    }
    
    for($i = 1; $i <= 10; $i++)
    {
        // do stuff within loop
    }
```

#### Functions
1. Brackets go on the same line as the function declaration if they are CSS or JS. Otherwise, brackets go on their own line (e.g. when creating a PHP script.)
2. Function names must use camelCase formatting.
3. There should be no spacing in between the function name and left parentheses.
4. A space is required after the comma listing each parameter.
5. If there are more than 5-10 parameters, parameters can be moved or split to different lines, with each line of parameters being tabbed at the beginning. 
5a.There should be a line break after the left parentheses and before the right parentheses.
```
    Example:
    public function testFunction($var1, $var2, $var3)
    {
        // do stuff
    }
    
    private function lotsOfParams(
        $var1, $var2, $var3, $var4, $var5, $var6
    )
    {
        // do stuff
    }
```

#### Classes
1. Class names must use camelCase formatting.
2. Each class section should be clearly labeled: constructor, setters, getters, and other functions
3. Filename for the class should be the same name as the class name, all lowercase. This is to ensure it works properly with the autoloader.
4. Brackets should be on their own line.
```
    Example:
    class TestClass
    {
        private $var1 = '';
        
        // CONSTRUCTORS
        ......
        
        // SETTERS
        ......
        
        // GETTERS
        ......
        
        // OTHER FUNCTIONS
        ......
    }
```

### Bug Reporting and Responsible Disclosure
#### Bugs
If you find a bug with PrivyPaste - let us know! We try to improve PrivyPaste everyday, but we can't catch everything. 
If you would like to let us know about a bug, please submit a bug report using the issue tracker for this project (located to your right). Please try to be as thorough as possible to help speed up remediation.

#### Security Issues/Responsible Disclosure
If you find a security vulnerability or general security issue, let us know about that too. You can use the issue tracker for this project (located to your right), or if it is a 
potentially high-severity bug, please let us one of the maintainers of PrivyPaste know via private message. This will give us time to implement a fix and users to patch their installations while still protecting users in the meantime.