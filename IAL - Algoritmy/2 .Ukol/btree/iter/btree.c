/*
 * Binárny vyhľadávací strom — iteratívna varianta
 *
 * S využitím dátových typov zo súboru btree.h, zásobníkov zo súborov stack.h a
 * stack.c a pripravených kostier funkcií implementujte binárny vyhľadávací
 * strom bez použitia rekurzie.
 */

#include "../btree.h"
#include "stack.h"
#include <stdio.h>
#include <stdlib.h>

/*
 * Inicializácia stromu.
 *
 * Užívateľ musí zaistiť, že incializácia sa nebude opakovane volať nad
 * inicializovaným stromom. V opačnom prípade môže dôjsť k úniku pamäte (memory
 * leak). Keďže neinicializovaný ukazovateľ má nedefinovanú hodnotu, nie je
 * možné toto detegovať vo funkcii.
 */
void bst_init(bst_node_t **tree) {
    *tree = NULL;
}

/*
 * Nájdenie uzlu v strome.
 *
 * V prípade úspechu vráti funkcia hodnotu true a do premennej value zapíše
 * hodnotu daného uzlu. V opačnom prípade funckia vráti hodnotu false a premenná
 * value ostáva nezmenená.
 *
 * Funkciu implementujte iteratívne bez použitia vlastných pomocných funkcií.
 */
bool bst_search(bst_node_t *tree, char key, int *value) {
    //pokud je strom prázdný
    if(tree == NULL) {
        return NULL;
    }

    bst_node_t *tmp = tree;

    // projdu celý strom
    while (tmp){
        // vyhledávání v levém podstromu
        if (key < tmp->key) {
            tmp = tmp->left;
        } else if (key > tmp->key){
            // vyhledávání v pravém podstromu
            tmp = tmp->right;
        } else {
            *value = tmp->value;
            return true; // vkládaný uzel již existuje
        }
    }
    return false;
}

/*
 * Vloženie uzlu do stromu.
 *
 * Pokiaľ uzol so zadaným kľúčom v strome už existuje, nahraďte jeho hodnotu.
 * Inak vložte nový listový uzol.
 *
 * Výsledný strom musí spĺňať podmienku vyhľadávacieho stromu — ľavý podstrom
 * uzlu obsahuje iba menšie kľúče, pravý väčšie.
 *
 * Funkciu implementujte iteratívne bez použitia vlastných pomocných funkcií.
 */
void bst_insert(bst_node_t **tree, char key, int value) {

    bst_node_t *tmp = (*tree);
    bst_node_t *insert_after = NULL;

    // projdu celý strom
    while (tmp){
        insert_after = tmp;
        // vyhledávání v levém podstromu
        if (key < tmp->key) {
            tmp = tmp->left;
            continue;
        }
        // vyhledávání v pravém podstromu
        if (key > tmp->key) {
            tmp = tmp->right;
            continue;
        }
        tmp->value = value;
        return; // vkládaný uzel již existuje
    }

    bst_node_t *new_node = NULL;
    new_node = malloc(sizeof(struct bst_node));
    //pokud malloc selže
    if (!new_node) {
        return;
    }
    // nastavení parametrů nového uzlu
    new_node->key = key;
    new_node->value = value;
    new_node->left = NULL;
    new_node->right = NULL;

    // vkládaný uzel bude kořen
    if (!insert_after) {
        *tree = new_node;
        return;
    }
    // vkládaný uzel bude levý list nalezeného uzlu
    if (key < insert_after->key) {
        insert_after->left = new_node;
        return;
    } else {
        // vkládaný uzel bude pravý list nalezeného uzlu
        insert_after->right = new_node;
    }
}

/*
 * Pomocná funkcia ktorá nahradí uzol najpravejším potomkom.
 *
 * Kľúč a hodnota uzlu target budú nahradené kľúčom a hodnotou najpravejšieho
 * uzlu podstromu tree. Najpravejší potomok bude odstránený. Funkcia korektne
 * uvoľní všetky alokované zdroje odstráneného uzlu.
 *
 * Funkcia predpokladá že hodnota tree nie je NULL.
 *
 * Táto pomocná funkcia bude využitá pri implementácii funkcie bst_delete.
 *
 * Funkciu implementujte iteratívne bez použitia vlastných pomocných funkcií.
 */
void bst_replace_by_rightmost(bst_node_t *target, bst_node_t **tree) {

    //dojdu až do nejpravějšího uzlu
    while ((*tree)->right != NULL) {
        tree = &((*tree)->right);
    }
    //pomocná proměnná
    bst_node_t *tmp;
    tmp = (*tree)->left;
    //předám hodnoty
    target->key = (*tree)->key;
    target->value = (*tree)->value;
    //uvolním zdroje
    free(*tree);
    (*tree) = tmp;
    return;
}

/*
 * Odstránenie uzlu v strome.
 *
 * Pokiaľ uzol so zadaným kľúčom neexistuje, funkcia nič nerobí.
 * Pokiaľ má odstránený uzol jeden podstrom, zdedí ho otec odstráneného uzla.
 * Pokiaľ má odstránený uzol oba podstromy, je nahradený najpravejším uzlom
 * ľavého podstromu. Najpravejší uzol nemusí byť listom!
 * Funkcia korektne uvoľní všetky alokované zdroje odstráneného uzlu.
 *
 * Funkciu implementujte iteratívne pomocou bst_replace_by_rightmost a bez
 * použitia vlastných pomocných funkcií.
 */
void bst_delete(bst_node_t **tree, char key) {
//pokud je strom prázdný nic nedělám
    if(*tree == NULL) {
        return;
    }

    while((*tree) != NULL) {
        if (key < (*tree)->key) {
            tree = &((*tree)->left);
        } else if (key > (*tree)->key) {
            tree = &((*tree)->right);
            //našel jsem hodnotu
        } else {
            //pomocná proměnná
            bst_node_t *tmp = (*tree);

            //pokud má pouze 1 podstrom
            if ((*tree)->right == NULL) {
                *tree = tmp->left;
                free(tmp);
                return;
            } else if ((*tree)->left == NULL) {
                *tree = tmp->right;
                free(tmp);
                return;
                //pokud má oba podstromy
            } else if (((*tree)->right != NULL) && ((*tree)->left != NULL)) {
                bst_replace_by_rightmost(*tree, &(*tree)->left);
                return;
            }
        }
    }

}

/*
 * Zrušenie celého stromu.
 *
 * Po zrušení sa celý strom bude nachádzať v rovnakom stave ako po
 * inicializácii. Funkcia korektne uvoľní všetky alokované zdroje rušených
 * uzlov.
 *
 * Funkciu implementujte iteratívne pomocou zásobníku uzlov a bez použitia
 * vlastných pomocných funkcií.
 */
void bst_dispose(bst_node_t **tree) {
    //pokud je strom prázdný nedělej nic
    if ((*tree) == NULL) {
        return;
    }
    stack_bst_t stack;
    stack_bst_init(&stack);
    stack_bst_push(&stack, (*tree));

    while (!stack_bst_empty(&stack)) {
        bst_node_t *tmp = stack_bst_top(&stack);
        stack_bst_pop(&stack);
        if (tmp->left != NULL) {
            stack_bst_push(&stack, tmp->left);
        }
        if (tmp->right != NULL) {
            stack_bst_push(&stack, tmp->right);
        }
        free(tmp);
    }
    bst_init(tree);
}

/*
 * Pomocná funkcia pre iteratívny preorder.
 *
 * Prechádza po ľavej vetve k najľavejšiemu uzlu podstromu.
 * Nad spracovanými uzlami zavola bst_print_node a uloží ich do zásobníku uzlov.
 *
 * Funkciu implementujte iteratívne pomocou zásobníku uzlov a bez použitia
 * vlastných pomocných funkcií.
 */
void bst_leftmost_preorder(bst_node_t *tree, stack_bst_t *to_visit) {
    if (tree == NULL) {
        return;
    }
        while(tree != NULL) {
            stack_bst_push(to_visit, tree);
            bst_print_node(tree);
            tree = tree->left;
        }
}

/*
 * Preorder prechod stromom.
 *
 * Pre aktuálne spracovávaný uzol nad ním zavolajte funkciu bst_print_node.
 *
 * Funkciu implementujte iteratívne pomocou funkcie bst_leftmost_preorder a
 * zásobníku uzlov bez použitia vlastných pomocných funkcií.
 */
void bst_preorder(bst_node_t *tree) {
    if(tree == NULL) {
        return;
    }

    stack_bst_t stack;
    stack_bst_init(&stack);
    bst_leftmost_preorder(tree, &stack);
    bst_node_t *tmp;
    while(!stack_bst_empty(&stack)) {
        tmp = stack_bst_top(&stack);
        stack_bst_pop(&stack);
        bst_leftmost_preorder(tmp->right, &stack);

    }
}

/*
 * Pomocná funkcia pre iteratívny inorder.
 *
 * Prechádza po ľavej vetve k najľavejšiemu uzlu podstromu a ukladá uzly do
 * zásobníku uzlov.
 *
 * Funkciu implementujte iteratívne pomocou zásobníku uzlov a bez použitia
 * vlastných pomocných funkcií.
 */
void bst_leftmost_inorder(bst_node_t *tree, stack_bst_t *to_visit) {
    if (tree == NULL) {
        return;
    }
    while(tree != NULL) {
        stack_bst_push(to_visit, tree);
        tree = tree->left;
    }
}

/*
 * Inorder prechod stromom.
 *
 * Pre aktuálne spracovávaný uzol nad ním zavolajte funkciu bst_print_node.
 *
 * Funkciu implementujte iteratívne pomocou funkcie bst_leftmost_inorder a
 * zásobníku uzlov bez použitia vlastných pomocných funkcií.
 */
void bst_inorder(bst_node_t *tree) {
    if(tree == NULL) {
        return;
    }

    stack_bst_t stack;
    stack_bst_init(&stack);
    bst_leftmost_inorder(tree, &stack);
    while(!stack_bst_empty(&stack)) {
        tree = stack_bst_top(&stack);
        stack_bst_pop(&stack);
        bst_print_node(tree);
        bst_leftmost_inorder(tree->right, &stack);

    }
}

/*
 * Pomocná funkcia pre iteratívny postorder.
 *
 * Prechádza po ľavej vetve k najľavejšiemu uzlu podstromu a ukladá uzly do
 * zásobníku uzlov. Do zásobníku bool hodnôt ukladá informáciu že uzol
 * bol navštívený prvý krát.
 *
 * Funkciu implementujte iteratívne pomocou zásobníkov uzlov a bool hodnôt a bez použitia
 * vlastných pomocných funkcií.
 */
void bst_leftmost_postorder(bst_node_t *tree, stack_bst_t *to_visit,stack_bool_t *first_visit) {
    if (tree == NULL) {
        return;
    }
    while(tree != NULL) {
        stack_bst_push(to_visit, tree);
        stack_bool_push(first_visit, true);
        tree = tree->left;
    }
}

/*
 * Postorder prechod stromom.
 *
 * Pre aktuálne spracovávaný uzol nad ním zavolajte funkciu bst_print_node.
 *
 * Funkciu implementujte iteratívne pomocou funkcie bst_leftmost_postorder a
 * zásobníkov uzlov a bool hodnôt bez použitia vlastných pomocných funkcií.
 */
void bst_postorder(bst_node_t *tree) {
    if(tree == NULL) {
        return;
    }
    bool fromLeft;
    stack_bst_t stack;
    stack_bst_init(&stack);
    stack_bool_t bStack;
    stack_bool_init(&bStack);
    bst_leftmost_postorder(tree, &stack, &bStack);
    while(!stack_bst_empty(&stack)) {
        tree = stack_bst_top(&stack);
        fromLeft = stack_bool_top(&bStack);
        stack_bool_pop(&bStack);
        if(fromLeft) {
            stack_bool_push(&bStack, false);
            bst_leftmost_postorder(tree->right, &stack, &bStack);
        }else {
            stack_bst_pop(&stack);
            bst_print_node(tree);
        }
    }
}
