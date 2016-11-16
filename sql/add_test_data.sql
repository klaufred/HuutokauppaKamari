INSERT INTO Customer (username, password, address, email) VALUES ('Klasu', '1234', 'Mikälie 1', 'klasu@gmail.com');
INSERT INTO Customer (username, password, address, email) VALUES ('Masa', '123444', 'Mikälie 2', 'masa@gmail.com');
INSERT INTO Customer (username, password, address, email) VALUES ('kati', '1234555', 'Mikälie 6', 'kati@gmail.com');

INSERT INTO Product (productName, description, minimalPrice, saleBeginningDate, saleEndingDate) VALUES ('Kone', 'Hyvä kone.', '588.8', '2016-11-14 12:00', '2026-11-14 12:00');
INSERT INTO Product (productName, description, minimalPrice, saleBeginningDate, saleEndingDate) VALUES ('Kone', 'Huono kone.', '590.8', '2016-11-14 12:00', '2026-11-14 12:00');

INSERT INTO Offer (amount, offerTime, product, customer) VALUES (1000.4, '2016-11-14 14:00', 1, 'Klasu');
INSERT INTO Offer (amount, offerTime, product, customer) VALUES (1000.4, '2016-11-14 14:00', 2, 'Klasu');

INSERT INTO Category (categoryName) VALUES('Koneet');


INSERT INTO Productcategory (category, product) VALUES(1,1);
INSERT INTO Productcategory (category, product) VALUES(1,2);
