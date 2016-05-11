create table users
( id int unsigned primary key auto_increment,
names varchar(100) not null, 
gender int not null,
birth_date datetime, 
city varchar(50), 
phone varchar(20),
comment text );