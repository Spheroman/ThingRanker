a2enmod rewrite
rm -rf /var/www/html
git clone https://github.com/Spheroman/ThingRanker.git /var/www/html
ROOTPASS=""
USERPASS=""
read -rp "New Database Root Password: " ROOTPASS
if [ -z "$ROOTPASS" ]; then
    echo "Root password cannot be empty. Please run the script again."
    exit 1
fi
read -rp "ThingRanker Database User Password: " USERPASS
if [ -z "$USERPASS" ]; then
    echo "User password cannot be empty. Please run the script again."
    exit 1
fi
mysql -e "DROP USER ''@'localhost'"
mysql -e "DROP USER ''@'$(hostname)'"
mysql -e "CREATE USER 'ThingRanker'@'localhost' IDENTIFIED WITH mysql_native_password BY '$USERPASS'"
mysql -e "DROP DATABASE IF EXISTS test"
mysql -e "CREATE DATABASE ThingRanker"
mysql -e "GRANT PRIVILEGE ON ThingRanker.* TO 'ThingRanker'@'localhost'"
mysql -e "USE DATABASE ThingRanker";
mysql -e "create table comps
(
    id          char(6)                    not null,
    name        varchar(100)               not null,
    time        datetime   default (now()) not null,
    updated     datetime   default (now()) null on update CURRENT_TIMESTAMP,
    started     tinyint(1) default 0       null,
    passcode    char(60)                   null,
    publicadd   tinyint(1) default 0       not null,
    addwhilerun tinyint(1) default 0       not null,
    playerlimit smallint   default -1      not null,
    pairingtype tinyint    default 0       not null,
    maxrounds   smallint   default -1      not null
);"
mysql -e "ALTER USER root@localhost IDENTIFIED BY '$ROOTPASS'"
mysql -e "FLUSH PRIVILEGES";
PHPINI=$(php -i | grep /.+/php.ini -oE)
rm -f "$PHPINI"
cp "${PHPINI}-development" "$PHPINI"
echo -e "extension=pdo.so\nextension=pdo_mysql.so" >> "$PHPINI"
rm -f /var/www/html/config.php
echo -e "<?php
\n
const DB_HOST = 'localhost';\n
const DB_USER = 'ThingRanker';\n
const DB_PASS = '$USERPASS';\n
const DB_NAME = 'ThingRanker';\n" >> /var/www/html/config.php

systemctl restart apache2