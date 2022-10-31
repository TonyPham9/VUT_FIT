//======== Copyright (c) 2021, FIT VUT Brno, All rights reserved. ============//
//
// Purpose:     White Box - Tests suite
//
// $NoKeywords: $ivs_project_1 $white_box_code.cpp
// $Author:     Tony Pham <xphamt00@stud.fit.vutbr.cz>
// $Date:       $2021-01-04
//============================================================================//
/**
 * @file white_box_tests.cpp
 * @author Tony Pham
 *
 * @brief Implementace testu prace s maticemi.
 */

#include "gtest/gtest.h"
#include "white_box_code.h"

class Matice1x1 : public ::testing::Test{

protected:
    Matrix m{};

};



TEST(Matice, AllTest) {
    std::vector<double>Test_3x3 {0,1,3};
    std::vector<double>Test_4x4 {0,1,3,6};

    Matrix x{};
    EXPECT_EQ(x.get(0,0), 0);
    Matrix x2 {3,3};
    EXPECT_ANY_THROW(Matrix(0,1));
    Matrix x3 {};
    x3.set(0,0, 5);
    Matrix result = x+x3;
    ASSERT_EQ(result.get(0,0),5);
    EXPECT_ANY_THROW(x3.get(5,5));
    EXPECT_FALSE(x3.set(5,5,5));

    std::vector<std::vector<double>> values;
    values = {{1.0},{2.0}};

    std::vector<std::vector<double>> values_2;
    values_2 = {{2.0,5.0,6.0},{2.0}};

    Matrix *matrix_last_test = new Matrix(2,2);
    EXPECT_FALSE(matrix_last_test->set(values_2));

    EXPECT_FALSE(x.set(values));
    Matrix *matice_4x4 = new Matrix(4,4);
    double values_4x4[] = {1.0, 6.0, 0.0, 0.0,
                           0.0, 1.0, 0.0, 0.0,
                           0.0, 0.0, 1.0, 0.0,
                           0.0, 0.0, 0.0, 1.0};
    for(int i = 0; i<4; ++i) {
        for(int j = 0; j<4; ++j) {
            matice_4x4 ->set(i,j, values_4x4[i+j]);
        }
    }

    Matrix *matice_3x3 = new Matrix(3,3);
    double values_3x3[] = {1.0, 6.0, 0.0,
                           0.0, 1.0, 0.0,
                           0.0, 0.0, 1.0 };
    for(int i = 0; i<3; ++i) {
        for(int j = 0; j<3; ++j) {
            matice_3x3 ->set(i,j, values_3x3[i+j]);
        }
    }
    Matrix *matice_3x2 = new Matrix(3,2);
    double values_3x2[] = {1.0, 0.0,
                           0.0, 1.0,
                           0.0, 0.0};
    for(int i = 0; i<3; ++i) {
        for(int j = 0; j<2; ++j) {
            matice_3x2 ->set(i,j, values_3x2[i+j]);
        }
    }
    Matrix *matice_2x2 = new Matrix(2,2);

    double values_2x2[] = {1.0, 2.0, 3.0, 4.0};
    for(int i = 0; i<2; ++i) {
        for(int j = 0; j<2; ++j) {
            matice_2x2 ->set(i,j, values_2x2[i+j]);
        }
    }

    Matrix *matice_2x2_singular = new Matrix(2,2);

    double value_singular[] = {1.0, -2.0, -2.0, 4.0};
    for(int i = 0; i<2; ++i) {
        for(int j = 0; j<2; ++j) {
            matice_2x2 ->set(i,j, value_singular[i+j]);
        }
    }

    Matrix *matice_2x1 = new Matrix(2,1);
    matice_2x1->set(values);

    Matrix *matice_1x1 = new Matrix(1,1);
    matice_1x1 ->set(0,0,9);



    EXPECT_NO_THROW(matice_3x2 ->operator*(*matice_2x2));
    EXPECT_ANY_THROW(matice_2x2 ->operator*(*matice_1x1));
    EXPECT_ANY_THROW(matice_4x4 ->operator*(*matice_2x2_singular));
    EXPECT_ANY_THROW(matice_1x1 ->operator*(*matice_2x2_singular));

    EXPECT_ANY_THROW(matice_2x2 ->operator+(*matice_2x1));
    EXPECT_ANY_THROW(matice_4x4 ->operator+(*matice_1x1));
    EXPECT_ANY_THROW(matice_4x4 ->operator+(*matice_2x2_singular));

    EXPECT_ANY_THROW(matice_2x2 ->operator==(*matice_2x1));
    EXPECT_NO_THROW(matice_2x2 ->operator==(*matice_2x2));
    EXPECT_FALSE(matice_2x2 ->operator==(*matice_2x2_singular));
    EXPECT_ANY_THROW(matice_4x4 ->operator==(*matice_1x1));
    EXPECT_ANY_THROW(matice_4x4 ->operator==(*matice_2x2_singular));



    EXPECT_NO_THROW(matice_2x1 ->operator*(2));
    EXPECT_NO_THROW(matice_2x2_singular ->operator*(2));
    EXPECT_NO_THROW(matice_2x1 ->operator*(0));


    EXPECT_ANY_THROW(matice_1x1 ->inverse());
    EXPECT_ANY_THROW(matice_4x4 ->inverse());
    EXPECT_NO_THROW(matice_3x3 ->inverse());
    EXPECT_NO_THROW(matice_2x2 ->inverse());
    EXPECT_ANY_THROW(matice_2x2_singular ->inverse());

    EXPECT_NO_THROW(matice_1x1 ->transpose());

    EXPECT_NO_THROW(matice_1x1 ->solveEquation(std::vector<double> {1}));
    EXPECT_ANY_THROW(matice_2x2 ->solveEquation(std::vector<double> {1}));

    EXPECT_ANY_THROW(matice_2x2_singular ->solveEquation(std::vector<double> {1,2}));
    EXPECT_ANY_THROW(matice_2x1 ->solveEquation(std::vector<double> {1}));

    EXPECT_NO_THROW(matice_3x3 ->solveEquation(Test_3x3));
    EXPECT_NO_THROW(matice_4x4 ->solveEquation(Test_4x4));

    delete(matice_1x1);
    delete(matice_2x1);
    delete(matice_2x2);
    delete(matice_2x2_singular);
    delete(matice_3x3);
    delete(matice_3x2);
    delete(matice_4x4);
}
/*** Konec souboru white_box_tests.cpp ***/