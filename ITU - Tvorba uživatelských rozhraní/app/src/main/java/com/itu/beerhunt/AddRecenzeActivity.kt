/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt

import android.content.Intent
import android.os.Build
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.RatingBar
import androidx.annotation.RequiresApi
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.RecenzeModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import java.time.LocalDate

class AddRecenzeActivity : AppCompatActivity() {
    //Pomocné proměnné
    private var Id_hospoda : Int = 0
    private lateinit var appDatabase: AppDatabase
    private lateinit var ratingBar: RatingBar
    private lateinit var recenze: EditText
    @RequiresApi(Build.VERSION_CODES.O)

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        setContentView(R.layout.activity_add_recenze)

        //inicializuji databázi
        appDatabase = AppDatabase.getDatabase(this)

        //stažení paramentrů
        Id_hospoda = intent.getIntExtra("hospoda",0)

        //tlačítko zpět
        val buttonBack = findViewById<Button>(R.id.button_back_to_review)
        buttonBack.setOnClickListener {
            finish()
        }

        //nastavení hodnocení
        ratingBar = findViewById(R.id.rating_bar)
        ratingBar.stepSize = 1f

        //tlačítko poslat recenzi
        val buttonSubmit = findViewById<Button>(R.id.button_submit_review)
        buttonSubmit.setOnClickListener {
            GlobalScope.launch(Dispatchers.IO){
                    recenze = findViewById(R.id.input_recenze)
                    appDatabase.getRecenzeDao().insertRecenze(
                        RecenzeModel(
                            null,
                            Id_hospoda,
                            GlobalClass.globalUserId,
                            ratingBar.rating.toInt(),
                            recenze.text.toString(),
                            LocalDate.now().toString()
                        )
                    )
                finish()
            }

        }

        //obsluha menu
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this@AddRecenzeActivity, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this@AddRecenzeActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }

        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)

    }
}