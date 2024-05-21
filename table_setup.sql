-- Script to set up the comps table. run before testing or whatever
drop table if exists comps;
create table comps
(
    id      char(6)                    not null,
    name    varchar(100)               not null,
    time    datetime   default (now()) not null,
    updated datetime                   null,
    started tinyint(1) default 0       not null
);