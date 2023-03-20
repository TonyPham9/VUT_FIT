/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt.Models

import androidx.room.ColumnInfo
import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "aktuality_table")
class AktualitaModel(
    @PrimaryKey(autoGenerate = true) val ID_aktuality: Int?,
    @ColumnInfo(name = "aktualita") val aktualita: String,
    @ColumnInfo(name = "ID_hospody") val ID_hospody: Int?,
    @ColumnInfo(name = "datum") val datum: String
) {
}