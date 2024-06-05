-- Script to set up the comps table. run before testing or server setup
drop table if exists comps;
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
