# PrivyPaste

## Description
PrivyPaste is a standalone, private pastebin solution that utilizes encryption to increase privacy and security.

## Requirements
To install PrivyPaste, you must meet the following hardware and software requirements:

* Apache or nginx (latest version is preferable)
* PHP 5.3<=
* MySQL or MariaDB, v 5.1<=
* OpenSSL 1.0.1<=

## Installation
### Preparation
1) Generate RSA keys to your liking using openssl (if you do not want the installation script to automatically generate them for you)

    # generate private key
    openssl genrsa -out private_key.pem 4096

    # derive public key
    openssl rsa -pubout -in private_key.pem -out public_key.pem

## Usage
