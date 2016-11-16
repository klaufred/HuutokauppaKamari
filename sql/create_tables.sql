CREATE TABLE Customer(
  username varchar(50) NOT NULL UNIQUE PRIMARY KEY,
  password varchar(50) NOT NULL,
  address varchar(250) NOT NULL,
  email varchar(150) NOT NULL
);

CREATE TABLE Product(
  id SERIAL PRIMARY KEY,
  productName varchar(100) NOT NULL,
  description varchar(500),
  minimalPrice decimal NOT Null,
  saleBeginningDate timestamp NOT NULL,
  saleEndingDate timestamp NOT NULL
);

CREATE TABLE Offer(
  id SERIAL PRIMARY KEY,
  amount decimal NOT NULL,
  offerTime timestamp NOT NULL,
  product integer NOT NULL REFERENCES Product(id),
  customer varchar(50) NOT NULL REFERENCES Customer(username)
);

CREATE TABLE Category(
  id SERIAL PRIMARY KEY,
  categoryName varchar(50) NOT NULL
);

CREATE TABLE Productcategory(
  id SERIAL PRIMARY KEY,
  category integer REFERENCES Category(id),
  product integer REFERENCES Product(id) 
);

