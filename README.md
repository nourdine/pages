# pages

A library for analysing remote html pages and extract some basic info from them.

### build it

```cmd
composer install
```

### Test it

Assuming you are insed the library folder, let's first start an http server to run the library against:

```cmd
php -S localhost:8080 -t ./test/ws
```

And then run the actual tests:

```cmd
phpunit
```