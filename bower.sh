#!/bin/bash

if [ $# -eq 0 ]; then
	echo 'Please type a command, like "./bower.sh install jquery --save"'
	exit;
fi

docker run -it --rm -v $(pwd):/data digitallyseamless/nodejs-bower-grunt bower $@ 
