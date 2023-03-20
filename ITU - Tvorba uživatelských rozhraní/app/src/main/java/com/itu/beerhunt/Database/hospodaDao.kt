/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt.Database

import androidx.room.Dao
import androidx.room.Delete
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import androidx.room.Update
import com.itu.beerhunt.Models.HospodaModel

@Dao
interface HospodaDao {
    @Query("SELECT * FROM hospoda_table")
    fun getAllHospoda(): List<HospodaModel>

    @Query("SELECT * FROM hospoda_table WHERE hospoda_table.Id_user LIKE :id_user")
    fun getHospodaByUser(id_user : Int): List<HospodaModel>

    @Query("SELECT * FROM hospoda_table WHERE hospoda_table.ID_hospoda LIKE :id_hospoda LIMIT 1")
    fun getHospodaByID(id_hospoda : Int): HospodaModel

    @Insert(onConflict = OnConflictStrategy.IGNORE)
    fun insertHospoda(hospodaModel: HospodaModel) : Long

    @Query("SELECT * FROM hospoda_table WHERE ID_hospoda = :idHospody")
    fun findHospodaById(idHospody:Int): List<HospodaModel>

    @Update
    fun updateHospoda(hospodaModel: HospodaModel)

    @Query("UPDATE hospoda_table SET pocet_stolu=:p_stolu WHERE ID_hospoda LIKE :Id_hospody")
    fun updatePocetStolu(Id_hospody: Int, p_stolu: Int)

    @Query("SELECT ID_hospoda,hospoda_table.nazev,poloha,pocet_stolu,po_od,po_do,ut_od,ut_do,st_od," +
            "st_do,ct_od,ct_do,pa_od,pa_do,so_od,so_do,ne_od,ne_do FROM hospoda_table " +
            "JOIN favorite_table ON hospoda_table.ID_hospoda = favorite_table.id_hospody WHERE id_uzivatele = :id_user")
    fun getOblibene(id_user : Int): List<HospodaModel>

    @Query("SELECT ID_hospoda,hospoda_table.nazev,poloha,pocet_stolu,po_od,po_do,ut_od,ut_do,st_od,st_do,ct_od,ct_do,pa_od,pa_do,so_od,so_do,ne_od,ne_do FROM hospoda_table JOIN piva_table ON hospoda_table.ID_hospoda = piva_table.ID_hospody WHERE pocet_stolu <= :pocet_stolu_filter AND cena <= :cena_filter AND piva_table.nazev LIKE :pivo_filter AND piva_table.dostupnost LIKE 1 GROUP BY ID_hospody")
    fun getFitrovane(pocet_stolu_filter : String, cena_filter : String, pivo_filter : String): List<HospodaModel>

    @Query("SELECT * FROM hospoda_table WHERE pocet_stolu <= :pocet_stolu_filter")
    fun getFitrovanePocetStolu(pocet_stolu_filter : String): List<HospodaModel>
}