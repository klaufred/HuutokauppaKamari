CREATE TABLE Asiakas(
  tunnus varchar(50) NOT NULL UNIQUE PRIMARY KEY,
  salasana varchar(50) NOT NULL,
  osoite varchar(250) NOT NULL,
  email varchar(150) NOT NULL
);

CREATE TABLE Tuote(
  id SERIAL PRIMARY KEY,
  nimi varchar(100) NOT NULL,
  kuvaus varchar(500),
  minimihinta decimal NOT Null,
  kaupanAlku timestamp NOT NULL,
  kaupanLoppu timestamp NOT NULL
);

CREATE TABLE Tarjous(
  id SERIAL PRIMARY KEY,
  maara decimal NOT NULL,
  aika timestamp NOT NULL,
  tuote integer NOT NULL REFERENCES Tuote(id),
  asiakas varchar(50) NOT NULL REFERENCES Asiakas(tunnus)
);

CREATE TABLE Kategoria(
  id SERIAL PRIMARY KEY,
  nimi varchar(50) NOT NULL
);

CREATE TABLE Tuotekategoria(
  id SERIAL PRIMARY KEY,
  kategoria integer REFERENCES Kategoria(id),
  tuote integer REFERENCES Tuote(id) 
);

