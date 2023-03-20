/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.textfield.TextInputEditText
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.AktualitaModel
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import java.text.SimpleDateFormat
import java.util.*

class AddAktualitaActivity : AppCompatActivity() {

    //pomocné proměnné
    private var Id_hospoda : Int = 0
    private var name_hospoda : String? = ""
    private lateinit var appDatabase: AppDatabase

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_add_aktualita)

        //stažení paramentrů
        Id_hospoda = intent.getIntExtra("hospoda_id",0)
        name_hospoda = intent.getStringExtra("hospoda_jmeno")

        //inicializace databáze
        appDatabase = AppDatabase.getDatabase(this)

        //získání prvků z viewu
        val backBtn = findViewById<Button>(R.id.back_button)
        val sendBtn = findViewById<Button>(R.id.button_send_actuality)
        val nametext = findViewById<TextView>(R.id.add_aktualita_hospoda_jmeno)
        val aktualita = findViewById<TextInputEditText>(R.id.input_add_aktualita)

        //nastavení jména hospody
        nametext.text = name_hospoda.toString()

        //tlačítko zpět
        backBtn.setOnClickListener(){
            finish()
        }

        //obsluha odeslání aktuality
        sendBtn.setOnClickListener(){
            GlobalScope.launch {
                val datum = Calendar.getInstance().time.toString("dd.MM.yyyy")
                appDatabase.getAktualitaDao().insertAktualita(AktualitaModel(null,aktualita.text.toString(),Id_hospoda,datum))
            }
            startActivity(Intent(this@AddAktualitaActivity, ProfilActivity::class.java))
        }

        //obsluha menu
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this@AddAktualitaActivity, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this@AddAktualitaActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }
        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
    }

    private fun Date.toString(format: String, locale: Locale = Locale.getDefault()): String {
        val formatter = SimpleDateFormat(format, locale)
        return formatter.format(this)
    }
}