/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt.Database

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.itu.beerhunt.Models.RecenzeModel


@Dao
interface RecenzeDao {

    @Query("SELECT * FROM recenze_table")
    fun getAllRecenze(): List<RecenzeModel>

    @Insert(onConflict = OnConflictStrategy.IGNORE)
    fun insertRecenze(recenzeModel: RecenzeModel) : Long

    @Query("SELECT *  FROM recenze_table WHERE id_hospody = :idHospody AND id_uzivatele = :idUzivatele")
    fun findRecenzeByIds(idHospody:Int, idUzivatele:Int): List<RecenzeModel>

    @Query("SELECT *  FROM recenze_table WHERE id_hospody = :idHospody")
    fun findRecenzeByHospoda(idHospody:Int): List<RecenzeModel>

    @Query("SELECT AVG(hodnoceni) FROM recenze_table WHERE id_hospody = :idHospody")
    fun getRating(idHospody:Int): Int
}