/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.Toast
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.textfield.TextInputEditText
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.UserModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

/**
 * Controller pro přihlašování do aplikace
 */
class LoginActivity : AppCompatActivity() {
    private lateinit var appDatabase : AppDatabase
    private lateinit var user : List<UserModel>

    //Při vytvoření
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        if(GlobalClass.globalUserId != 0){
            startActivity(Intent(this, MainActivity::class.java))
        }

        //připrava buttonů
        val button_prihlasit = findViewById<Button>(R.id.button_prihlasit)
        val button_ztracene_heslo = findViewById<Button>(R.id.button_ztracene_heslo)
        val button_registrovat_login = findViewById<Button>(R.id.button_registrovat_login)

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
        button_prihlasit.setOnClickListener {
            readData()
        }

        button_ztracene_heslo.setOnClickListener {
            //ztracene heslo
        }

        button_registrovat_login.setOnClickListener {
            startActivity(Intent(this, RegistraceActivity::class.java))
        }
    }

    /**
     *
     */
    private fun readData() {
        //inputy
        val input_username = findViewById<TextInputEditText>(R.id.input_prihlasovaci_jmeno)
        val input_password = findViewById<TextInputEditText>(R.id.input_prihlasovaci_heslo)

        val username = input_username.text.toString()
        val password = input_password.text.toString()

        if (username.isEmpty()) {
            input_username.error = "Zadejte jméno"
        }else if (password.isEmpty()) {
            input_password.error = "Zadejte heslo"
        }else{
            //pokud jsou vyplněné
            GlobalScope.launch {
                user = appDatabase.getUserDao().findUserByUsername(username)
                
                withContext(Dispatchers.Main){
                    if (user.isEmpty()){
                        input_username.error = "Jméno neexistuje"
                    }else{
                        //pokud jmeno existuje a hesla se schodují
                        if (user[0].password == password){
                            val nullableInt : Int? = user[0].ID_user
                            val nonNullableInt : Int = nullableInt!!
                            GlobalClass.globalUserId = nonNullableInt
                            val nullableString : String? = user[0].password
                            val nonNullableString : String = nullableString!!
                            GlobalClass.globalUserPWD = nonNullableString

                            if (user[0].email.isNotEmpty()){ //přihlásit jako uživatel
                                startActivity(Intent(this@LoginActivity, ProfilActivity::class.java))
                            }else {
                                //přihlásit jako číšník
                                Intent(this@LoginActivity, CisnikActivity::class.java).also {
                                    val nullableInt2: Int? = user[0].Id_hospoda
                                    val nonNullableInt2: Int = nullableInt2!!
                                    it.putExtra("hospoda", nonNullableInt2)
                                    startActivity(it)
                                }
                            }
                        }else{
                            input_password.error = "Nespravne heslo"
                        }
                    }
                }
            }
        }
    }
}
