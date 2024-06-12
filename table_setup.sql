-- Script to set up the comps table. run before testing or server setup
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
