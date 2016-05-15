create or replace package myexceptions as
  existing_user exception;
END;
/
create or replace package body myexceptions as
  existing_user exception;
END;
/
create or replace function stergereprodus(p_barcode product.barcode%type) return varchar2 as

 v_i product%rowtype;
Begin
	select * into v_i from  product where barcode=p_barcode;

	delete from product where barcode=p_barcode;
	return 'Produsul cu barcode '|| p_barcode || ' a fost sters cu succes';
	exception
		when no_data_found then
			return 'Produsul cu barcode '|| p_barcode || 'nu exista in baza de date';
END;
/
CREATE OR REPLACE FUNCTION voteazaProdus(p_barcode product.barcode%type, p_rating CHAR) RETURN VARCHAR2 AS
 v_i product%rowtype;
 i_rating product.rating%type;
Begin
	select * into v_i from  product where barcode=p_barcode;
  i_rating := TO_NUMBER(p_rating);
  
	UPDATE PRODUCT
  SET rating = (rating*nr_voturi+i_rating)/(nr_voturi+1)
  WHERE barcode = p_barcode;
  
  UPDATE PRODUCT
  SET nr_voturi = nr_voturi + 1
  WHERE barcode = p_barcode;
  
	return 'Votul a fost inregistrat cu succes pentru produsul cu barcode '|| p_barcode || '.';
	exception
		when no_data_found then
			return 'Produsul cu barcode '|| p_barcode || 'nu exista in baza de date';
END;
/
CREATE OR REPLACE PROCEDURE inregistrareUtilizator(p_email EDECUSER.EMAIL_ADDRESS%type, p_parola EDECUSER.PASSWORD%type,p_fname EDECUSER.FIRST_NAME%type,p_lname EDECUSER.LAST_NAME%TYPE,p_type EDECUSER.USERTYPE%TYPE) AS
v_rezultat INTEGER;
BEGIN
  SELECT COUNT(*) INTO v_rezultat FROM edecuser where EMAIL_ADDRESS = p_email;
  if v_rezultat = 1 THEN
    raise myexceptions.existing_user;
  ELSE
    INSERT INTO edecuser
    VALUES(p_email,p_fname,p_lname,p_parola,p_type);
  END IF;
  EXCEPTION
    WHEN myexceptions.existing_user THEN
      raise_application_error(-20020,'Utilizatorul exista deja',false);
END;
/
CREATE OR REPLACE FUNCTION ingrediente_preferate(p_user EDECUSER.EMAIL_ADDRESS%TYPE) RETURN VARCHAR2 AS
ingr_pref VARCHAR2(2000) := '';
CURSOR produse_revizuite IS
SELECT barcode FROM REVIEW
WHERE EMAIL_ADDRESS = p_user; 
BEGIN
  FOR produs IN produse_revizuite LOOP
    FOR ingredient IN
          (SELECT INGREDIENT_NAME FROM ingredient
          WHERE INGREDIENT_ID IN
          (SELECT ingredient_id from product_ingredient
          where barcode = produs.barcode)
          )          
    LOOP
      ingr_pref := ingr_pref || ingredient.ingredient_name || '<br>';
    END LOOP;
  END LOOP;
  IF (ingr_pref = '') THEN RETURN 'Nu exista ingrediente preferate pentru utilizatorul ' || p_user;
  ELSE RETURN ingr_pref;
  END IF;  
END;
/
INSERT INTO REVIEW
VALUES('raresmihai@psgbd.ro','5941021001674',sysdate,'Bun bunut');
