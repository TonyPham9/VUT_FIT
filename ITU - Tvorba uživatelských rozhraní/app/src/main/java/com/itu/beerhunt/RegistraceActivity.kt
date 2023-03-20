/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.Toast
import androidx.lifecycle.lifecycleScope
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.textfield.TextInputEditText
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.UserModel
import kotlinx.coroutines.*

/**
 * Controller a pro registraci do aplikace
 */
class RegistraceActivity : AppCompatActivity() {
    private lateinit var appDatabase: AppDatabase
    private lateinit var user_name: List<UserModel>
    private var Id_user: Long = 0

    //Při vytvoření
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_registrace)

        if(GlobalClass.globalUserId != 0){
            startActivity(Intent(this, MainActivity::class.java))
        }

        //buttony
        val button_registrovat = findViewById<Button>(R.id.button_registrovat)
        appDatabase = AppDatabase.getDatabase(this)

        //menu handle
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.login -> {
                        startActivity(Intent(this, LoginActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }

        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)

        //akce pro buttony
        button_registrovat.setOnClickListener {
            writeData()
        }
    }

    /**
     * Metoda pro zapsání nového uživatele
     */
    private fun writeData() {
        //inputy
        val input_username = findViewById<TextInputEditText>(R.id.input_prihlasovaci_jmeno_reg)
        val input_email = findViewById<TextInputEditText>(R.id.input_email_reg)
        val input_password = findViewById<TextInputEditText>(R.id.input_prihlasovaci_heslo1_reg)
        val input_password2 = findViewById<TextInputEditText>(R.id.input_prihlasovaci_heslo2_reg)

        val username = input_username.text.toString()
        val email = input_email.text.toString()
        val password = input_password.text.toString()
        val password2 = input_password2.text.toString()
        val picture = "default"

        if (username.isEmpty()) {
            input_username.error = "Zadejte jméno"
        }else if(email.isEmpty()) {
            input_email.error = "Zadejte e-mail"
        }else if (password.isEmpty()) {
            input_password.error = "Zadejte heslo"
        }else if (password2.isEmpty()) {
            input_password2.error = "Zadejte znovu heslo"
        }else if (password != password2){
            input_password2.error = "Hesla se neschodují"
        }else{
            //pokud jsou vyplněné
            GlobalScope.launch{
                user_name = appDatabase.getUserDao().findUserByUsername(username)
                withContext(Dispatchers.IO){
                    val user = UserModel(null,username,email,password,picture, null)
                    if(user_name.isEmpty()){        //pokud uživatel s tímto jménem neexistuje
                        Id_user = appDatabase.getUserDao().insertUser(user) //přidání do Db
                        GlobalClass.globalUserId = Id_user.toInt()
                        GlobalClass.globalUserPWD = password2
                        startActivity(Intent(this@RegistraceActivity, ProfilActivity::class.java))
                    }
                }
                withContext(Dispatchers.Main){
                    if (user_name.isNotEmpty()){
                        input_username.error="Jméno existuje"
                    }
                }
            }
        }
    }
}