/*Tato èást vytváøí sekvence pro poèítání PK*/
create SEQUENCE ID_klienta_seq minvalue 1 CACHE 10;
/
create SEQUENCE ID_vypujcky_seq minvalue 1 CACHE 10;
/
create SEQUENCE ID_zaznamu_seq minvalue 1 CACHE 10;
/
create SEQUENCE ID_zamestnance_seq minvalue 1 CACHE 10;
/
create SEQUENCE ID_doplnku_seq minvalue 1 CACHE 10;
/
create SEQUENCE ID_kostymu_seq minvalue 1 CACHE 10;
/
/*Tato èást vytváøí tabulky a relace mezi tabulkami*/
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
------------------------------- TRIGGERY ----------------------------------------
--Automatick vygeneruje obsah sloupce Vyrobce pokud není uveden
create trigger def_val_kost
before INSERT on Kostym
for each row 
begin
IF : new.Vyrobce is NULL 
THEN       
    :new.Vyrobce := 'Nebyl uveden';
END IF;
end;
/
create trigger def_val_dopl
before INSERT on Doplnky
for each row 
begin
IF : new.Vyrobce is NULL 
THEN       
    :new.Vyrobce := 'Nebyl uveden';
END IF;
end;
/
--Kontroluje jestli vkládaná data jsou validní
CREATE OR REPLACE TRIGGER kontrola_vypujcka BEFORE
    INSERT OR UPDATE OF Id_vypujcky,cena ON zaznam
    FOR EACH ROW
DECLARE
    castka   NUMBER;
BEGIN
    SELECT
        cena
    INTO
        castka
    FROM
        vypujcka
    WHERE
        vypujcka.id_vypujcky =:new.id_vypujcky;

    IF
        (:new.cena != castka )
    THEN
        raise_application_error(-20203,'Vkladani neexistujici ceny');
    END IF;

END;
/

/*Zde se vkládají data*/

INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'František', 'Vomáèka', DATE '1998-12-05','Brno', 420666353, 'vomFrant@gmail.com');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Jaroslav', 'Prásk', DATE '1988-11-25','Znojmo', 976776876, 'JardaVomacka@seznam.com');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Stanislav', 'Pravák', DATE '1999-05-16','Havlíèkùv Brod', 123123123, 'stanik88@seznam.cz');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Libor', 'Lavièka', DATE '2002-06-19','Praha', 976776876, 'libor.lavicka@gmail.com');
INSERT INTO zamestnanec values( ID_zamestnance_seq.nextval,'Lojza', 'Komín', DATE '2001-07-21','Svitavy', 722656879, 'milujuqueeny@gmail.com');

INSERT INTO Klient values( ID_klienta_seq.nextval,'Boris', 'Paštika', DATE '2003-03-28');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Filip', 'Vrzal', DATE '1986-09-29');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Radek', 'Matìjka', DATE '1969-02-13');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Boøek', 'Stavitel', DATE '2002-02-20');
INSERT INTO Klient values( ID_klienta_seq.nextval,'Dominik', 'Raketa', DATE '1997-08-24');

INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Klobouk s peøím', 'Nike', 'satén', 'Výborný pro divadelní hry', 2000, 'A', 5,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=2));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Šála s nášivkou', NULL, 'vlna', NULL, 500, 'A', 2,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=3));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Skautský šátek', NULL, 'bavlna', NULL, 350, 'A', 3,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=4));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Slepecká hùl', NULL, 'hliník', NULL, 800, 'N', 1,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=5));
INSERT INTO Doplnky values( ID_doplnku_seq.nextval,'Zorova maska', NULL, 'bavlna', NULL, 600, 'N', 2,(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=5));

INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Carmen', 'Andrie', 'Nylon', 'Divadelní kostým', 3000.0, 'S', 'A', 'èervená', 7 , 'Zachovalý',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=1));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Suknì', 'Andrie', 'Bavlna', 'Jednoduchá dlouhá skunì', 1200.0, 'L', 'N', 'zelená', 1 , 'Jako nová',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=1));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Kroj', null, 'Nylon', 'Vyšívaný lidový odìv', 3500, 'M', 'A', 'zelená', 2 , 'Použitý',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=2));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Pláš', NULL,'Akryl', 'Èerný pláš pro zlodìje', 900.0, NULL, 'N', 'èerná', 3 , 'Jako nová',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=3));
INSERT INTO Kostym values( ID_kostymu_seq.nextval, 'Sako', 'Vývoj', 'Nylon', 'Èerné sako s jemnými pruhy', 1100.0, 'L', 'A', 'svìtle èerná', 4 , 'Použitý',(SELECT ID_zamestnance from Zamestnanec WHERE ID_zamestnance=4));

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
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 1300, 'Halloween', DATE '2012-10-01','Lehce natržené', 3);
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 500, NULL, DATE '2013-10-01',NULL, 4);
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 3000, NULL, DATE '2020-04-29',NULL, 5);

INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=1), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=1), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=1), 5);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=2), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=2), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=2), 3);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=3), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=3), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=3), 2);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=4), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=4), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=4), 1);
INSERT INTO sjednal values((SELECT vypujcka.od from vypujcka WHERE ID_vypujcky=5), (SELECT vypujcka.do from vypujcka WHERE ID_vypujcky=5), (SELECT ID_vypujcky from vypujcka WHERE ID_vypujcky=5), 1);
--2 Tabulky
-- Vyhledá vyhledá jméno, pøíjmení a telefoní èíslo zamìstnancù, kteøí se starají o kostým od výrobce Andrie.
SELECT DISTINCT jmeno, prijmeni, tel_cislo FROM kostym NATURAL JOIN zamestnanec where vyrobce = 'Andrie';
--2 Tabulky
-- Vyhledá název, materiál a cenu doplòkù o které se stará zamìstnanec s pøíjmením Komín.
SELECT nazev, material, cena FROM doplnky NATURAL JOIN zamestnanec where prijmeni = 'Komín';
--3 Tabulky
-- Vyhledá jméno, pøíjmení klienta a cenu, datum od a datum do sjednaných výpùjèek od datumu 2014/07/09 a dál a cena byla vìtší nìž 3500, seøazené podle ceny sestupnì
SELECT jmeno, prijmeni, cena, od, do from klient NATURAL JOIN sjednal NATURAL JOIN vypujcka where od > TO_DATE('2014/07/09', 'yyyy/mm/dd') AND cena > 3500 ORDER by cena desc;
--GROUP BY a agregaèní funkce
--Vypíše zamìstnance a poèet kostýmù, o který se starají
SELECT jmeno, prijmeni, COUNT(kostym.ID_kostymu) AS Pocet_spravovanych_kostymu FROM kostym LEFT JOIN zamestnanec ON kostym.ID_zamestnance = zamestnanec.ID_zamestnance GROUP BY jmeno,prijmeni;
--GROUP BY a agregaèní funkce
-- Vypíše poèet dostupných a poèet nedostupných doplòkù
select dostupnost, count(*) from doplnky group by dostupnost; 
--predikát EXISTS
-- vypíše id_zamestnance, jméno a pøíjmení zamìstnancù kteøí nespravují žádný kostým
SELECT id_zamestnance, jmeno, prijmeni FROM zamestnanec WHERE NOT EXISTS(select * from kostym where kostym.id_zamestnance = zamestnanec.id_zamestnance);
--predikát IN
--Vypíše všechny informace o kostýmech, které jsou stejnì starý, jako nìjaký doplòìk
SELECT * FROM kostym WHERE stari IN (SELECT stari FROM doplnky);
-- vypíše jméno, pøíjmení klienta který zaplatil nejvíce za jednotlivou výpùjèku a její cenu 
select jmeno, prijmeni, cena from vypujcka natural join klient natural join sjednal where rownum = 1 order by cena desc;
------------------------------ TRIGGER TEST ---------------------------------------
--1. Triger by mìl nahradit všechy NULL položky pøi vkládání (kostym s id 3 a 4)  u výrobce za "Nebyl uveden" 
SELECT ID_KOSTYMU ,
NAZEV ,
VYROBCE
FROM Kostym;
--2. Triger vyhodí error protože cena 100 v tabulce vypujcka neni
INSERT INTO zaznam values( ID_zaznamu_seq.nextval, 100, NULL, DATE '2020-04-29',NULL, 5);
------------------------------ PROCEDURY ---------------------------------------
-- Procedura naète poèet kostýmù a jejich cenu pak ji zprùmìruje a vypoèítá inflaci v pøípadì 25%
CREATE OR REPLACE PROCEDURE "print_price_after"
AS
	"cena_vsech_kostymu" NUMBER;
	"pocet_kostymu" NUMBER;
	"prumerna_cena" NUMBER;
    "prumerna_cena_inflace" NUMBER;

BEGIN
	SELECT COUNT(*) INTO "pocet_kostymu" FROM Kostym;
    SELECT SUM(Cena) INTO "cena_vsech_kostymu" FROM Kostym;
    "prumerna_cena" := "cena_vsech_kostymu" / "pocet_kostymu";
    "prumerna_cena_inflace" := "prumerna_cena" * 1.25;
    
    DBMS_OUTPUT.put_line(
		'Je zde '
		|| "pocet_kostymu" || ' kostymu a jejich prùmìrná cena je '
		|| "prumerna_cena" || ' a po inflace '
		|| "prumerna_cena_inflace"  || '.'
	);
    
    EXCEPTION WHEN ZERO_DIVIDE THEN
	BEGIN
		IF "pocet_kostymu" = 0 THEN
			DBMS_OUTPUT.put_line('Neni zde zadny kostym');
		END IF;
	END;
END;
/

CREATE OR REPLACE PROCEDURE najdi_kostym (
    jmeno IN VARCHAR2
) IS
    hledany_kostym kostym.id_kostymu%TYPE;
    c_kostymID  kostym.id_kostymu%TYPE;
	c_zamestnanecID  zamestnanec.id_zamestnance%TYPE;
	c_dostupnost  kostym.dostupnost%TYPE;
	CURSOR c_uskladneni IS
		SELECT id_kostymu, id_zamestnance, dostupnost FROM kostym;
BEGIN
	OPEN c_uskladneni;
    SELECT
        id_kostymu
    INTO
        hledany_kostym
    FROM
        kostym
    WHERE
        kostym.nazev = jmeno;

    
	LOOP
		FETCH c_uskladneni INTO c_kostymID, c_zamestnanecID, c_dostupnost;
		EXIT WHEN c_uskladneni%NOTFOUND;
		
		IF (c_kostymID = hledany_kostym) THEN
			 DBMS_OUTPUT.put_line(
		'Kostym '
		|| jmeno || ' jeho dostupnost je: '
		|| c_dostupnost
	);
		END IF;
	END LOOP;
END;
/

--Test 1. procedury
BEGIN "print_price_after"; END;
/
--Test 2. procedury 
BEGIN najdi_kostym('Carmen'); END;
/

--------------------------- MATERIALIZED VIEW ----------------------------------


-- Materializovaný pohled na všechny zamìstnance a poèet jimi spravovanými kostýmy.
CREATE MATERIALIZED VIEW "Poèet spravovaných kostýmù" AS
SELECT
    ID_Zamestnance,
    Jmeno,
    Prijmeni,
    COUNT(*) AS "Poèet spravovaných kostýmù"
FROM Zamestnanec
NATURAL JOIN Kostym
GROUP BY ID_Zamestnance, Jmeno, Prijmeni;

-- Výpis materializovaného pohledu.
SELECT * FROM "Poèet spravovaných kostýmù";

-- Aktualizace dat, které jsou v materializovaném pohledu.
UPDATE Kostym SET ID_Zamestnance = 4 WHERE ID_kostymu = 1;
-- Data se v materializovaném pohledu neaktualizují.

-- Materializovaný pohled obsahuje souhrn zamìstnancù (id_zamestnance, Jmeno, Prijmeni) a poèet kostýmù, které spravují.
-- V kombinaci s poètem spravovaných doplòkù mùžeme urèit vytíženost jednotlivých zamìstnancù.
----------------------------- EXPLAIN PLAN -------------------------------------
--Vypíše klienty, kteøí se narodili mezi roky 1990 a 2020 s poètem jejich výjpùjèek
EXPLAIN PLAN FOR
SELECT Jmeno, Prijmeni, COUNT(*)
FROM Klient
NATURAL JOIN Sjednal
NATURAL JOIN Vypujcka
WHERE datnarozeni BETWEEN TO_DATE('1990-01-01', 'YYYY-MM-DD') AND TO_DATE('2019-12-31', 'YYYY-MM-DD')
GROUP BY Jmeno, Prijmeni
ORDER BY COUNT(*) DESC;
SELECT * FROM  TABLE(DBMS_XPLAN.DISPLAY);
-- Seskupí Klienty podle datumu narození (èímž zrychlí vyhledávání)
CREATE INDEX "NewIndex" ON Klient (datnarozeni);

EXPLAIN PLAN FOR
SELECT Jmeno, Prijmeni, COUNT(*)
FROM Klient
NATURAL JOIN Sjednal
NATURAL JOIN Vypujcka
WHERE datnarozeni BETWEEN TO_DATE('1990-01-01', 'YYYY-MM-DD') AND TO_DATE('2019-12-31', 'YYYY-MM-DD')
GROUP BY Jmeno, Prijmeni
ORDER BY COUNT(*) DESC;
SELECT * FROM  TABLE(DBMS_XPLAN.DISPLAY);
-------------------------------- Práva ------------------------------------

GRANT ALL ON Sjednal TO xkubaj03;
GRANT ALL ON Doplnky TO xkubaj03;
GRANT ALL ON Je_doporucovan TO xkubaj03;
GRANT ALL ON Je_soucasti_doplnky TO xkubaj03;
GRANT ALL ON Je_soucasti_kostym TO xkubaj03;
GRANT ALL ON Klient TO xkubaj03;
GRANT ALL ON Kostym TO xkubaj03;
GRANT ALL ON Vypujcka TO xkubaj03;
GRANT ALL ON Zamestnanec TO xkubaj03;
GRANT ALL ON Zaznam TO xkubaj03;
GRANT ALL ON Zprostredkoval TO xkubaj03;
GRANT EXECUTE ON "print_price_after" TO xkubaj03;
GRANT EXECUTE ON najdi_kostym TO xkubaj03;

/* Odstraní tabulky a sequence
DROP MATERIALIZED VIEW "Poèet spravovaných kostýmù";
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