/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt.Database

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.itu.beerhunt.Models.HospodaModel
import com.itu.beerhunt.Models.PivoModel

@Dao
interface PivoDao{
    @Insert(onConflict = OnConflictStrategy.IGNORE)
    fun insertPivo(pivoModel: PivoModel)

    @Query("SELECT * FROM piva_table WHERE piva_table.ID_hospody LIKE :id_hospoda")
    fun getPivaByHospodaID(id_hospoda : Int): List<PivoModel>

    @Query("DELETE FROM piva_table WHERE ID_hospody = :id_hospoda")
    fun deletePivoByHospodaID(id_hospoda: Int)

    @Query("UPDATE piva_table SET dostupnost=:dostupnost WHERE ID_piva LIKE :Id_piva")
    fun updateDostupnost(Id_piva: Int, dostupnost: Boolean)
}