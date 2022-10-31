//======== Copyright (c) 2021, FIT VUT Brno, All rights reserved. ============//
//
// Purpose:     Test Driven Development - priority queue code
//
// $NoKeywords: $ivs_project_1 $tdd_code.cpp
// $Author:     Tony Pham <xphamt0000@stud.fit.vutbr.cz>
// $Date:       $2021-01-04
//============================================================================//
/**
 * @file tdd_code.cpp
 * @author Tony Pham
 * 
 * @brief Implementace metod tridy prioritni fronty.
 */

#include <stdlib.h>
#include <stdio.h>

#include "tdd_code.h"

//============================================================================//
// ** ZDE DOPLNTE IMPLEMENTACI **
//
// Zde doplnte implementaci verejneho rozhrani prioritni fronty (Priority Queue)
// 1. Verejne rozhrani fronty specifikovane v: tdd_code.h (sekce "public:")
//    - Konstruktor (PriorityQueue()), Destruktor (~PriorityQueue())
//    - Metody Insert/Remove/Find a GetHead
//    - Pripadne vase metody definovane v tdd_code.h (sekce "protected:")
//
// Cilem je dosahnout plne funkcni implementace prioritni fronty implementovane
// pomoci tzv. "double-linked list", ktera bude splnovat dodane testy 
// (tdd_tests.cpp).
//============================================================================//

PriorityQueue::PriorityQueue()
{
 m_pHead = nullptr;
}

PriorityQueue::~PriorityQueue()
{
 //TODO leaky
    Element_t *currentNode = new Element_t();
    Element_t *next = new Element_t();

    while (next != nullptr) {
        m_pHead = next;
        delete(currentNode);
        currentNode = m_pHead;
        next = next->pNext;
    }

}

void PriorityQueue::Insert(int value)
{
    Element_t *newNode = new Element_t();
    newNode->value = value;
    newNode->pNext = nullptr;
    //přidáváání do prázdný fronty
    if(m_pHead == nullptr) {
        m_pHead = newNode;
    } else {
        Element_t *currentNode = m_pHead;

        do {
            //přidávání na začátek
            if(m_pHead->value < newNode->value) {
               newNode->pNext = m_pHead;
                m_pHead = newNode;
                break;
            } else {
                 Element_t *trail = currentNode;
                currentNode = currentNode->pNext;
                if(trail->pNext == nullptr  && trail->value > newNode->value) {
                    trail->pNext = newNode;
                    break;
                }
                //přidání do prostřed
                if(currentNode->value <= newNode->value) {
                newNode->pNext = currentNode;
                trail->pNext = newNode;
                    break;
                }
            }

            //currentNode = currentNode->pNext;
        }while (currentNode != nullptr);
    }




}
bool PriorityQueue::Remove(int value)
{
    //pokud je prazdny
    if (m_pHead == nullptr) {
        return false;
    } else {
        Element_t *currentNode = m_pHead;
        Element_t *trail = NULL;

        while (currentNode != nullptr) {
            if(currentNode->value == value) {
                break;
            } else {
                trail = currentNode;
                currentNode = currentNode->pNext;

            }
        }

        if(currentNode == nullptr) {
            return false;
        } else {
            if(m_pHead == currentNode) {
                m_pHead = m_pHead->pNext;
            } else {
                trail->pNext = currentNode->pNext;
            }
            delete currentNode;
        }
    }


}

PriorityQueue::Element_t *PriorityQueue::Find(int value)
{
    if (m_pHead == nullptr) {
        return NULL;
    }
    Element_t *currentNode = m_pHead;
    do {
        if(currentNode->value == value) {
            return currentNode;
        }
        currentNode = currentNode->pNext;
    }while (currentNode != nullptr);
    return NULL;
}

size_t PriorityQueue::Length()
{
    if (m_pHead == nullptr) {
        return 0;
    }

    int count = 0;
    Element_t* tmp = m_pHead;

    while (tmp != nullptr) {
        count++;
        tmp = tmp->pNext;
    }
    return count;
}

PriorityQueue::Element_t *PriorityQueue::GetHead()
{
    return m_pHead;
}

/*** Konec souboru tdd_code.cpp ***/
