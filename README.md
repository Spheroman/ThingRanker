# ThingRanker
Rank things using a pairwise competition with Bayesian Elo Ratings
## Database
> :warning: I understand now that this is **NOT** how you structure a relational database. It is a product of its time and should not be referenced anymore.

So the app has 1 preset table and will make more tables as they are needed. Here's an explanation of them: <br> <br>
This is the comps table, it stores a list of all the different comps along with their options and setup settings, including their name and unique ID.
<table>
  <tr>
    <td>id</td><td>name</td><td>time</td><td>updated</td><td>started</td>
  </tr>
  <tr>
    <td>unique string of random lowercase letters and numbers. 6 digits long.</td>
    <td>name of the competition</td>
    <td>time the competition was created</td>
    <td>last time the competition was modified in any way</td>
    <td>if the competition is running or not</td>
  </tr>
</table>

Then for every entry in the comps table, theres a new table with name `id`, where `id` is the id from the comps table. This table stores the items in the competition as well as their calculated rankings.
<table>
  <tr>
    <td>id</td><td>name</td><td>rating</td><td>variance</td>
  </tr>
  <tr>
    <td>an auto incrementing integer starting at 1</td>
    <td>name of the entry in the competition</td>
    <td>Glicko-2 rating. default 1000</td>
    <td>Glicko-2 variance. default 500</td>
  </tr>
</table>

We also have a head2head table where we store the results of the pairings, as well as the upcoming pairings. its title is `id_h2h` where `id` is (you guessed it) the id. As pairings are generated and completed, they are added to this table. This table will be recreated every time the competition is started. It can be used to view matchup stats once implementation for those are complete, alongside recalculating ratings based on different elo systems.
<table>
  <tr>
    <td>id</td><td>p1</td><td>p2</td><td>winner</td><td>player</td>
  </tr>
  <tr>
    <td>pairing id</td>
    <td>first item</td>
    <td>second item</td>
    <td>winner of the pairing</td>
    <td>submitter's name (optional)</td>
  </tr>
</table>

That's what we have so far.

## Page Layout and Concept

https://www.figma.com/design/fkUNiNu0bEyCh0Jy0L1Cwo/ThingRanker?node-id=0%3A1&t=ywimVgiXGstqoxEx-1

So, there's going to probably be about 5 pages. A home page, a setup page, a competition landing page, a pairing page, and a results page.

### Home Page
This is where we create the competition. Basically there's a a bunch of information about how the pairings work and whatnot and then competition name field. Basically go to a website that has something similar, and imagine that but without any logins or annoying stuff. When we create a new competition, we redirect to the setup page. If we have enough time, we will also add the ability to use a pin or password to lock the setup information to require authentication.
### Setup Page
This is where we will have all the setup options. You can add an item to the competition, choose the ranking system, choose a pairing system, enable people to add items when they're on the landing page or while the competition is running, start, reset, and delete the competition, and whatever else we think might be useful. Once the tournament starts, this page can show a QR code, and most of the options will be locked. If possible, we figure out websockets or submit arrays or some way to submit multiple things at once, otherwise it will be annoying to use.
### Landing Page
The landing page will be where the non-admins see the competition. It can allow them to add items to the competition as well as the ability to see the current items, but not much else. Once the tournament starts, this is where they can be redirected to the pairing page, although we might just make the pairing page override the landing page while the competition runs. Ideally, we can figure out how to automatically refresh or redirect when it's started using websockets. That will be complicated though.
### Pairing Page
Pairings are shown on this page. It will show 2 options, and the user has to pick between the two, and whichever wins will be recorded in the database. Logic will have to be applied to prevent repeated or invalid post requests. It will also store the amount of submissions and can have a minimum and a maximum amount of submissions for it to be considered valid.
### Ranking Page
This page will show the rankings of the items after the competition finishes or halfway through the competition. Doesn't need much else, other than maybe the ability to reset it.

## Debian Installation
To install the project, start by installing apache2, php, mariadb-server, git, and php-mysql using `sudo apt install apache2 php mariadb-server git php-mysql` or your other favorite package manager. Next, run `sudo a2enmod rewrite`. This enables .htaccess files and the RewriteEngine. <br><br>
To clone the repository into the /var/www/html directory, you can remove the current one with `sudo rm -rf /var/www/html` and clone into it with `sudo git clone "https://github.com/Spheroman/ThingRanker.git" /var/www/html`. <br><br>
Next, we have to setup the database. You can either modify the table_setup.sql file to use a new password, then run it with `mysql < /var/www/html/table_setup.sql`, or you can run the commands manually. If you just installed mariadb, then your default password will be blank and you can log in with `mysql`. If you changed your password already, login using `mysql -p` and run the following commands: <br>
```
CREATE USER ThingRanker@localhost IDENTIFIED WITH mysql_native_password USING PASSWORD('your password here');
DROP DATABASE IF EXISTS test;
CREATE DATABASE ThingRanker;
GRANT ALL PRIVILEGES ON ThingRanker.* TO ThingRanker@localhost;
USE ThingRanker;
create table comps
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
);
ALTER USER root@localhost IDENTIFIED BY 'a secure password';
```
Make sure that you use the password you want the app to use in the first line. Then, modify config.php with `sudo nano /var/www/html/config.php` and update DB_PASS to use your new password. <br><br>
After the database is finished being set up, now we have to modify the PHP and apache2 config files. First, run `php -i | grep /.+/php.ini -oE` to find the path of your php.ini file. Once you find it, open it with `sudo nano` and uncomment the line that says `extension=pdo_mysql`. Next, edit the apache2 config file with `sudo nano /etc/apache2/apache2.conf` and go to line 172. There should be a line that says AllowOverride, and you need to set that value to be `All`. <br> <br>
We've modified the apache and php config files, so now we need to restart apache webserver. You can do that with `sudo systemctl restart apache2`. Once that is complete, you should be able to access the ThingRanker homepage on your device's IP address.

## Dev Setup
To work on the project, you need to install MySql, PHP, and Apache2. Connect PHP to Apache2 and make sure that PDO_MySql is enabled. In Apache2, enabe mod_rewrite.so for the links to work correctly. In MySql, create a database named `test` with username `root` and password `billybob`. Once that's all set up correctly, run the table_setup.sql while logged into the database. If you're on windows, you can use WAMP to make setup easier.
