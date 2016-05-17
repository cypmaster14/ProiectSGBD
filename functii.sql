create or replace function cautareProdus(p_barcode varchar2) return varchar2 as

	cursor c_type (p_bar varchar2) is 
		select product_name,quantity,price,rating,image from product
		where barcode=p_bar;

	v_return_message varchar2(2000);


Begin
	
	for v_i in c_type(p_barcode) LOOP
		v_return_message:=v_i.product_name||'$'||v_i.quantity||'$'||v_i.price||'$'||v_i.rating||'$'||v_i.image;
	END LOOP;

	v_return_message:=v_return_message||'$'||getIngredients(p_barcode);

	return  v_return_message;

	exception
		when no_data_found then	
			return 'Produsul cu barcode-ul:'||p_barcode||' nu exista in baza de date';
END;




create or replace function getIngredients(p_barcode varchar2) return varchar2 as

	cursor c_type(p_bar varchar2) is
		select ingredient_name from ingredient i
		join product_ingredient pi
		on pi.ingredient_id=i.ingredient_id
		where barcode=p_bar;


	v_return_message varchar2(2000);
	v_ingredient  ingredient.ingredient_name%type;

Begin
	
	for v_i in c_type(p_barcode) LOOP
		v_return_message:=v_return_message||v_i.ingredient_name||'$';
	END LOOP;

	v_return_message:=substr(v_return_message,1,length(v_return_message)-1);
  --Dbms_Output.Put_Line('Ingrediente:'||v_return_message);
  return V_Return_Message;

	exception
		when no_data_found then	
			return 'Produsul cu barcode-ul:'||p_barcode||' nu exista in baza de date';

END;

Declare
 v_mesaj varchar2(2000);
BEGin
	v_mesaj:=cautareProdus(?);
	dbms_output.put_line(v_mesaj);
END;



create or replace function stergereUtilizator(p_email edecuser.email_address%type) return varchar2 as

 v_i edecuser%rowtype;
Begin
	select * into v_i from  edecuser where email_address=p_email;

	delete from edecuser where email_address=p_email;
	return 'Utilizatorul cu email-ul'||p_email || 'a fost sters cu succes';
	exception
		when no_data_found then
			return 'Utilizatorul cu email-ul'||p_email || 'nu exista in baza de date';
END;


DECLARE
  v_mesaj varchar(2000);
BEGin
 v_mesaj:=stergereutilizator('adsad@fifa.ccom');
 dbms_output.put_line(v_mesaj);
 END;



create or replace function plasareComentariu(p_barcode Product.barcode%type, p_email edecuser.email_address%type, p_comentariu varchar2 ,p_dataPostare review.post_date %type) return varchar2 as

	v_statement varchar2(2000);
  v_aux varchar(2);
Begin
	
	select '1' into v_aux from Product
	where barcode=p_barcode;
  v_statement:='insert into review values (' ||p_email||','||p_barcode||','||p_dataPostare||','||p_comentariu||')';
  --execute immediate v_statement;
  insert into review values (p_email,p_barcode,p_dataPostare,p_comentariu);
  dbms_output.put_line(v_statement);
  return 'Comentaril a fost plasat cu succes';

	exception
		when no_data_found then
			return 'Produsul pe care vreti sa il comentati nu exista in baza de date';
END;

declare
  v_mesaj varchar2(2000);
Begin
  v_mesaj:=plasareComentariu('4017100370007','fifa@fifa.com','asdsadsadsafsa',sysdate);
  dbms_output.put_line(v_mesaj);
END;





create or replace function getCategory (p_categorie EdecCategory.category_name%type) return number is
	v_id EdecCategory.category_name%type;
BEGin
	select category_id into v_id from EdecCategory where category_name like p_categorie||'%';
	return v_id;
 END;



create or replace function top10(p_categorie EdecCategory.category_name%type) return varchar2 is
	v_mesaj varchar2(2000);
Begin
	for v_i in ( select product_name from ( select product_name from Product where category_id=getCategory(p_categorie)  order by rating desc) where rownum<=10 ) LOOP
		v_mesaj:=v_mesaj || v_i.product_name||'$';
	END loop;
	v_mesaj:=substr(v_mesaj,1,length(v_mesaj)-1);
	return v_mesaj;
END;







----Exportul CSV

create or replace procedure exportCSV is
	type cursor_type is ref cursor;
	c cursor_type;
	d cursor_type;
	TYPE columns_names IS TABLE OF VARCHAR2(50);
	v_columns columns_names;
	stmt varchar2(10000);
	rezultat varchar2(1000);
	insertStmt varchar2(10000):='insert into ';
	username varchar2(200);
	obiect varchar2(200);
	tip varchar2(200);
	comanda varchar2(10000);
	select_stmt varchar2(1000);
	file_id UTL_FILE.FILE_TYPE;
	--select object_name ,object_type from user_objects where object_type in ('TABLE') and object_name in (upper('Product'))

Begin
	select user into username from dual;
	for v_i in (select object_name ,object_type from user_objects where object_type in ('TABLE') and object_name in (upper('EdecUser'),upper('EdecCategory'),upper('Ingredient'),upper('Campaing'),upper('EdecUser_Campaing'),upper('Product_Ingredient'),upper('Review'))) LOOP
		file_id:=UTL_FILE.FOPEN('WORKSPACE',v_i.object_name||'42.csv','W');
		select column_name BULK COLLECT into v_columns from all_tab_columns
		where table_name=upper(v_i.object_name);
		stmt:='select';
		for v_j in v_columns.first .. v_columns.last LOOP
			stmt:=stmt||''''''||'||'|| v_columns(v_j)||'||'||'''''' ||'||'',''||';
		END LOOP;
		stmt:=substr(stmt,1,length(stmt)-7);
    stmt:=stmt||' from '||v_i.object_name;
    open d for stmt;   
    insertStmt:='';
      	LOOP
      		fetch d into rezultat;
      		exit when d%NOTFOUND;          
      		insertStmt:=insertStmt||rezultat;         
      		UTL_FILE.PUT(file_id,insertStmt);
      		UTL_FILE.NEW_LINE(file_id,1);
      		dbms_output.put_line('Am pus in fisier:');
          insertStmt:='';
      	END LOOP;
      	UTL_FILE.FCLOSE(file_id);
	END LOOP;
END;



Declare
Begin
	exportCSV();
END;


----Importul CSV

create or replace procedure importCSV is

	type cursor_type is ref cursor;
	c_i cursor_type;
	v_id EdecCategory.category_id%type;
	v_name EdecCategory.category_name%type;
	v_statement varchar2(2000);

Begin
	execute immediate 'create table xter_category(
						category_id number(38),
						category_name varchar2(60)
						) 
						organization external
						(
							default directory WORKSPACE
							access parameters
							(records delimited by newline fields terminated by '','')
							location (''EDECCATEGORY42.csv'')
						)';

	open c_i for 'select * from xter_category';
	loop
		fetch c_i into v_id,v_name;
		exit when c_i%NOTFOUND;
		v_statement:='insert into EdecCategory values (:id,:name)';
		execute immediate v_statement using v_id,v_name;
	END LOOP;
	execute immediate 'drop table xter_category';
END;





Declare
Begin
	importCSV();
END;