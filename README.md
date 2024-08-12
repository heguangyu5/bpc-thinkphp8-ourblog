# BPC: How to make and run tests?

```shell
/usr/share/php
├── OurBlog -> /path/to/bpc-thinkphp8-ourblog/extend/OurBlog
├── phpunit-ext -> /path/to/phpunit-ext
├── Psr
│   └── SimpleCache -> /path/to/bpc-psr-simple-cache-3.0.0/src
├── think-helper
│   └── think -> /path/to/bpc-topthink-think-helper-3.1.6/src
├── think-orm
│   └── think -> /path/to/bpc-topthink-think-orm-3.0.20/src
└── think-orm-stubs -> /path/to/bpc-topthink-think-orm-stubs-3.0.20

cd extend/OurBlog/OurBlog
make && sudo make install-libourblog
sudo ldconfig

cd tests
./sync-test-db.sh
PHPUNIT="phpunit-bpc --bpc=." ./run-all-tests.sh

make
./sync-test-db.sh
PHPUNIT=./test ./run-all-tests.sh
```

# BPC: How to make and run ourblog?

```shell
/usr/share/php
├── OurBlog -> /path/to/bpc-thinkphp8-ourblog/extend/OurBlog
├── Psr
│   ├── Container -> /path/to/bpc-psr-container-2.0.2/src
│   ├── Http
│   │   └── Message -> /path/to/bpc-psr-http-message-1.1/src
│   ├── Log -> /path/to/bpc-psr-log-3.0.0/src
│   └── SimpleCache -> /path/to/bpc-psr-simple-cache-3.0.0/src
├── think-helper
│   └── think -> /path/to/bpc-topthink-think-helper-3.1.6/src
├── think-orm
│   └── think -> /path/to/bpc-topthink-think-orm-3.0.20/src
└── topthink-framework -> /path/to/bpc-topthink-framework-8.0.3

make
make run
```

