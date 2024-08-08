FILES = run-test.php test-files
test: $(FILES)
	bpc -v \
	    -c ../extend/OurBlog/OurBlog/bpc.conf \
	    -o test \
	    -u phpunit \
	    -u phpunit-ext \
	    -u think-helper \
	    -u psr-simplecache \
	    -u think-orm \
	    -u ourblog \
	    -d display_errors=on \
	    -d suppress_runtime_too_many_arguments_warning=1 \
	    run-test.php \
	    --input-file test-files
clean:
	@rm -rf .bpc-build-* md5.map
	@rm -fv $(FILES) TESTCASE_LIST.php test
	@rm -rf MockClassFile
