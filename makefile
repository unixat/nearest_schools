# 
# Makefile
# make setup - downloads and collates open data 
# make clean - removes existing data
# make distclean - clean entire project (e.g. testing on new syste)
# 

setup:
	rm -f schools.dat ukpostcodes.* 2>/dev/null
	composer install
	php setupData.php

download:
	rm -f schools.dat ukpostcodes.*
	php setupData.php

distclean:
	rm -f ukpostcodes.*
	rm -rf vendor
	rm -f composer.lock

clean:
	rm -f schools.dat ukpostcodes.*

backup:
	tar cvf ns.tar --exclude vendor *
