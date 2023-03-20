/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt.Models

import androidx.room.ColumnInfo
import androidx.room.Entity
import androidx.room.PrimaryKey

@Entity(tableName = "user_table")
data class UserModel(
    @PrimaryKey(autoGenerate = true) val ID_user: Int?,
    @ColumnInfo(name = "username") val username: String?,
    @ColumnInfo(name = "email") val email: String,
    @ColumnInfo(name = "password") val password: String?,
    @ColumnInfo(name = "picture") val picture: String?,
    @ColumnInfo(name = "Id_hospoda") val Id_hospoda: Int?
){

}
