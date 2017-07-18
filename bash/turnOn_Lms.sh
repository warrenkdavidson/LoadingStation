#!/bin/bash

HOST='10.10.34.100'
USER='q'
PASSWD='0'

#cd csv 

ftp -n -v $HOST << EOT
ascii
user $USER $PASSWD
prompt
cd "data/ls/"
put lsact.csv
bye
EOT