#!/bin/bash

rm -rf ./tp
rsync -a -f"+ */" -f"- *" . ./tp

for i in `cat src.list`
do
    filename=`basename -- $i`
    if [ "${filename##*.}" == "php" ]
    then
        echo "phptobpc $i"
        phptobpc $i > ./tp/$i
    else
        echo "cp       $i"
        cp $i ./tp/$i
    fi
done
cp src.list Makefile ./tp/
