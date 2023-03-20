/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt.Database

import android.content.Context
import androidx.room.Database
import androidx.room.Room
import androidx.room.RoomDatabase
import com.itu.beerhunt.Models.*

@Database(entities = arrayOf(UserModel :: class, HospodaModel::class, PivoModel::class, FavoriteModel::class, AktualitaModel::class,RecenzeModel::class), version = 1, exportSchema = false)

/**
 * Přístup do k databázi
 */
abstract class AppDatabase : RoomDatabase(){
    //získaní dat z jednotlivých tabulek
    abstract fun getUserDao() : UserDao

    abstract fun getHospodaDao() : HospodaDao

    abstract fun getPivoDao() : PivoDao

    abstract fun getFavoriteDao() : FavoriteDao

    abstract fun getAktualitaDao() : AktualitaDao

    abstract fun getRecenzeDao() : RecenzeDao

    //Vytvoření DB nebo její získání pokud již existuje
    companion object{

        @Volatile
        private var INSTANCE : AppDatabase? = null

        fun getDatabase(context : Context) : AppDatabase{

            return INSTANCE ?: synchronized(this){
                val instance = Room.databaseBuilder(context.applicationContext,AppDatabase::class.java, "app_database").build()
                INSTANCE = instance
                return instance
            }
        }
    }
}