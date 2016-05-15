create or replace FUNCTION logareValidaProiect(v_email EdecUser.email_address%type,v_parola EdecUser.password%type, v_userType EdecUser.userType%type) RETURN INTEGER IS
v_count INTEGER;
BEGIN
  select count(*) into v_count from EdecUser where email_address=v_email AND password=v_parola and userType=v_userType ;
  if(v_count=0) then
    return 0;
  else
    return 1;
  end if;
END logareValidaProiect;

/

create or replace procedure creeazaCampanie(v_CampaignName Campaing.campaing_name%type,v_Description Campaing.description%type,v_Barcode Campaing.barcode%type) IS
BEGIN
   --if(LENGTH(TRIM(TRANSLATE(v_barcode, '0123456789', ' '))) >= 0) then
   -- raise myexceptions.illegal_barcode;
    --else 
    insert into Campaing Values(null,v_CampaignName,v_Description,v_Barcode);
  --END IF;
  --EXCEPTION
    --when myexceptions.illegal_barcode then
      --raise_application_error(-20003,'Codul de bare trebuie sa contina doar cifre.');
END;

/

create or replace function cotareProdusPeCategorii RETURN VARCHAR2 IS
CURSOR c1 is select distinct p.product_name,p.rating,p.category_id from product p where rating = (
  select max(rating) from product where category_id=p.category_id);
v_product_name Product.product_Name %type;
v_rating Product.rating%type;
v_category_id Product.category_id%type;
v_Result varchar2(4000);
BEGIN
  OPEN c1;
  LOOP
    EXIT WHEN c1%NOTFOUND;
    FETCH c1 into v_product_name,v_rating,v_category_id;
    EXIT WHEN c1%NOTFOUND;
    v_Result:=' <h2>'||v_Result||v_product_name||'----'||v_rating||'---- '||v_category_id||' <br> ';
  END LOOP;
  return v_Result;
END;

/

CREATE OR REPLACE FUNCTION produsulSaptamanii return varchar2 IS
  v_Result varchar2(4000);
  v_product_name Product.product_name%type;
  CURSOR c1 is select distinct p.product_name from Product p where p.barcode IN (
  select barcode from Review where sysdate-post_date<=7) 
  AND rating = (
    select max(p.rating) from product p natural join Review where sysdate-post_date<=7);
  BEGIN
  OPEN c1;
  LOOP
    EXIT when c1%NOTFOUND;
    FETCH c1 into v_product_name;
      if(instr(v_Result,v_product_name)is null) then
      v_Result:=' <h1> '||v_Result||v_product_name || '</h1> <br> ';
      end if;
  END LOOP;
  CLOSE c1;
  return v_Result;
END;

/

create or replace procedure updateProdus(v_Barcode Product.barcode%type,v_Name Product.product_Name%type ,v_Quantity Product.Quantity%type, v_Price Product.Price%type,v_Image Product.IMAGE%type,v_Category_Id Product.category_Id%type) IS
BEGIN
  Update Product SET product_name=v_Name, Quantity=v_Quantity, Price=v_Price, image=v_Image, category_id=v_category_id where barcode=v_Barcode;
END;
