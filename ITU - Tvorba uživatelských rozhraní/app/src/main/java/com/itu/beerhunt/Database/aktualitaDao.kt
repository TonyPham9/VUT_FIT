/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt.Database

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.itu.beerhunt.Models.AktualitaModel

@Dao
interface AktualitaDao {
    @Query("SELECT * FROM aktuality_table")
    fun getAllAktuality(): List<AktualitaModel>

    @Insert(onConflict = OnConflictStrategy.IGNORE)
    fun insertAktualita(aktualitaModel: AktualitaModel)

    @Query("SELECT * FROM aktuality_table WHERE id_hospody = :idHospody ORDER BY aktualita DESC LIMIT 1")
    fun getLastAktualita(idHospody:Int): List<AktualitaModel>

    @Query("SELECT * FROM aktuality_table WHERE id_hospody = :idHospody ORDER BY ID_aktuality DESC LIMIT 1")
    fun getLastAktualitaByID(idHospody:Int): List<AktualitaModel>

    @Query("SELECT  *  FROM aktuality_table WHERE ID_hospody IN (SELECT ID_hospody FROM hospoda_table JOIN favorite_table ON hospoda_table.ID_hospoda = favorite_table.id_hospody WHERE id_uzivatele = :Id_user) ORDER BY ID_aktuality DESC")
    fun getMyFavouriteHospody(Id_user: Int): List<AktualitaModel>
}