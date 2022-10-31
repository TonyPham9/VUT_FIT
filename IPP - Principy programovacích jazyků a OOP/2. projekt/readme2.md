# Implementační dokumentace k 2. úloze do IPP 2021/2022
Jméno a příjmení: __Tony Pham__
Login: __xphamt00__

## 1. Úvod
Úkolem bylo naprogramovat `interpret.py` v jazyce python3.8 a testy na `interpret.py` a předešlí program `parse.php` v jazyce php8.1 .

## 2. Interptet
### 1. Popis 
Skript`interpret.py` načte XML reprezentaci programu a tento program
s využitím vstupu dle parametrů příkazové řádky interpretuje a generuje výstup.
### 2. Parametry
Skript umožňuje `--help` pro vypsání nápovedy, `--source=file` pro nastaveního vstupního xml, `--input=file` pro nastení vstumu pro instrukce READ
### 3. Implementace
Vstupní data čte skript `interpret.py` ze souborů nebo ze standardního vstupu. Chybové stavy vypisuje na standardní chybový výstup.

Základem je třída `Instruction` která obshahuje atributy pro jednotlivou instrukci a funkce pro nastavení nebo získání atributů. Ještě před vytvořením objektu třídy `Instruction` se volá funkce `input_check`, která zkontroluje parametry a vrátí cestu k nim pokud se nejedná o `--help`. Další důležitá třída je třída `Frame` obsahuje atributy s rámci a funkce pro práci s rámci např. `set_var`, která nastaví v určitém rámci hodnotu proměnný. Poslední důležitá třída je `Factory`, která volá konstruktor s využitím podtříd `Instruction` pro každou instrukci ve vstupním xml souboru. 

Pro zpracování xml vstupu byla využita vestavěná funkce `xml.etree.ElementTree`, díky které se podařilo lehce vybrat podstatná data. Dále bylo potřeba zkontrolovat xml vstup. K tomu byli použity funkce `arg_count_check`, `check_head` a `check_instructions`, které kontrolují vstup a vrací chyby spjaté s xml. 

Po kontrole se načtou všechny podstatná data do slovníku `instr_dict`, kde klíčovou hodnotu reprezentuje pořadí vykonávání instrukce a hodnotu klíče reprezentuje objekt třídy `Instruction` s již vyplněnými atributy.

Ještě před interpretací instrukcí je potřeba naplnit slovník `label_dict` kde klíčovou hodnotu reprezentuje jméno návěstí a hodnotu klíče reprezentuje pořadí, na kterém se nechází. Toto je potřeba, protože např. instrukce JUMP může být jako první instrukce a program by nevěděl kam skočit.

Po tomto všem se dostáváme do hlavního `while` cyklu, který interpretuje instrukce a končí až přečte poslední instrukci z `instr_dict`. V cyklu je `switch` tvořený z "ifů" a `case` hodnoty jsou jednotlivé instrukce.

## 3. Test
### 1. Popis
Skript`test.php` bude sloužit pro automatické testování (postupné) aplikace
`parse.php` a `interpret.py`.
### 2. Parametry
Skript umožňuje`--help` pro vypsání nápovedy, `--directory=path` pro nastaveního adresáře, `--recursiv` pro hledání testů i v podadresářím, `--parse-script=file` a `--int-script=file` pro nastavení skriptů k testování, `--parse-only` a `--int-only` pro testování jen jednoho skriptu, `--jexampath=path` pro nastavení cesky k souboru `jexamxml.jar` a `--noclean` pro zamezení mazání dočasných souborů.
### 3. Implementace
Skript `test.php` hledá testové soubory buď v aktuálním adresáři, nebo v zadaném adresáři a provede jejich otestování. Výstupem je přehledná tabulka ve formátu HTML5, která je vypsána na standartní výstup. Chybová hlášení jsou vypisována na standartní chybový výstup.  

Skript se skládá z několika tříd a funkcí. Hlavní funkce `main` zajišťuje samotné testování a uchování výsledků testu. Dále třída `arg_check` a funkce `check_line_arg` slouží ke kontrole argumentů programu a přiřazení cest k souborům a třída `test_load`, která obsahuje funkci `find_tests` je důležitá část programu, která vyhledává testovací položky a případně vytváří chybějící soubory a třída `HTML_gen` s funkcemi `html_layout` a `fill_html` slouží k vygenerování výstupného HMTL kodu.

Nejprve se ověří argumenty programu a zmapují se testové soubory `.src` v zadaném adresáři. K těmto souborům se případně vytvoří prázdné přídavné soubory `.in` a `.out`. Potom následuje saamotné testování.