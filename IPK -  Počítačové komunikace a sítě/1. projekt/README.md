# Dokumentace k 1. projektu IPK

Autor: Tony Pham (xphamt00)

## Postup při vypracování

První věc, co jsem udělal je, že jsem si vytvořil funkce na získání "hostname", "cpu-name" a "laod".
Po vytvoření funkcí jsem vše lokálně otestoval na standartním výstupu. Dále jsem ošetřil argumenty programu. Nakonec jsem sepsal do mainu server, který funguje, dokud není vypnut.



### Spuštění

Server se spouští příkazem ./hinfosvc číslo_portu. Když port není zadán, tak se jedno a chybový stav.

```
$ ./hinfosvc 8888
```

## Využití

Klient se serveru může dotázat na 3 věci: jméno hosta, název cpu a využití cpu. Pokud je příkaz zadán špatně, je vrácen error.

```
$ GET http://localhost:8888/load
13 % 

$ GET http://localhost:8888/cpu-name
Intel(R) Core(TM) i5-7300HQ CPU @ 2.50GHz

$ GET http://localhost:8888/hostname
xphamt00-VirtualBox
```

## Vypnutí serveru

Server se vypne kombinací kláves CTRL + C.


### Zdroje

Při vypracování projektu jsem čerpal z těchto stránek a zdrojů.

* https://www.geeksforgeeks.org/tcp-server-client-implementation-in-c/
* https://wis.fit.vutbr.cz/FIT/st/course-sl.php?id=761569&item=87710
* https://stackoverflow.com/questions/23367857/accurate-calculation-of-cpu-
* Kódy z přednášek IPK
