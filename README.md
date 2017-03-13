# Simon Payments Integrated Gateway

PHP/MySQL Integrated Payment Processor by Ari.Asulin@gmail.com

`© 2017 Simon Payments, LLC. All rights reserved.`

## Prerequisites

```
PHP > 5.5.0
(extensions: php-curl php-soap php-imap php-mcrypt php-pgsql)
```

```
MySQL > 5.5
```

## Installing Instructions


Check out the development repository
```
$ git checkout dev;
```

Create the 'spg' database
```
$ mysql -p -e "CREATE SCHEMA spg";
```

Import the database schema
```
$ mysql -p spg < site/spg/spg.sql; 
```

Grant user 'spg' rights to the new database
```
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

Check which files were modified
```
$ git status;
$ git diff;
```

Add the modified files 
```
$ git add .;
```

Commit the changes with a message
```
$ git commit -m "Site Theme Changes";
```

Push the changes to the repository
```
$ git push;
```
