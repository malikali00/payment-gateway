# Simon Payments Gateway

PHP/MySQL Integrated Payment Processor written by Ari Asulin

`© 2017 Simon Payments, LLC. All rights reserved.`

## Prerequisites

```
PHP > 5.5.0
(extensions: php-curl php-soap php-imap php-mcrypt php-pgsql)
```

```
MySQL > 5.5
User    paylogic2@localhost
Pass    eVw{P7mphBn
```

## Installing

```
$ git checkout dev;
$ mysql -p -e "CREATE SCHEMA spg";
$ mysql -p spg < site/spg/spg.sql; 
$ mysql -p -e "GRANT SELECT, INSERT, UPDATE, DELETE ON spg.* TO 'spg'@'%';"
```

## Editing the Site Template without deployment

1. Open site/spg/test.html in a browser.

2. Edit the files:
```
web/view/theme/spg/assets/spg-theme.css
web/view/theme/spg/assets/spg-theme.js
```

3. Commit & Push
```
$ git add .;
$ git commit -m "Site Theme Changes";
$ git push;
```
