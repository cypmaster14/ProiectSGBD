create or replace trigger keywords
before insert on product
for each row
begin
  :New.keywords:=:New.barcode||' '||:New.product_name;
end;

create or replace trigger actualizare_keywords
after insert on product_ingredient
for each row
declare 
  v_ingredient_name ingredient.ingredient_name%type;
begin
  select ingredient_name into v_ingredient_name from ingredient where ingredient_id=:New.ingredient_id; 
  update product set keywords=product.keywords||' '|| v_ingredient_name where barcode=:New.barcode;
end;