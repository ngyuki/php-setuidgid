# Composer Install Test

**Command**

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
sudo vendor/bin/setuidgid apache sample.php
```

**Output**

```
apache
```

**Archive**

```
wget -O - https://github.com/ngyuki/php-setuidgid/archive/master.tar.gz | tar xzvf -
```
