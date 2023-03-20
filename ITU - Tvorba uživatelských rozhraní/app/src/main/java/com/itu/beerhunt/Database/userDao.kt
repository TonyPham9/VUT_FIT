/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt.Database

import androidx.room.Dao
import androidx.room.Insert
import androidx.room.OnConflictStrategy
import androidx.room.Query
import com.itu.beerhunt.Models.UserModel


@Dao
interface UserDao {

    @Query("SELECT * FROM user_table")
    fun getAllUser(): List<UserModel>

    @Query("SELECT * FROM user_table WHERE ID_user LIKE :user_id LIMIT 1")
    fun findUserById(user_id: Int): List<UserModel>

    @Query("SELECT * FROM user_table WHERE username LIKE :user_name LIMIT 1")
    fun findUserByUsername(user_name: String): List<UserModel>

    @Query("SELECT * FROM user_table WHERE Id_hospoda =:Id_hospody")
    fun findCisnik(Id_hospody: Int): List<UserModel>

    @Insert(onConflict = OnConflictStrategy.IGNORE, )
    fun insertUser(userModel: UserModel) : Long

    @Query("UPDATE user_table SET email=:user_email WHERE ID_user LIKE :user_id")
    fun updateEmail(user_id: Int,user_email:String)

    @Query("UPDATE user_table SET password=:user_password WHERE ID_user LIKE :user_id")
    fun updatePassword(user_id: Int,user_password:String)

    @Query("DELETE FROM user_table WHERE ID_user = :Id_cisnik")
    fun deleteCisnik(Id_cisnik: Int)
}