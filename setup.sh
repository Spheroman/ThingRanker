sudo apt update
sudo apt-get install -y apache2 php mariadb-server git php-mysql
a2enmod rewrite
git clone https://github.com/Spheroman/ThingRanker.git
ln -s ThingRanker /var/www/html
PHPINI=$(php -i | grep /.+/php.ini -oE)
echo -e "extension=pdo.so\nextension=pdo_mysql.so" >> "$PHPINI"
systemctl restart apache2