-- Create JPN_USER table to store user information.
CREATE TABLE JPN_USER
(
  U_ID BIGINT NOT NULL COMMENT 'UNIQUE IDENTIFIER FOR THE USER', 
  U_FNAME VARCHAR(20) NOT NULL COMMENT 'THE FIRST NAME OF THE USER', 
  U_LNAME VARCHAR(20) COMMENT 'THE LAST NAME OF THE USER',
  U_PHONE VARCHAR(10) NOT NULL COMMENT 'THE PHONE NUMBER OF THE USER',
  U_EMAIL VARCHAR(40) NOT NULL COMMENT 'THE EMAIL ADDRESS OF THE USER',
  U_PWD_HASH VARBINARY(255) NOT NULL COMMENT 'PASSWORD HASH'
);

ALTER TABLE JPN_USER
  ADD PRIMARY KEY (U_ID);

ALTER TABLE JPN_USER
  ADD UNIQUE KEY uk_user_email (U_EMAIL);
  
ALTER TABLE JPN_CUSTOMER 
  MODIFY U_ID BIGINT NOT NULL AUTO_INCREMENT COMMENT 'UNIQUE IDENTIFIER FOR THE USER';


-- Create JPN_CUSTOMER table to store customer information including contact and identification details.
CREATE TABLE JPN_CUSTOMER 
( 
  CUST_ID BIGINT NOT NULL COMMENT 'UNIQUE IDENTIFIER FOR CUSTOMER',
  CUST_UID_TYPE VARCHAR(15) NOT NULL COMMENT 'THE IDENTIFICATION TYPE OF CUSTOMER', 
  CUST_UID_NO VARCHAR(20) NOT NULL COMMENT 'THE IDENTIFICATION NUMBER OF CUSTOMER' 
);

ALTER TABLE JPN_CUSTOMER 
  ADD PRIMARY KEY (CUST_ID);
  
ALTER TABLE JPN_CUSTOMER
  ADD CONSTRAINT fk_customer_user
    FOREIGN KEY (CUST_ID) REFERENCES JPN_USER (U_ID);  
  
  
-- Create JPN_AUTHOR table to store author details including name, address, and email.
CREATE TABLE JPN_AUTHOR 
( 
  A_ID BIGINT NOT NULL COMMENT 'UNIQUE IDENTIFIER FOR THE AUTHOR',
  A_STREET VARCHAR(20) NOT NULL COMMENT 'THE STREET OF AUTHOR ADDRESS', 
  A_CITY VARCHAR(20) NOT NULL COMMENT 'THE CITY OF AUTHOR ADDRESS', 
  A_STATE VARCHAR(2) NOT NULL COMMENT 'THE STATE OF AUTHOR ADDRESS', 
  A_COUNTRY VARCHAR(30) NOT NULL COMMENT 'COUNTRY OF THE AUTHOR', 
  A_ZIPCODE VARCHAR(5)  NOT NULL COMMENT 'THE ZIPCODE OF AUTHOR ADDRESS'
);

ALTER TABLE JPN_AUTHOR 
  ADD PRIMARY KEY (A_ID);
  
ALTER TABLE JPN_AUTHOR
  ADD CONSTRAINT fk_author_user
    FOREIGN KEY (A_ID) REFERENCES JPN_USER (U_ID);
  

-- Create JPN_EMPLOYEE table to store employee information.
CREATE TABLE JPN_EMPLOYEE
(
  E_ID BIGINT NOT NULL COMMENT 'UNIQUE IDENTIFIER FOR EMPLOYEE',
  E_HIREDATE DATE NOT NULL DEFAULT (CURRENT_DATE) COMMENT 'DATE THE EMPLOYEE WAS HIRED'
);

ALTER TABLE JPN_EMPLOYEE
  ADD PRIMARY KEY (E_ID);
  
ALTER TABLE JPN_EMPLOYEE
  ADD CONSTRAINT fk_employee_user
    FOREIGN KEY (E_ID) REFERENCES JPN_USER (U_ID);
	

-- Create JPN_ROLE table to store the list of roles available in the system.
CREATE TABLE JPN_ROLE
(
  ROLE_ID BIGINT NOT NULL COMMENT 'UNIQUE IDENTIFIER FOR ROLE',
  ROLE_NAME VARCHAR(20) NOT NULL COMMENT 'THE NAME OF ROLE'
);

ALTER TABLE JPN_ROLE
  ADD PRIMARY KEY (ROLE_ID);

ALTER TABLE JPN_ROLE
  ADD UNIQUE KEY uk_role_name (ROLE_NAME);
  

-- Create JPN_USER_ROLE table to map users to the roles they possess.
CREATE TABLE JPN_USER_ROLE
(
  U_ID BIGINT NOT NULL COMMENT 'UNIQUE IDENTIFIER FOR USER',
  ROLE_ID BIGINT NOT NULL COMMENT 'UNIQUE IDENTIFIER FOR ROLE'
);

ALTER TABLE JPN_USER_ROLE
  ADD PRIMARY KEY (U_ID, ROLE_ID);

ALTER TABLE JPN_USER_ROLE
  ADD CONSTRAINT fk_userrole_user
    FOREIGN KEY (U_ID) REFERENCES JPN_USER (U_ID);

ALTER TABLE JPN_USER_ROLE
  ADD CONSTRAINT fk_userrole_role
    FOREIGN KEY (ROLE_ID) REFERENCES JPN_ROLE (ROLE_ID);



--- Create a join table for exihibition reservation
CREATE TABLE JPN_CUSTOMER_SEMINAR (
  REGISTRATION_ID BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  CUST_ID BIGINT NOT NULL,
  E_ID BIGINT NOT NULL,
  FOREIGN KEY (CUST_ID) REFERENCES JPN_CUSTOMER(CUST_ID),
  FOREIGN KEY (E_ID) REFERENCES JPN_SEMINAR(E_ID)
);
