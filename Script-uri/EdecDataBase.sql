create table EdecUser
(email_address varchar2(40) primary key,
first_Name varchar2(40) not null CHECK (LENGTH(TRIM(TRANSLATE(first_Name, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ' '))) < 0),
last_Name varchar2(40) not null CHECK (LENGTH(TRIM(TRANSLATE(last_Name, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ' '))) < 0),
password varchar2(40) not null CHECK (LENGTH(TRIM(TRANSLATE(password, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', ' '))) < 0));
/
create table EdecCategory
(category_id integer primary key,
category_name varchar2(60) not null);
/
create table Product
(barcode varchar2(20) primary key CHECK (LENGTH(TRIM(TRANSLATE(barcode, '0123456789', ' '))) < 0),
product_Name varchar2(200) not null,
quantity varchar2(6) not null,
price varchar2(20),
rating number(5,2),
image varchar2(90),
keywords varchar2(4000) not null,
category_id integer references EdecCategory(category_id) on delete cascade);
/
create table Ingredient
(ingredient_id integer primary key,
ingredient_name varchar2(300) not null);
/
create table Campaing
(campaing_id integer primary key,
campaing_name varchar2(50) not null,
description varchar2(1000) not null,
barcode varchar2(20) references Product(Barcode) on delete cascade);
/
create table EdecUser_Campaing
(campaing_id integer references campaing(campaing_id) on delete cascade,
email_address varchar2(40) references EdecUser(email_address) on delete cascade,
PRIMARY KEY (campaing_id, email_address));
/
create table Product_Ingredient
(ingredient_id integer references Ingredient(ingredient_id) on delete cascade,
barcode varchar2(20) references Product(barcode) on delete cascade,
PRIMARY KEY (ingredient_id, barcode));
/
create table Review
(email_address varchar2(40) references EdecUser(email_address) on delete cascade,
barcode varchar2(20) references Product(barcode) on delete cascade,
post_date date not null,
review_content varchar2(3000) not null,
PRIMARY KEY (email_address, barcode));
/
drop table EdecUser cascade constraints;
/
drop table campaing cascade constraints;
/
drop table edeccategory cascade constraints;
/
drop table edecuser_campaing cascade constraints;
/
drop table ingredient cascade constraints;
/
drop table product cascade constraints;
/
drop table product_ingredient cascade constraints;
/
drop table review cascade constraints;
/