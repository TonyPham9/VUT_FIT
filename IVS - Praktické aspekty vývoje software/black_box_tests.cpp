//======== Copyright (c) 2017, FIT VUT Brno, All rights reserved. ============//
//
// Purpose:     Red-Black Tree - public interface tests
//
// $NoKeywords: $ivs_project_1 $black_box_tests.cpp
// $Author:     Tony Pham <xphamt0000@stud.fit.vutbr.cz>
// $Date:       $2021-01-04
//============================================================================//
/**
 * @file black_box_tests.cpp
 * @author Tony Pham
 * 
 * @brief Implementace testu binarniho stromu.
 */

#include <vector>

#include "gtest/gtest.h"

#include "red_black_tree.h"

class EmptyTree : public ::testing::Test
{
protected:
    BinaryTree tree;
};

class NonEmptyTree : public ::testing::Test
{
protected:
    virtual void SetUp() {
        int values[] = {10, 85, 15, 70, 20, 60, 30, 50, 65, 80, 90, 40, 5, 55};

        for (auto value : values) {
            tree.InsertNode(value);
        }
    }
    BinaryTree tree;
};

class TreeAxioms : public ::testing::Test
{
protected:
    virtual void SetUp() {
        int values[] = {10, 85, 15, 70, 20, 60, 30, 50, 65, 80, 90, 40, 5, 55};

        for (auto value : values) {
            tree.InsertNode(value);
        }
    }
    BinaryTree tree;
};
//TODO insert empty
TEST_F(EmptyTree, InsertNode) {
    EXPECT_TRUE(tree.GetRoot() == NULL);
    tree.InsertNode(0);
    ASSERT_TRUE(tree.GetRoot() != NULL);
    EXPECT_EQ(tree.GetRoot()->key, 0);
    tree.InsertNode(10);
    tree.InsertNode(8);
    EXPECT_EQ(tree.GetRoot()->key, 8);
}

TEST_F(EmptyTree, DeleteNode) {

    EXPECT_FALSE(tree.DeleteNode(0));

    EXPECT_FALSE(tree.DeleteNode(10));

}

TEST_F(EmptyTree, FindNode) {

    EXPECT_TRUE(tree.FindNode(0) == NULL);

    EXPECT_TRUE(tree.FindNode(10) == NULL);
}
//TODO insert nonempty
TEST_F(NonEmptyTree, InsertNode) {

    EXPECT_TRUE(tree.GetRoot()->key == 30);

}

TEST_F(NonEmptyTree, DeleteNode) {

    EXPECT_FALSE(tree.DeleteNode(0));

    int values[] = { 5, 10, 15, 20, 30, 40, 50, 55, 60, 65, 70, 80, 85};
    for(int i = 0; i < 13; ++i)
    {
        EXPECT_TRUE(tree.DeleteNode(values[i]));
    }

    EXPECT_EQ(tree.GetRoot()->key, 90);

    tree.DeleteNode(90);
    EXPECT_TRUE(tree.GetRoot() == NULL);

}

TEST_F(NonEmptyTree, FindNode) {
    EXPECT_TRUE(tree.FindNode(0) == NULL);

    int values[] = { 5, 10, 15, 20, 30, 40, 50, 55, 60, 65, 70, 80, 85, 90 };
    for(int i = 0; i < 14; ++i)
    {
        BinaryTree::Node_t *Node = tree.FindNode(values[i]);
        ASSERT_TRUE(Node != NULL);
        EXPECT_EQ(Node->key, values[i]);
    }
}

TEST_F(TreeAxioms, FirstAxiom) {

    std::vector<BinaryTree::Node_t *> outLeafNodes{};
    tree.GetLeafNodes(outLeafNodes);
    bool areAllNodesBlack;
    for(auto node : outLeafNodes) {
        if (node->color == BLACK) {
            areAllNodesBlack = true;
        } else {
            areAllNodesBlack = false;
            break;
        }
    }
     EXPECT_TRUE(areAllNodesBlack);
}

TEST_F(TreeAxioms, SecondAxiom) {

    std::vector<BinaryTree::Node_t *> outNonLeafNodes{};
    tree.GetLeafNodes(outNonLeafNodes);
    bool areNodesBlackAfterRed;
    for(auto node : outNonLeafNodes) {
        if (node->color == RED) {
            if (node->pRight->color == BLACK && node->pLeft->color == BLACK)
                areNodesBlackAfterRed = true;
        } else {
            areNodesBlackAfterRed = false;
            break;
        }
    }
    EXPECT_FALSE(areNodesBlackAfterRed);
}
//TODO dodělat 3. axiom
TEST_F(TreeAxioms, ThirdAxiom) {

    std::vector<BinaryTree::Node_t *> outNonLeafNodes{};
    tree.GetLeafNodes(outNonLeafNodes);
    bool areNodesBlackAfterRed;
    for(auto node : outNonLeafNodes) {
        if (node->color == RED) {
            if (node->pRight->color == BLACK && node->pLeft->color == BLACK)
                areNodesBlackAfterRed = true;
        } else {
            areNodesBlackAfterRed = false;
            break;
        }
    }
    EXPECT_FALSE(areNodesBlackAfterRed);
}
//============================================================================//
// ** ZDE DOPLNTE TESTY **
//
// Zde doplnte testy Red-Black Tree, testujte nasledujici:
// 1. Verejne rozhrani stromu
//    - InsertNode/DeleteNode a FindNode
//    - Chovani techto metod testuje pro prazdny i neprazdny strom.
// 2. Axiomy (tedy vzdy platne vlastnosti) Red-Black Tree:
//    - Vsechny listove uzly stromu jsou *VZDY* cerne.
//    - Kazdy cerveny uzel muze mit *POUZE* cerne potomky.
//    - Vsechny cesty od kazdeho listoveho uzlu ke koreni stromu obsahuji
//      *STEJNY* pocet cernych uzlu.
//============================================================================//

/*** Konec souboru black_box_tests.cpp ***/
