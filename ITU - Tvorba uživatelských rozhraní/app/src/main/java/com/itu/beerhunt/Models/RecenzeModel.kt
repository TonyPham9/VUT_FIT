/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt.Models

import androidx.room.ColumnInfo
import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "recenze_table")
data class RecenzeModel(
    @PrimaryKey(autoGenerate = true) val ID_favorite: Int?,
    @ColumnInfo(name = "id_hospody") val hospoda: Int,
    @ColumnInfo(name = "id_uzivatele") val user: Int,
    @ColumnInfo(name = "hodnoceni") val hodnoceni: Int,
    @ColumnInfo(name = "recenze") val recenze: String,
    @ColumnInfo(name = "date") val date: String,
    )
