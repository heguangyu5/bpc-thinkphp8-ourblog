#!/bin/bash

[ "$PHPUNIT" == "" ] && {
    echo "Usage 1: PHPUNIT=phpunit-bpc ./run-all-tests.sh [GROUP]"
    echo "Usage 2: PHPUNIT=\"phpunit-bpc --bpc=.\" ./run-all-tests.sh [GROUP]"
    echo "Usage 3: PHPUNIT=./test ./run-all-tests.sh [GROUP]"
    exit
}

PHPUNIT="$PHPUNIT --bootstrap=bootstrap.php --stop-on-failure ."

run_group() {
    printf "\n\n\033[32;49;1m=== Run $1 ===\033[39;49;0m\n\n"
}

if [ "$1" != "" ]
then
    run_group $1
    $PHPUNIT --group $1
    exit
fi

run_group "Reg,Activate"
$PHPUNIT --group reg,activate

run_group "BaseDbTablesInit"
$PHPUNIT --group BaseDbTablesInit

run_group "Others"
$PHPUNIT --exclude-group reg,activate,BaseDbTablesInit
