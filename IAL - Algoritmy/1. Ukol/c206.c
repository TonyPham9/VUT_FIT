
/* ******************************* c206.c *********************************** */
/*  Předmět: Algoritmy (IAL) - FIT VUT v Brně                                 */
/*  Úkol: c206 - Dvousměrně vázaný lineární seznam                            */
/*  Návrh a referenční implementace: Bohuslav Křena, říjen 2001               */
/*  Vytvořil: Martin Tuček, říjen 2004                                        */
/*  Upravil: Kamil Jeřábek, září 2020                                         */
/*           Daniel Dolejška, září 2021                                       */
/* ************************************************************************** */
/*
** Implementujte abstraktní datový typ dvousměrně vázaný lineární seznam.
** Užitečným obsahem prvku seznamu je hodnota typu int. Seznam bude jako datová
** abstrakce reprezentován proměnnou typu DLList (DL znamená Doubly-Linked
** a slouží pro odlišení jmen konstant, typů a funkcí od jmen u jednosměrně
** vázaného lineárního seznamu). Definici konstant a typů naleznete
** v hlavičkovém souboru c206.h.
**
** Vaším úkolem je implementovat následující operace, které spolu s výše
** uvedenou datovou částí abstrakce tvoří abstraktní datový typ obousměrně
** vázaný lineární seznam:
**
**      DLL_Init ........... inicializace seznamu před prvním použitím,
**      DLL_Dispose ........ zrušení všech prvků seznamu,
**      DLL_InsertFirst .... vložení prvku na začátek seznamu,
**      DLL_InsertLast ..... vložení prvku na konec seznamu,
**      DLL_First .......... nastavení aktivity na první prvek,
**      DLL_Last ........... nastavení aktivity na poslední prvek,
**      DLL_GetFirst ....... vrací hodnotu prvního prvku,
**      DLL_GetLast ........ vrací hodnotu posledního prvku,
**      DLL_DeleteFirst .... zruší první prvek seznamu,
**      DLL_DeleteLast ..... zruší poslední prvek seznamu,
**      DLL_DeleteAfter .... ruší prvek za aktivním prvkem,
**      DLL_DeleteBefore ... ruší prvek před aktivním prvkem,
**      DLL_InsertAfter .... vloží nový prvek za aktivní prvek seznamu,
**      DLL_InsertBefore ... vloží nový prvek před aktivní prvek seznamu,
**      DLL_GetValue ....... vrací hodnotu aktivního prvku,
**      DLL_SetValue ....... přepíše obsah aktivního prvku novou hodnotou,
**      DLL_Previous ....... posune aktivitu na předchozí prvek seznamu,
**      DLL_Next ........... posune aktivitu na další prvek seznamu,
**      DLL_IsActive ....... zjišťuje aktivitu seznamu.
**
** Při implementaci jednotlivých funkcí nevolejte žádnou z funkcí
** implementovaných v rámci tohoto příkladu, není-li u funkce explicitně
 * uvedeno něco jiného.
**
** Nemusíte ošetřovat situaci, kdy místo legálního ukazatele na seznam
** předá někdo jako parametr hodnotu NULL.
**
** Svou implementaci vhodně komentujte!
**
** Terminologická poznámka: Jazyk C nepoužívá pojem procedura.
** Proto zde používáme pojem funkce i pro operace, které by byly
** v algoritmickém jazyce Pascalovského typu implemenovány jako procedury
** (v jazyce C procedurám odpovídají funkce vracející typ void).
**
**/

#include "c206.h"

int error_flag;
int solved;

/**
 * Vytiskne upozornění na to, že došlo k chybě.
 * Tato funkce bude volána z některých dále implementovaných operací.
 */
void DLL_Error() {
    printf("*ERROR* The program has performed an illegal operation.\n");
    error_flag = TRUE;
}

/**
 * Provede inicializaci seznamu list před jeho prvním použitím (tzn. žádná
 * z následujících funkcí nebude volána nad neinicializovaným seznamem).
 * Tato inicializace se nikdy nebude provádět nad již inicializovaným seznamem,
 * a proto tuto možnost neošetřujte.
 * Vždy předpokládejte, že neinicializované proměnné mají nedefinovanou hodnotu.
 *
 * @param list Ukazatel na strukturu dvousměrně vázaného seznamu
 */
void DLL_Init( DLList *list ) {
    //nastavím vše na NULL
    list->activeElement = NULL;
    list->firstElement = NULL;
    list->lastElement = NULL;
    return;
}

/**
 * Zruší všechny prvky seznamu list a uvede seznam do stavu, v jakém se nacházel
 * po inicializaci.
 * Rušené prvky seznamu budou korektně uvolněny voláním operace free.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_Dispose( DLList *list ) {

    //Jedu z leva do prava doku vše nesmažu
    while (list->firstElement != NULL) {
        list->activeElement = list->firstElement;
        list->firstElement = list->firstElement->nextElement;
        free(list->activeElement);
    }

    list->activeElement = NULL;
    list->firstElement = NULL;
    list->lastElement = NULL;
    return;
}

/**
 * Vloží nový prvek na začátek seznamu list.
 * V případě, že není dostatek paměti pro nový prvek při operaci malloc,
 * volá funkci DLL_Error().
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param data Hodnota k vložení na začátek seznamu
 */
void DLL_InsertFirst( DLList *list, int data ) {

    //Mallocuju nevý prvek a zkontroluju malloc
    struct DLLElement *newElement = (struct DLLElement *)malloc(sizeof(struct DLLElement));
    if(newElement == NULL) {
        DLL_Error();
        return;
    }
    //Dám hodnoty prvku
    newElement->data = data;
    newElement->nextElement = NULL;
    newElement->previousElement = NULL;

    if(list->firstElement == NULL) { //fronta je prázdná
        list->firstElement = newElement; //vlož nový jako první a jediný
        list->lastElement = newElement; //korekce

    } else { //obsahuje nejméně jeden prvek

        newElement->nextElement = list->firstElement;
        list->firstElement->previousElement = newElement;
        list->firstElement = newElement;
    }
    return;
}

/**
 * Vloží nový prvek na konec seznamu list (symetrická operace k DLL_InsertFirst).
 * V případě, že není dostatek paměti pro nový prvek při operaci malloc,
 * volá funkci DLL_Error().
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param data Hodnota k vložení na konec seznamu
 */
void DLL_InsertLast( DLList *list, int data ) {

    //Mallocuju nevý prvek a zkontroluju malloc
    struct DLLElement *newElement = (struct DLLElement *)malloc(sizeof(struct DLLElement));
    if(newElement == NULL) {
        DLL_Error();
        return;
    }
    //Dám hodnoty prvku
    newElement->data = data;
    newElement->nextElement = NULL;
    newElement->previousElement = NULL;

    if(list->firstElement == NULL) { //fronta je prázdná
        list->firstElement = newElement; //vlož nový jako první a jediný
        list->lastElement = newElement; //korekce

    } else { //obsahuje nejméně jeden prvek
        newElement->previousElement = list->lastElement;
        list->lastElement->nextElement = newElement;
        list->lastElement = newElement;

    }
}

/**
 * Nastaví první prvek seznamu list jako aktivní.
 * Funkci implementujte jako jediný příkaz (nepočítáme-li return),
 * aniž byste testovali, zda je seznam list prázdný.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_First( DLList *list ) {

    list->activeElement = list->firstElement;
    return;
}

/**
 * Nastaví poslední prvek seznamu list jako aktivní.
 * Funkci implementujte jako jediný příkaz (nepočítáme-li return),
 * aniž byste testovali, zda je seznam list prázdný.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_Last( DLList *list ) {

   list->activeElement = list->lastElement;
    return;
}

/**
 * Prostřednictvím parametru dataPtr vrátí hodnotu prvního prvku seznamu list.
 * Pokud je seznam list prázdný, volá funkci DLL_Error().
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param dataPtr Ukazatel na cílovou proměnnou
 */
void DLL_GetFirst( DLList *list, int *dataPtr ) {

    //kontrola
    if (list->firstElement == NULL) {
        DLL_Error();
        return;
    }

    *dataPtr = list->firstElement->data;
    return;
}

/**
 * Prostřednictvím parametru dataPtr vrátí hodnotu posledního prvku seznamu list.
 * Pokud je seznam list prázdný, volá funkci DLL_Error().
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param dataPtr Ukazatel na cílovou proměnnou
 */
void DLL_GetLast( DLList *list, int *dataPtr ) {

    //kontrola
    if (list->firstElement == NULL) {
        DLL_Error();
        return;
    }

    *dataPtr = list->lastElement->data;
    return;
}

/**
 * Zruší první prvek seznamu list.
 * Pokud byl první prvek aktivní, aktivita se ztrácí.
 * Pokud byl seznam list prázdný, nic se neděje.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_DeleteFirst( DLList *list ) {

    //kontrola
    if (list->firstElement == NULL) {
        return;
    }

    //deaktivace
    if (list->firstElement == list->activeElement) {
        list->activeElement = NULL;
    }

    //odstranění
    struct DLLElement *tmpElement;
    tmpElement = list->firstElement;

    //měl pouze jeden prvek
    if (list->firstElement == list->lastElement) {
        list->lastElement = NULL;
        list->firstElement = NULL;
        //více prvků
    } else {
        list->firstElement = list->firstElement->nextElement;
        list->firstElement->previousElement = NULL;
    }
    free(tmpElement);
}

/**
 * Zruší poslední prvek seznamu list.
 * Pokud byl poslední prvek aktivní, aktivita seznamu se ztrácí.
 * Pokud byl seznam list prázdný, nic se neděje.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_DeleteLast( DLList *list ) {

    //kontrola
    if (list->firstElement == NULL) {
        return;
    }

    //deaktivace
    if (list->lastElement == list->activeElement) {
        list->activeElement = NULL;
    }

    //odstanění
    struct DLLElement *tmpElement;
    tmpElement = list->lastElement;
    if (list->firstElement == list->lastElement) {
        list->firstElement = NULL;
        list->firstElement = NULL;
    } else {
        list->lastElement = list->lastElement->previousElement;
        list->lastElement->nextElement = NULL;
    }
    free(tmpElement);
}

/**
 * Zruší prvek seznamu list za aktivním prvkem.
 * Pokud je seznam list neaktivní nebo pokud je aktivní prvek
 * posledním prvkem seznamu, nic se neděje.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_DeleteAfter( DLList *list ) {


    //kontrola
    if (list->activeElement == NULL || list->activeElement == list->lastElement) {
        return;
    }

    //pomocná proměnná
    struct DLLElement *tmpElement;
    tmpElement = list->activeElement->nextElement;

    //pokud mažu last
    if(list->activeElement->nextElement == list->lastElement) {
        list->lastElement = list->activeElement;
        list->lastElement->nextElement = NULL;

        //pokud nemažu last
    } else {
        list->activeElement->nextElement = list->activeElement->nextElement->nextElement;
        list->activeElement->nextElement->nextElement->previousElement = list->activeElement;
    }
    free(tmpElement);
}

/**
 * Zruší prvek před aktivním prvkem seznamu list .
 * Pokud je seznam list neaktivní nebo pokud je aktivní prvek
 * prvním prvkem seznamu, nic se neděje.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_DeleteBefore( DLList *list ) {

    //kontrola
    if (list->activeElement == NULL || list->activeElement == list->firstElement) {
        return;
    }

    //pomocná proměnná
    struct DLLElement *tmpElement;
    tmpElement = list->activeElement->previousElement;

    //pokud mažu první
    if(list->activeElement->previousElement == list->firstElement) {
        list->firstElement = list->activeElement;
        //pokud neamažu prnní
    } else {
        list->activeElement->previousElement = list->activeElement->previousElement->previousElement;
        list->activeElement->previousElement->previousElement->nextElement = list->activeElement;
    }

    free(tmpElement);

}

/**
 * Vloží prvek za aktivní prvek seznamu list.
 * Pokud nebyl seznam list aktivní, nic se neděje.
 * V případě, že není dostatek paměti pro nový prvek při operaci malloc,
 * volá funkci DLL_Error().
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param data Hodnota k vložení do seznamu za právě aktivní prvek
 */
void DLL_InsertAfter( DLList *list, int data ) {
    //kontrola aktivace
    if (list->activeElement == NULL) {
        return;
    }
    //Mallocuju nevý prvek a zkontroluju malloc
    struct DLLElement *newElement = (struct DLLElement *)malloc(sizeof(struct DLLElement));
    if(newElement == NULL) {
        DLL_Error();
        return;
    }
    //Dám hodnoty prvku
    newElement->data = data;
    newElement->nextElement = NULL;
    newElement->previousElement = list->activeElement;

    list->activeElement->nextElement = newElement;

    //pokud přidám a bude last
    if(list->activeElement == list->lastElement) {
        list->lastElement = newElement;

        //pokud přidám a NEBUDE last
    } else {
        list->activeElement->nextElement->previousElement = newElement;

    }
}

/**
 * Vloží prvek před aktivní prvek seznamu list.
 * Pokud nebyl seznam list aktivní, nic se neděje.
 * V případě, že není dostatek paměti pro nový prvek při operaci malloc,
 * volá funkci DLL_Error().
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param data Hodnota k vložení do seznamu před právě aktivní prvek
 */
void DLL_InsertBefore( DLList *list, int data ) {

//kontrola aktivace
    if (list->activeElement == NULL) {
        return;
    }
    //Mallocuju nevý prvek a zkontroluju malloc
    struct DLLElement *newElement = (struct DLLElement *)malloc(sizeof(struct DLLElement));
    if(newElement == NULL) {
        DLL_Error();
        return;
    }
    //Dám hodnoty prvku
    newElement->data = data;
    newElement->nextElement = NULL;
    newElement->previousElement = NULL;

    //pokud přidám a bude first
    if(list->activeElement == list->firstElement) {
        list->activeElement->previousElement = newElement;
        newElement->nextElement = list->activeElement;
        list->firstElement = newElement;

        //pokud přidám a NEBUDE first
    } else {
        newElement->previousElement = list->activeElement->previousElement;
        list->activeElement->previousElement->nextElement = newElement;
        newElement->nextElement = list->activeElement;
        list->activeElement->previousElement = newElement;

    }
}

/**
 * Prostřednictvím parametru dataPtr vrátí hodnotu aktivního prvku seznamu list.
 * Pokud seznam list není aktivní, volá funkci DLL_Error ().
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param dataPtr Ukazatel na cílovou proměnnou
 */
void DLL_GetValue( DLList *list, int *dataPtr ) {

    //kontrola
    if (list->activeElement == NULL) {
        DLL_Error();
        return;
    }

    if (list->activeElement != NULL) {
        *dataPtr = list->activeElement->data;
    }
}

/**
 * Přepíše obsah aktivního prvku seznamu list.
 * Pokud seznam list není aktivní, nedělá nic.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 * @param data Nová hodnota právě aktivního prvku
 */
void DLL_SetValue( DLList *list, int data ) {

    //kontrola
    if (list->firstElement == NULL) {
        return;
    }

    if(list->activeElement != NULL) {
        list->activeElement->data = data;
    }
    return;
}

/**
 * Posune aktivitu na následující prvek seznamu list.
 * Není-li seznam aktivní, nedělá nic.
 * Všimněte si, že při aktivitě na posledním prvku se seznam stane neaktivním.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_Next( DLList *list ) {

    //kontrola
    if (list->firstElement == NULL) {
        return;
    }

    if(list->activeElement != NULL) {
        list->activeElement = list->activeElement->nextElement;
    }
    return;
}


/**
 * Posune aktivitu na předchozí prvek seznamu list.
 * Není-li seznam aktivní, nedělá nic.
 * Všimněte si, že při aktivitě na prvním prvku se seznam stane neaktivním.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 */
void DLL_Previous( DLList *list ) {

    //kontrola
    if (list->firstElement == NULL) {
        return;
    }

    if (list->activeElement != NULL) {
        list->activeElement = list->activeElement->previousElement;
    }
    return;
}

/**
 * Je-li seznam list aktivní, vrací nenulovou hodnotu, jinak vrací 0.
 * Funkci je vhodné implementovat jedním příkazem return.
 *
 * @param list Ukazatel na inicializovanou strukturu dvousměrně vázaného seznamu
 *
 * @returns Nenulovou hodnotu v případě aktivity prvku seznamu, jinak nulu
 */
int DLL_IsActive( DLList *list ) {

    return (list->activeElement != NULL);
}

/* Konec c206.c */