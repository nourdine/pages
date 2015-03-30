# pages

A library for analysing remote html pages and extract some basic info from them.

### Build it

```cmd
composer install
```

### Test it

(...assuming you are inside the library distribution folder) Let's first start up mini webapp to run the library against:

```cmd
php -S localhost:8080 -t ./test/webapp
```

Now that the target webapp is up un running we can actually run our tests:

```cmd
phpunit
```