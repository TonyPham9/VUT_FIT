/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.textfield.TextInputEditText
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.UserModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

/**
 * Controller a pro zobrazení účtu
 */
class ProfilActivity : AppCompatActivity() {

    private lateinit var appDatabase : AppDatabase
    private lateinit var user : List<UserModel>

    //Při vytvoření
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_profil)

        //buttony
        val button_odhlasit_profil = findViewById<Button>(R.id.button_odhlasit_profil)
        val button_add_hospoda_profil = findViewById<Button>(R.id.button_add_hospoda_profil)
        val button_spravovat_hospoda_profil = findViewById<Button>(R.id.button_spravovat_hospoda_profil)
        val button_oblibene_hospoda_profil = findViewById<Button>(R.id.button_oblibene_hospoda_profil)

        val button_zmenit_email_profil = findViewById<Button>(R.id.button_zmenit_email_profil)
        val button_zmenit_heslo_profil = findViewById<Button>(R.id.button_zmenit_heslo_profil)

        appDatabase = AppDatabase.getDatabase(this)

        //menu handle
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
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
        button_odhlasit_profil.setOnClickListener {
            GlobalClass.globalUserId = 0
            GlobalClass.globalUserPWD = ""
            startActivity(Intent(this, MainActivity::class.java))
        }

        button_add_hospoda_profil.setOnClickListener {
            startActivity(Intent(this, AddHospodaActivity::class.java))
        }

        button_spravovat_hospoda_profil.setOnClickListener {
            startActivity(Intent(this,ShowMyPubsActivity::class.java))
        }

        button_oblibene_hospoda_profil.setOnClickListener {
            startActivity(Intent(this,ShowFavouritePubsActivity::class.java))
        }

        button_zmenit_email_profil.setOnClickListener{
            updateEmail()
        }

        button_zmenit_heslo_profil.setOnClickListener {
            updateHeslo()
        }
    }

    /**
     * Metoda pro změnění emailu
     */
    private fun updateEmail() {
        //inputy
        val input_password = findViewById<TextInputEditText>(R.id.input_zmenit_email_heslo_profil)
        val input_email = findViewById<TextInputEditText>(R.id.input_zmenit_email_email_profil)

        val password = input_password.text.toString()
        val email = input_email.text.toString()

        if (email.isEmpty()){
            input_email.error = "Zadejte nový email"
        }else if(password.isEmpty()){
            input_password.error = "Zadejte heslo"
        }else{
            //pokud jsou vyplněné
            if (password == GlobalClass.globalUserPWD){
                //pokud je správné heslo
                GlobalScope.launch(Dispatchers.IO){
                    appDatabase.getUserDao().updateEmail(GlobalClass.globalUserId, email) //update Db
                    withContext(Dispatchers.Main){
                        input_email.text!!.clear()
                        input_password.text!!.clear()
                    }
                }
            }else{
                input_password.text!!.clear()
                input_password.error = "Nesprávné heslo"
            }
        }
    }

    /**
     * Metoda pro změnění hesla
     */
    private fun updateHeslo() {
        //inputy
        val input_password = findViewById<TextInputEditText>(R.id.input_zmenit_heslo_heslo1_profil)
        val input_password2 = findViewById<TextInputEditText>(R.id.input_zmenit_heslo_heslo2_profil)

        val password = input_password.text.toString()
        val password2 = input_password2.text.toString()

        if (password2.isEmpty()){
            input_password2.error = "Zadejte nové heslo"
        }else if(password.isEmpty()){
            input_password.error = "Zadejte heslo"
        }else{
            //pokud jsou vyplněné
            if (password == GlobalClass.globalUserPWD){
                //pokud je správné heslo
                GlobalScope.launch {
                    appDatabase.getUserDao().updatePassword(GlobalClass.globalUserId, password2) //update Db
                    GlobalClass.globalUserPWD = password2
                }
            }else{
                input_password.error = "Nesprávné heslo"
            }
        }
    }
}