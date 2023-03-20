/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt.Models

import androidx.room.ColumnInfo
import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "piva_table")
class PivoModel (
    @PrimaryKey(autoGenerate = true) val ID_piva: Int?,
    @ColumnInfo(name = "nazev") val nazev: String,
    @ColumnInfo(name = "cena") val cena: Double,
    @ColumnInfo(name = "ID_hospody") val ID_hospody:Int?,
    @ColumnInfo(name = "dostupnost") val dostupnost:Boolean
){
}