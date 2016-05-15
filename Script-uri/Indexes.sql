begin
  for i in 1..991000 LOOP
    insert into Product values('0'||i,'produs_'||i,'bucata','0.00 Lei/Bucata',null,'no image',null,null);
  END LOOP;
end;

create index raiting_index on Product(rating);
/
create index email_pass on EdecUser(email_address,password);
/
create index product_name_index on Product(lower(product_name));