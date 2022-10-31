/*
 * Tabuľka s rozptýlenými položkami
 *
 * S využitím dátových typov zo súboru hashtable.h a pripravených kostier
 * funkcií implementujte tabuľku s rozptýlenými položkami s explicitne
 * zreťazenými synonymami.
 *
 * Pri implementácii uvažujte veľkosť tabuľky HT_SIZE.
 */

#include "hashtable.h"
#include <stdlib.h>
#include <string.h>

int HT_SIZE = MAX_HT_SIZE;

/*
 * Rozptyľovacia funkcia ktorá pridelí zadanému kľúču index z intervalu
 * <0,HT_SIZE-1>. Ideálna rozptyľovacia funkcia by mala rozprestrieť kľúče
 * rovnomerne po všetkých indexoch. Zamyslite sa nad kvalitou zvolenej funkcie.
 */
int get_hash(char *key) {
  int result = 1;
  int length = strlen(key);
  for (int i = 0; i < length; i++) {
    result += key[i];
  }
  return (result % HT_SIZE);
}

/*
 * Inicializácia tabuľky — zavolá sa pred prvým použitím tabuľky.
 */
void ht_init(ht_table_t *table) {

    for (int i = 0 ; i < HT_SIZE; ++i) {
        (*table)[i] = NULL;
    }
}

/*
 * Vyhľadanie prvku v tabuľke.
 *
 * V prípade úspechu vráti ukazovateľ na nájdený prvok; v opačnom prípade vráti
 * hodnotu NULL.
 */
ht_item_t *ht_search(ht_table_t *table, char *key) {
    //pomocná proměnná
    ht_item_t *tmp = (*table)[get_hash(key)];

    //cyklím a hledám shodu s key
    while(tmp != NULL) {
        //když najdu shodu vracím prvek
        if(key == tmp->key) {
            return tmp;
        }
        tmp = tmp->next;
    }
    //když nenacházím vracím NULL
    return NULL;

}

/*
 * Vloženie nového prvku do tabuľky.
 *
 * Pokiaľ prvok s daným kľúčom už v tabuľke existuje, nahraďte jeho hodnotu.
 *
 * Pri implementácii využite funkciu ht_search. Pri vkladaní prvku do zoznamu
 * synonym zvoľte najefektívnejšiu možnosť a vložte prvok na začiatok zoznamu.
 */
void ht_insert(ht_table_t *table, char *key, float value) {
    //pomocná proměnná s použitím funkce ht_search
    ht_item_t *tmp = ht_search(table,key);
    //existuje -> náhrada hodnoty
    if(tmp != NULL) {
        tmp->value = value;

    }
    //neexistuje -> přidání na začátek
    else {
        ht_item_t * new_first = malloc(sizeof(struct ht_item));
        new_first->key = key;
        new_first->value = value;
        new_first->next = (*table)[get_hash(key)];
        (*table)[get_hash(key)] = new_first;
    }
}

/*
 * Získanie hodnoty z tabuľky.
 *
 * V prípade úspechu vráti funkcia ukazovateľ na hodnotu prvku, v opačnom
 * prípade hodnotu NULL.
 *
 * Pri implementácii využite funkciu ht_search.
 */
float *ht_get(ht_table_t *table, char *key) {
    //pomocná proměnná s použitím funkce ht_search
    ht_item_t *tmp = ht_search(table,key);

    //vracím hodnotu prvku
    if(tmp != NULL) {
        return &tmp->value;
    }
    //vracím NULL
    else {
        return NULL;
    }
}

/*
 * Zmazanie prvku z tabuľky.
 *
 * Funkcia korektne uvoľní všetky alokované zdroje priradené k danému prvku.
 * Pokiaľ prvok neexistuje, nerobte nič.
 *
 * Pri implementácii NEVYUŽÍVAJTE funkciu ht_search.
 */
void ht_delete(ht_table_t *table, char *key) {

    //pomocná proměnná
    ht_item_t *tmp = (*table)[get_hash(key)];
    ht_item_t *tmp_prev = NULL;
    //pokud prvek neexstuje nic nedělám
    if(tmp == NULL) {
        return;
    }

    while(tmp != NULL) {
        if(tmp->key == key) {
            //pokud je jediný
            if(tmp->next == NULL) {
                (*table)[get_hash(key)] = NULL;
                free(tmp);
                break;
            }
            tmp_prev->next = tmp->next;
            free(tmp);
            break;
        }
        tmp_prev = tmp;
        tmp = tmp->next;
    }

}


/*
 * Zmazanie všetkých prvkov z tabuľky.
 *
 * Funkcia korektne uvoľní všetky alokované zdroje a uvedie tabuľku do stavu po
 * inicializácii.
 */
void ht_delete_all(ht_table_t *table) {
    //cyklus pro celou tabulku
    for(int i = 0; i < HT_SIZE; i++) {
        //pomocná proměnná
        ht_item_t *tmp = (*table)[i];
        //cyklus co maže prvky na indexu tabulky
        while(tmp != NULL) {
            //pomocná proměnná
            ht_item_t *tmp_2 = tmp;
            free(tmp);
            tmp = tmp_2->next;
        }
        //po odstanění prvků nastavím index na NULL
        (*table)[i] = NULL;
    }
}
