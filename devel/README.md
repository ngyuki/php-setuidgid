# Composer Install Test

**Command**

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
sudo vendor/bin/setuidgid apache sample.php ; echo $?
```

**Output**

```
apache
0
```

**Command**

```
sudo vendor/bin/setuidgid apache ; echo $?
```

**Output**

```
Usage: php SetUidGid.php <user> <script.php>
255
```

**Archive**

```
wget -O - https://github.com/ngyuki/php-setuidgid/archive/master.tar.gz | tar xzvf -
```
