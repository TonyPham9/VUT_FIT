/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt.Models

import androidx.room.ColumnInfo
import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "favorite_table", primaryKeys = ["id_hospody","id_uzivatele"])
data class FavoriteModel(
    @ColumnInfo(name = "id_hospody") val hospoda: Int,
    @ColumnInfo(name = "id_uzivatele") val user: Int,

    )
