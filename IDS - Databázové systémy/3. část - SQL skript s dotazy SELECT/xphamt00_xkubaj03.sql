/*Tato ��st vytv��� sekvence pro po��t�n� PK*/
create SEQUENCE ID_klienta_seq minvalue 1 CACHE 10;
create SEQUENCE ID_vypujcky_seq minvalue 1 CACHE 10;
create SEQUENCE ID_zaznamu_seq minvalue 1 CACHE 10;
create SEQUENCE ID_zamestnance_seq minvalue 1 CACHE 10;
create SEQUENCE ID_doplnku_seq minvalue 1 CACHE 10;
create SEQUENCE ID_kostymu_seq minvalue 1 CACHE 10;

/*Tato ��st vytv��� tabulky a relace mezi tabulkami*/
CREATE TABLE Klient (
    ID_klienta  INTEGER DEFAULT(ID_klienta_seq.nextval) PRIMARY KEY CHECK(REGEXP_LIKE(ID_klienta,'[^0-9$]*','i')) CHECK(ID_klienta > 0) NOT NULL,
    Jmeno varchar(50) NOT NULL,
    Prijmeni varchar(50) NOT NULL,
    DatNarozeni DATE NOT NULL
);

CREATE TABLE Vypujcka (
    ID_vypujcky INTEGER DEFAULT(ID_vypujcky_seq.nextval) PRIMARY KEY CHECK(REGEXP_LIKE(ID_vypujcky,'[^0-9$]*','i')) CHECK(ID_vypujcky > 0) NOT NULL,
    Od DATE NOT NULL,
    Do DATE NOT NULL,
    Cena float(8) NOT NULL                     
);  

CREATE TABLE Sjednal (
    Od DATE NOT NULL,
    Do DATE NOT NULL,
    ID_vypujcky INTEGER NOT NULL,
    ID_klienta INTEGER NOT NULL,
    
    CONSTRAINT PK_sjednal PRIMARY KEY (ID_vypujcky,ID_klienta),

    CONSTRAINT Sjednal_FK_Klient
		FOREIGN KEY (ID_klienta)
		REFERENCES Klient(ID_klienta),
    CONSTRAINT Sjednal_FK_Vypujcka
		FOREIGN KEY (ID_vypujcky)
		REFERENCES Vypujcka(ID_vypujcky)
);

CREATE TABLE Zaznam (
    ID_zaznamu INTEGER DEFAULT(ID_zaznamu_seq.nextval) PRIMARY KEY CHECK(REGEXP_LIKE(ID_zaznamu,'[^0-9$]*','i')) CHECK(ID_zaznamu > 0) NOT NULL, 
    Cena float(8) NOT NULL,
    Akce VARCHAR(255),
    Vraceno DATE NOT NULL,
    Informace VARCHAR(255),
    ID_vypujcky INTEGER NOT NULL,
    
    CONSTRAINT Zaznam_FK_Vypujcka
		FOREIGN KEY (ID_vypujcky)
		REFERENCES Vypujcka(ID_vypujcky)
);

CREATE TABLE Zamestnanec (
    ID_zamestnance INTEGER DEFAULT(ID_zamestnance_seq.nextval) PRIMARY KEY CHECK(REGEXP_LIKE(ID_zamestnance,'[^0-9$]*','i')) CHECK(ID_zamestnance > 0) NOT NULL,
    Jmeno varchar(255) NOT NULL,
    Prijmeni varchar(255) NOT NULL,
    DatNarozeni DATE NOT NULL,
    Adresa varchar(255) NOT NULL,
    Tel_Cislo int NOT NULL,
    Email varchar(255)
);

CREATE TABLE Doplnky (
    ID_doplnku INTEGER DEFAULT(ID_doplnku_seq.nextval) PRIMARY KEY CHECK(REGEXP_LIKE(ID_doplnku,'[^0-9$]*','i')) CHECK(ID_doplnku > 0) NOT NULL,
    Nazev varchar(50) NOT NULL,
    Vyrobce varchar(50),
    Material varchar(50),
    Popis varchar(510),
    Cena float(8) NOT NULL,
    Dostupnost varchar(255),
    Stari INTEGER NOT NULL,
    ID_zamestnance INTEGER NOT NULL,
    
    CONSTRAINT Doplnky_FK_Zamestnanec
		FOREIGN KEY (ID_zamestnance)
		REFERENCES Zamestnanec(ID_zamestnance)
);

CREATE TABLE Kostym (
    ID_kostymu INTEGER DEFAULT(ID_kostymu_seq.nextval) PRIMARY KEY CHECK(REGEXP_LIKE(ID_kostymu,'[^0-9$]*','i')) CHECK(ID_kostymu > 0) NOT NULL,
    Nazev varchar(50) NOT NULL,
    Vyrobce varchar(50),
    Material varchar(50),
    Popis varchar(510),
    Cena float(8) NOT NULL,
    Velikost varchar(4),
    Dostupnost char(1),
    Barva varchar(50),
    Stari INTEGER NOT NULL, 
    Stav varchar(255),
    ID_zamestnance INTEGER NOT NULL,
    
    CONSTRAINT Kostym_FK_Zamestnanec
		FOREIGN KEY (ID_zamestnance)
		REFERENCES Zamestnanec(ID_zamestnance)
);

CREATE TABLE Zprostredkoval (
    ID_vypujcky INTEGER NOT NULL, 
    ID_zamestnance INTEGER NOT NULL,
    
    CONSTRAINT PK_zprostredkoval PRIMARY KEY (ID_vypujcky,ID_zamestnance),
    
    CONSTRAINT Zprostredkoval_FK_Zamestnanec
		FOREIGN KEY (ID_zamestnance)
		REFERENCES Zamestnanec(ID_zamestnance),
    CONSTRAINT Zprostredkoval_FK_Vypujcka
		FOREIGN KEY (ID_vypujcky)
		REFERENCES Vypujcka(ID_vypujcky)
);

CREATE TABLE Je_soucasti_kostym (
    ID_vypujcky INTEGER NOT NULL, 
    ID_kostymu INTEGER NOT NULL,
    
    CONSTRAINT PK_je_soucasti_kostym PRIMARY KEY (ID_vypujcky,ID_kostymu),
    
    CONSTRAINT Je_soucasti_kostym_FK_Vypujcka
		FOREIGN KEY (ID_vypujcky)
		REFERENCES Vypujcka(ID_vypujcky),
    CONSTRAINT Je_soucasti_kostym_FK_Kostym
		FOREIGN KEY (ID_kostymu)
		REFERENCES Kostym(ID_kostymu)
);

CREATE TABLE Je_soucasti_doplnky (
    ID_vypujcky INTEGER NOT NULL, 
    ID_doplnku INTEGER NOT NULL,
    
    CONSTRAINT PK_je_soucasti_doplnky PRIMARY KEY (ID_vypujcky,ID_doplnku),
    
    CONSTRAINT Je_soucasti_doplnky_FK_Vypujcka
		FOREIGN KEY (ID_vypujcky)
		REFERENCES Vypujcka(ID_vypujcky),
    CONSTRAINT Je_soucasti_doplnky_FK_Doplnky
		FOREIGN KEY (ID_doplnku)
		REFERENCES Doplnky(ID_doplnku)
);

CREATE TABLE Je_doporucovan (
    ID_kostymu INTEGER NOT NULL, 
    ID_doplnku INTEGER NOT NULL,
    
    CONSTRAINT PK_je_doporucovan PRIMARY KEY (ID_kostymu,ID_doplnku),

    CONSTRAINT Je_doporucovan_FK_Kostym
		FOREIGN KEY (ID_kostymu)
		REFERENCES Kostym(ID_kostymu),
    CONSTRAINT Je_doporucovan_FK_Doplnky
		FOREIGN KEY (ID_doplnku)
		REFERENCES Doplnky(ID_doplnku)
);

/*Zde se vkl�daj� data*/

INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Franti�ek', 'Vom��ka', DATE '1998-12-05','Brno', 420666353, 'vomFrant@gmail.com');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Jaroslav', 'Pr�sk', DATE '1988-11-25','Znojmo', 976776876, 'JardaVomacka@seznam.com');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Stanislav', 'Prav�k', DATE '1999-05-16','Havl��k�v Brod', 123123123, 'stanik88@seznam.cz');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Libor', 'Lavi�ka', DATE '2002-06-19','Praha', 976776876, 'libor.lavicka@gmail.com');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Lojza', 'Kom�n', DATE '2001-07-21','Svitavy', 722656879, 'milujuqueeny@gmail.com');

INSERT INTO Klient values( ID_klienta_seq.nextval,'Boris', 'Pa�tika', DATE '2003-03-28');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Filip', 'Vrzal', DATE '1986-09-29');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Radek', 'Mat�jka', DATE '1969-02-13');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Bo�ek', 'Stavitel', DATE '2002-02-20');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Dominik', 'Raketa', DATE '1997-08-24');

INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Klobouk s pe��m', 'Nike', 'sat�n', 'V�born� pro divadeln� hry', 2000, 'A', 5,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=2));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'��la s n�ivkou', NULL, 'vlna', NULL, 500, 'A', 2,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=3));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Skautsk� ��tek', NULL, 'bavlna', NULL, 350, 'A', 3,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=4));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Slepeck� h�l', NULL, 'hlin�k', NULL, 800, 'N', 1,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=5));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Zorova maska', NULL, 'bavlna', NULL, 600, 'N', 2,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=5));

INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Carmen', 'Andrie', 'Nylon', 'Divadeln� kost�m', 3000.0, 'S', 'A', '�erven�', 7 , 'Zachoval�',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=1));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Sukn�', 'Andrie', 'Bavlna', 'Jednoduch� dlouh� skun�', 1200.0, 'L', 'N', 'zelen�', 1 , 'Jako nov�',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=1));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Kroj', NULL, 'Nylon', 'Vy��van� lidov� od�v', 3500, 'M', 'A', 'zelen�', 2 , 'Pou�it�',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=2));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Pl᚝', NULL,'Akryl', '�ern� pl᚝ pro zlod�je', 900.0, NULL, 'N', '�ern�', 3 , 'Jako nov�',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=3));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Sako', 'V�voj', 'Nylon', '�ern� sako s jemn�mi pruhy', 1100.0, 'L', 'A', 'sv�tle �ern�', 4 , 'Pou�it�',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=4));

INSERT INTO Vypujcka values( ID_vypujcky_seq.nextval, DATE '2015-08-24', DATE '2015-08-30', 6100);
INSERT INTO Vypujcka values( ID_vypujcky_seq.nextval, DATE '2018-02-24', DATE '2018-02-24', 4400);
INSERT INTO Vypujcka values( ID_vypujcky_seq.nextval, DATE '2012-09-14', DATE '2012-10-01', 1300);
INSERT INTO Vypujcka values( ID_vypujcky_seq.nextval, DATE '2013-01-16', DATE '2013-10-01', 500);
INSERT INTO Vypujcka values( ID_vypujcky_seq.nextval, DATE '2020-04-13', DATE '2020-04-29', 3000);

INSERT INTO Je_soucasti_kostym values(1,1);
INSERT INTO Je_soucasti_kostym values(1,5);
INSERT INTO Je_soucasti_kostym values(2,3);
INSERT INTO Je_soucasti_kostym values(2,4);
INSERT INTO Je_soucasti_kostym values(5,1);

INSERT INTO Je_soucasti_doplnky values(1,1);
INSERT INTO Je_soucasti_doplnky values(1,2);
INSERT INTO Je_soucasti_doplnky values(3,4);
INSERT INTO Je_soucasti_doplnky values(3,2);
INSERT INTO Je_soucasti_doplnky values(4,2);

INSERT INTO Je_doporucovan values (3, 1);
INSERT INTO Je_doporucovan values (4, 1);    
INSERT INTO Je_doporucovan values (4, 5);    
INSERT INTO Je_doporucovan values (5, 2);   
INSERT INTO Je_doporucovan values (2, 3);  

INSERT INTO zprostredkoval values(1, 2);
INSERT INTO zprostredkoval values(1, 3);
INSERT INTO zprostredkoval values(2, 1);
INSERT INTO zprostredkoval values(3, 4);
INSERT INTO zprostredkoval values(5, 5);

INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 6100, NULL, DATE '2015-08-30',NULL, 1);
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 4400, NULL, DATE '2018-02-24',NULL, 2);
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 1300, 'Halloween', DATE '2012-10-01','Lehce natr�en�', 3);
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 500, NULL, DATE '2013-10-01',NULL, 4);
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 3000, NULL, DATE '2020-04-29',NULL, 5);

INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=1), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=1), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=1), 5);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=2), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=2), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=2), 3);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=3), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=3), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=3), 2);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=4), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=4), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=4), 1);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=5), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=5), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=5), 1);
--2 Tabulky
-- Vyhled� vyhled� jm�no, p��jmen� a telefon� ��slo zam�stnanc�, kte�� se staraj� o kost�m od v�robce Andrie.
SELECT DISTINCT jmeno, prijmeni, tel_cislo FROM kostym NATURAL JOIN zamestnanec where vyrobce = 'Andrie';
--2 Tabulky
-- Vyhled� n�zev, materi�l a cenu dopl�k� o kter� se star� zam�stnanec s p��jmen�m Kom�n.
SELECT nazev, material, cena FROM doplnky NATURAL JOIN zamestnanec where prijmeni = 'Kom�n';
--3 Tabulky
-- Vyhled� jm�no, p��jmen� klienta a cenu, datum od a datum do sjednan�ch v�p�j�ek od datumu 2014/07/09 a d�l a cena byla v�t�� n� 3500, se�azen� podle ceny sestupn�
SELECT jmeno, prijmeni, cena, od, do from klient NATURAL JOIN sjednal NATURAL JOIN vypujcka where od > TO_DATE('2014/07/09', 'yyyy/mm/dd') AND cena > 3500 ORDER by cena desc;
--GROUP BY a agrega�n� funkce
--Vyp�e zam�stnance a po�et kost�m�, o kter� se staraj�
SELECT jmeno, prijmeni, COUNT(kostym.ID_kostymu) AS Pocet_spravovanych_kostymu FROM kostym LEFT JOIN zamestnanec ON kostym.ID_zamestnance = zamestnanec.ID_zamestnance GROUP BY jmeno,prijmeni;
--GROUP BY a agrega�n� funkce
-- Vyp�e po�et dostupn�ch a po�et nedostupn�ch dopl�k�
select dostupnost, count(*) from doplnky group by dostupnost; 
--predik�t EXISTS
-- vyp�e id_zamestnance, jm�no a p��jmen� zam�stnanc� kte�� nespravuj� ��dn� kost�m
SELECT id_zamestnance, jmeno, prijmeni FROM zamestnanec WHERE NOT EXISTS(select * from kostym where kostym.id_zamestnance = zamestnanec.id_zamestnance);
--predik�t IN
--Vyp�e v�echny informace o kost�mech, kter� jsou stejn� star�, jako n�jak� dopl��k
SELECT * FROM kostym WHERE stari IN (SELECT stari FROM doplnky);
-- vyp�e jm�no, p��jmen� klienta kter� zaplatil nejv�ce za jednotlivou v�p�j�ku a jej� cenu 
select jmeno, prijmeni, cena from vypujcka natural join klient natural join sjednal where rownum = 1 order by cena desc;

/* Odstran� tabulky a sequence
DROP TABLE Sjednal;
DROP TABLE Zaznam;
DROP TABLE Je_soucasti_doplnky;
DROP TABLE Je_doporucovan;
DROP SEQUENCE ID_klienta_seq;
DROP SEQUENCE ID_vypujcky_seq;
DROP SEQUENCE ID_doplnku_seq;
DROP SEQUENCE ID_kostymu_seq;
DROP SEQUENCE ID_zamestnance_seq;
DROP SEQUENCE ID_zaznamu_seq;
DROP TABLE Klient;
DROP TABLE Zprostredkoval;
DROP TABLE Je_soucasti_kostym;
DROP TABLE Vypujcka;
DROP TABLE Kostym;
DROP TABLE Doplnky;
DROP TABLE Zamestnanec;
*/