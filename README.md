# PrivyPaste

## Description
PrivyPaste is a standalone, private pastebin solution that utilizes encryption to increase privacy and security.

PrivyPaste can be easily scaled, making it a great option for both SMB's and enterprise users alike.

## Requirements
To install PrivyPaste, you must meet the following hardware and software requirements:

* Apache or nginx (latest version is preferable)
* PHP 5.3<=
* MySQL or MariaDB, v 5.1<=
* OpenSSL 1.0.1<=

## Installation
### Preparation
1. Generate RSA keys to your liking using openssl (if you do not want the installation script to automatically generate them for you)
```bash
    # generate private key
    openssl genrsa -out private_key.pem 4096

    # derive public key
    openssl rsa -pubout -in private_key.pem -out public_key.pem
```

### Installing
Once the public and private keys have been created, run the installation script which will guide you through the rest of the process of setting up PrivyPaste. 

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