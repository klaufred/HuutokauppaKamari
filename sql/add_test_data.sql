INSERT INTO Asiakas (tunnus, salasana, osoite, email) VALUES ('Klasu', '1234', 'Mikälie 1', 'klasu@gmail.com');
INSERT INTO Asiakas (tunnus, salasana, osoite, email) VALUES ('Masa', '123444', 'Mikälie 2', 'masa@gmail.com');
INSERT INTO Asiakas (tunnus, salasana, osoite, email) VALUES ('kati', '1234555', 'Mikälie 6', 'kati@gmail.com');

INSERT INTO Tuote (nimi, kuvaus, minimihinta, kaupanAlku, kaupanLoppu) VALUES ('Kone', 'Hyvä kone.', '588.8', '2016-11-14 12:00', '2026-11-14 12:00');
INSERT INTO Tuote (nimi, kuvaus, minimihinta, kaupanAlku, kaupanLoppu) VALUES ('Kone', 'Huono kone.', '590.8', '2016-11-14 12:00', '2026-11-14 12:00');

INSERT INTO Tarjous (maara, aika, tuote, asiakas) VALUES (1000.4, '2016-11-14 14:00', 1, 'Klasu');
INSERT INTO Tarjous (maara, aika, tuote, asiakas) VALUES (1000.4, '2016-11-14 14:00', 2, 'Klasu');

INSERT INTO Kategoria (nimi) VALUES('Koneet');


INSERT INTO Tuotekategoria (kategoria, tuote) VALUES(1,1);
INSERT INTO Tuotekategoria (kategoria, tuote) VALUES(1,2);
