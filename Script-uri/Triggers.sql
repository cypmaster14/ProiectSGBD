ALTER SESSION SET PLSCOPE_SETTINGS = 'IDENTIFIERS:NONE';
/
create or replace trigger keywords_and_rating
before insert on product
for each row
begin
  :New.keywords:=:New.barcode||' '||:New.product_name;
  :New.rating:=dbms_random.value(1.0,5.0);
end;
/
create or replace trigger update_keywords
after insert on product_ingredient
for each row
declare 
  v_ingredient_name ingredient.ingredient_name%type;
begin
  select ingredient_name into v_ingredient_name from ingredient where ingredient_id=:New.ingredient_id; 
  update product set keywords=product.keywords||' '|| v_ingredient_name where barcode=:New.barcode;
end;