#!/bin/bash

#########################################################################################
#
#	Installtion Script for PrivyPaste
#	https://github.com/magneticstain/PrivyPaste
#
#	Purpose: installs the PrivyPaste webapp as well as any dependencies
#
#########################################################################################

lgHr="#################################################################################"
medHr="############################################"

# FUNCTIONS
function createCerts
{
	# creates PKI certificates
	echo "$medHr"
	echo "Creating certs..."
	echo "NOTE: make sure not to set a password for the private key!"
	echo "$medHr"

	# private key
	openssl genrsa -out private_key.pem 4096

	# public key
	openssl rsa -pubout -in private_key.pem -out public_key.pem

	return 0
}

function installApplicationFiles
{
	# creates the proper directories and installs the application files
	# create directories
    mkdir /opt/privypaste/ > /dev/null 2>&1
    mkdir /opt/privypaste/certs/ > /dev/null 2>&1
    mkdir /opt/privypaste/src/ > /dev/null 2>&1
    mkdir /opt/privypaste/web/ > /dev/null 2>&1

    # move to application directory
    cp -r ./* /opt/privypaste/src/ > /dev/null 2>&1
    cp -r *.pem /opt/privypaste/certs/ > /dev/null 2>&1
    cp -r ./PrivyPaste/ /opt/privypaste/web/ > /dev/null 2>&1

    # set perms
    chown -R root:root /opt/privypaste/
    chmod -R 0755 /opt/privypaste/
    chown -R root:www-data /opt/privypaste/certs/
    chmod -R 0750 /opt/privypaste/certs/
    chown -R www-data:www-data /opt/privypaste/web/
    chmod -R 0751 /opt/privypaste/web/

	return 0
}

function promptUser
{
	# prompt the user with a given string and return the user's answer
	read -p "$1" result

	echo "$result"
}

# MAIN
echo "$lgHr"
echo "	PrivyPaste Installer v1.0 "
echo "$lgHr"

# check for certs
hasCerts=$(promptUser 'Create certificates? [y/n]: ')
if [[ "$hasCerts" == "y" || "$hasCerts" == "Y" ]]
then
	createCerts
fi

# install db schema
echo "$medHr"
echo " Installing database schema... "
echo "$medHr"
mysqlPath=$(/usr/bin/which mysql)
$mysqlPath -u root -p privypaste < ./PrivyPaste/src/db/privypaste_schema.sql
if [ "$?" -eq 0 ]
then
	echo "Schema installed succesfully!"
else
	echo "Could not install schema. Please verify your database configuration."
	exit 0
fi

# move application files
installApplicationFiles

echo "Installation complete!"