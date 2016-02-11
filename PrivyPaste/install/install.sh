#!/bin/bash

#########################################################################################
#
#	Installtion Script for PrivyPaste
#	https://github.com/magneticstain/PrivyPaste
#
#	Purpose: installs the PrivyPaste webapp as well as any dependencies
#
#########################################################################################

# CONSTANTS
# Formatting
lgHr="#################################################################################"
medHr="############################################"
# I/O
ROOT_APP_DIR='/opt/privypaste/'
APP_PKI_DIR='/opt/privypaste/pki/'
APP_LOGS_DIR='/opt/privypaste/logs/'
APP_SRC_DIR='/opt/privypaste/src/'
WEB_DIR='/opt/privypaste/web/'
WEB_DIR_ROOT="$WEB_DIR/PrivyPaste/"

# FUNCTIONS
function createKey
{
	# creates PKI certificates
	echo "$medHr"
	echo "Creating encryption key..."
	echo "$medHr"

	./generate_key.php

	return 0
}

function installApplicationFiles
{
	# creates the proper directories and installs the application files

	# create directories
    mkdir $ROOT_APP_DIR > /dev/null 2>&1
    mkdir $APP_PKI_DIR > /dev/null 2>&1
    mkdir $APP_LOGS_DIR > /dev/null 2>&1
    mkdir $APP_SRC_DIR > /dev/null 2>&1
    mkdir $WEB_DIR > /dev/null 2>&1
    mkdir $WEB_DIR_ROOT > /dev/null 2>&1

    # move to application directory
    cp -r ./* $APP_SRC_DIR > /dev/null 2>&1
    cp -r *.pem APP_CERTS_DIR > /dev/null 2>&1
    rsync -av ./PrivyPaste/ $WEB_DIR_ROOT --exclude 'src' --exclude 'tests' > /dev/null 2>&1

    # set perms
    # check which distro we're using
    apacheUser='www-data'
    if [ -f "/etc/redhat-release" ]
    then
        apacheUser='apache'
    fi

    chown -R root:root $ROOT_APP_DIR
    chmod -R 0755 $ROOT_APP_DIR
    chown -R root:$apacheUser $APP_PKI_DIR
    chmod -R 0750 $APP_PKI_DIR
    chown -R root:$apacheUser $APP_LOGS_DIR
    chmod -R 0750 $APP_LOGS_DIR
    chown -R $apacheUser:$apacheUser $WEB_DIR
    chmod -R 0751 $WEB_DIR

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

# prompt for key generation
createKey=$(promptUser 'Create key? [y/n]: ')
if [[ "$createKey" == "y" || "$createKey" == "Y" ]]
then
	createKey
fi

# install db schema
echo "$medHr"
echo " Installing database schema... "
echo "$medHr"

DB_NAME=$(grep -i db_name ../conf/db.php | cut -d "'" -f4)
if [ -z "$DB_NAME" ]
then
    # prompt user for db name
    echo -en "DATABASE NAME: "
    read DB_NAME
fi

mysqlPath=$(/usr/bin/which mysql)
$mysqlPath -u root -p $DB_NAME < ../src/db/privypaste_schema.sql
if [ "$?" -eq 0 ]
then
	echo "Schema installed succesfully!"
else
	echo "Could not install schema. Please verify your database configuration."
	exit 0
fi

# move application files
installApplicationFiles
echo "Application files and directories installed succesfully!"

echo "Installation complete!"