/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt.Models

import androidx.room.ColumnInfo
import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "hospoda_table")
data class HospodaModel(
    @PrimaryKey(autoGenerate = true) val ID_hospoda: Int?,
    @ColumnInfo(name = "nazev") var nazev: String?,
    @ColumnInfo(name = "poloha") var poloha: String?,
    @ColumnInfo(name = "pocet_stolu") var pocet_stolu: Int?,
    @ColumnInfo(name = "po_od") var po_od: String?,
    @ColumnInfo(name = "po_do") var po_do: String?,
    @ColumnInfo(name = "ut_od") var ut_od: String?,
    @ColumnInfo(name = "ut_do") var ut_do: String?,
    @ColumnInfo(name = "st_od") var st_od: String?,
    @ColumnInfo(name = "st_do") var st_do: String?,
    @ColumnInfo(name = "ct_od") var ct_od: String?,
    @ColumnInfo(name = "ct_do") var ct_do: String?,
    @ColumnInfo(name = "pa_od") var pa_od: String?,
    @ColumnInfo(name = "pa_do") var pa_do: String?,
    @ColumnInfo(name = "so_od") var so_od: String?,
    @ColumnInfo(name = "so_do") var so_do: String?,
    @ColumnInfo(name = "ne_od") var ne_od: String?,
    @ColumnInfo(name = "ne_do") var ne_do: String?,
    @ColumnInfo(name = "Id_user") var Id_hospoda: Int?
){

}
