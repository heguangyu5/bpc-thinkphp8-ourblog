all:
	./bpc-prepare.sh
	$(MAKE) -C ./tp tp8-ourblog-althttpd
	mv tp/tp8-ourblog-althttpd .

tp8-ourblog-althttpd:
	bpc -v \
	    -c ../bpc.conf          \
	    --althttpd              \
	    -o tp8-ourblog-althttpd \
	    -d display_errors=on            \
	    -d memory_limit=256M            \
	    -d date.timezone=Asia/Shanghai  \
	    -d suppress_runtime_too_many_arguments_warning=1 \
	    -u think-helper         \
	    -u psr-simplecache      \
	    -u think-orm            \
	    -u psr-http-message     \
	    -u psr-log              \
	    -u psr-container        \
	    -u topthink-framework   \
	    -u ourblog              \
	    --input-file src.list

run:
	rm -rf /tmp/tp
	mkdir /tmp/tp
	mkdir /tmp/tp/bin
	mkdir /tmp/tp/public
	mkdir -m 0777 /tmp/tp/runtime
	cp tp8-ourblog-althttpd /tmp/tp/bin/
	/tmp/tp/bin/tp8-ourblog-althttpd --project-name tp/public --port 8080 --project-entry tp/public/index.php --root /tmp/tp/public
