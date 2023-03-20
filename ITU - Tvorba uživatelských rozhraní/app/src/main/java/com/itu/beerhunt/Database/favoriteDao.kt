/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt.Database

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.itu.beerhunt.Models.FavoriteModel


@Dao
interface FavoriteDao {

    @Query("SELECT * FROM favorite_table")
    fun getAllFavorite(): List<FavoriteModel>

    @Insert(onConflict = OnConflictStrategy.IGNORE)
    fun insertFavorite(favoriteModel: FavoriteModel) : Long

    @Query("DELETE FROM favorite_table WHERE id_hospody = :idHospody AND id_uzivatele = :idUzivatele")
    fun deleteFavorite(idHospody:Int, idUzivatele:Int)
}