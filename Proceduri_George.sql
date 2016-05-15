create or replace package myexceptions as
  illegal_password exception;
  illegal_barcode exception;
  unexisted_user exception;
END;
/
create or replace package body myexceptions as
  illegal_password exception;
  illegal_barcode exception;
  unexisted_user exception;
END;

create or replace procedure schimbareParola(p_email IN EdecUser.email_address%type, p_old_pass IN EdecUser.password%type,
  p_new_pass in EDECUSER.EMAIL_ADDRESS%type) AS
  v_verif integer;
begin
  if (LENGTH(TRIM(TRANSLATE(p_new_pass, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', ' '))) >= 0) then
    raise myexceptions.illegal_password;
  else select count(1) into v_verif from EdecUser where email_address=p_email and password=p_old_pass;
        if (v_verif=0) then
          raise myexceptions.unexisted_user;
        else update EdecUser set password=p_new_pass where email_address=p_email;
        END IF;
  end IF;
  EXCEPTION
  when myexceptions.illegal_password then
    raise_application_error(-20001,'Parola necorespunzatoare',false);
  when myexceptions.unexisted_user then
    raise_application_error(-20002,'Parola veche incorecta.',false);
end;

create or replace procedure adaugareProdus(p_barcode product.barcode%type,p_product_name PRODUCT.PRODUCT_NAME%type,
  p_quantity PRODUCT.QUANTITY%type, p_price PRODUCT.PRICE%type,p_image PRODUCT.IMAGE%type, p_categoryId PRODUCT.CATEGORY_ID%type) AS
begin
  if(LENGTH(TRIM(TRANSLATE(p_barcode, '0123456789', ' '))) >= 0) then
    raise myexceptions.illegal_barcode;
  else 
    insert into Product values(p_barcode,p_product_name,p_quantity,p_price,null,p_image,null,p_categoryId);
  end IF;
  EXCEPTION
    when myexceptions.illegal_barcode then
      raise_application_error(-20003,'Codul de bare trebuie sa contina doar cifre.');
end;