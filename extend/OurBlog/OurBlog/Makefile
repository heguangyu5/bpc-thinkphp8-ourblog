libourblog.so:
	phptobpc ../Util.php > Util.php
	phptobpc ../Upload.php > Upload.php
	phptobpc ../User.php > User.php
	phptobpc ../Post.php > Post.php
	bpc -v \
	    -c bpc.conf  \
	    -l ourblog \
	    -u think-helper \
	    -u psr-simplecache \
	    -u think-orm \
	    Util.php Upload.php User.php Post.php

install-libourblog:
	bpc -l ourblog --install
