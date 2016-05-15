ALTER SESSION SET PLSCOPE_SETTINGS = 'IDENTIFIERS:NONE';
/
/*create or replace procedure update_keywords_proc(p_barcode in product.barcode%type) AS
  v_product_name product.product_name%type;
  v_keywords product.keywords%type;
begin
  select product_name into v_product_name from product where barcode=p_barcode;
  v_keywords:=p_barcode||' '||v_product_name;
  for curs in (select ingredient_name from product_ingredient natural join ingredient) LOOP
    v_keywords:=v_keywords||' '||curs.ingredient_name;
  end LOOP;
  update product set keywords=v_keywords where barcode=p_barcode;
end;*/
/
create or replace trigger keywords_and_rating
before insert on product
for each row
begin
  :New.keywords:=:New.barcode||' '||:New.product_name;
  :New.rating:=dbms_random.value(1.0,5.0);
end;
/
/*create or replace trigger update_keywords
after insert on product_ingredient
for each row
declare 
  v_ingredient_name ingredient.ingredient_name%type;
begin
  select ingredient_name into v_ingredient_name from ingredient where ingredient_id=:New.ingredient_id; 
  update product set keywords=product.keywords||' '|| v_ingredient_name where barcode=:New.barcode;
end;
/
create or replace trigger update_keywords_up_del
after update or delete on product_ingredient
for each row
begin
  IF UPDATING('ingredient_id') then
    update_keywords_proc(:New.barcode);
  ELSIF DELETING then
    UPDATE_KEYWORDS_PROC(:Old.barcode);
  END IF;
end;
/
create or replace trigger update_keywords_up_ing
after update on Igredient
for each row
begin
  for curs in (select barcode from product_ingredinet where ingredient_id=:New.Ingredient_id) LOOP
    null;
  end LOOP;
end;*/
