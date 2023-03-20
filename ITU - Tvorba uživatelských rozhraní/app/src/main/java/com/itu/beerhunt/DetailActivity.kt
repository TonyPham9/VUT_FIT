/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt

import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.widget.Button
import android.widget.RatingBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.AktualitaModel
import com.itu.beerhunt.Models.HospodaModel
import com.itu.beerhunt.Models.PivoModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import kotlin.properties.Delegates

class DetailActivity : AppCompatActivity() {
    //Pomocné proměnné
    private var Id_hospoda : Int = 0
    private lateinit var appDatabase: AppDatabase
    private lateinit var detail_pub: List<HospodaModel>
    private var rating by Delegates.notNull<Int>()
    private lateinit var last_aktualita : List<AktualitaModel>
    private lateinit var all_piva : List<PivoModel>

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        //stažení paramentrů
        Id_hospoda = intent.getIntExtra("hospoda",0)

        //inicializuji databázi
        appDatabase = AppDatabase.getDatabase(this)

        //pokud se jedná o nepřihlášeného uživatele
        if (GlobalClass.globalUserId == 0) {

            setContentView(R.layout.activity_detail)

            //nastavení jména
            GlobalScope.launch(Dispatchers.IO) {
                detail_pub = appDatabase.getHospodaDao().findHospodaById(Id_hospoda)
                withContext(Dispatchers.Main) {
                    val nazev_hospody = findViewById<TextView>(R.id.pub_name_detail)
                    nazev_hospody.text = detail_pub[0].nazev.toString()

                }
            }

            //nastavení hodnocení
            val ratingBar = findViewById<RatingBar>(R.id.rating_bar)
            GlobalScope.launch {
                rating = appDatabase.getRecenzeDao().getRating(Id_hospoda)
                withContext(Dispatchers.Main) {
                    ratingBar.rating = rating.toFloat()

                }
            }

            //tlačítko zpět
            val buttonBack = findViewById<Button>(R.id.button_back_to_search)
            buttonBack.setOnClickListener {
                finish()
            }

            //tlačítko recenze
            val buttonRecenze = findViewById<Button>(R.id.button_recenze)
            buttonRecenze.setOnClickListener {
                Intent(this, RecenzeActivity::class.java).also {
                    it.putExtra("hospoda", Id_hospoda)
                    startActivity(it)
                }
            }

            //tlačítko navigovat
            val buttonNavigovat = findViewById<Button>(R.id.navigovat_button)
            buttonNavigovat.setOnClickListener {
                val uri = "http://maps.google.com/maps"
                val intent = Intent(Intent.ACTION_VIEW, Uri.parse(uri))
                startActivity(intent)
            }

            //text s aktualitami
            val aktualityText = findViewById<TextView>(R.id.popis_content)
            GlobalScope.launch {
                last_aktualita = appDatabase.getAktualitaDao().getLastAktualitaByID(Id_hospoda)
                withContext(Dispatchers.Main) {
                    if(last_aktualita.isEmpty())
                        aktualityText.text = ""
                    else
                        aktualityText.text = last_aktualita[0].aktualita

                }
            }

            //text na cepu
            val naCepuText = findViewById<TextView>(R.id.na_cepu_content)
            GlobalScope.launch {
                all_piva = appDatabase.getPivoDao().getPivaByHospodaID(Id_hospoda)
                withContext(Dispatchers.Main) {
                    if(all_piva.isEmpty())
                        aktualityText.text = ""
                    else
                        all_piva.forEach{
                            if(it.dostupnost == true) {
                                naCepuText.text = naCepuText.text.toString() + it.nazev + "\n"
                            }
                        }

                }
            }

            //obsluha menu
            val mOnNavigationItemSelectedListener =
                BottomNavigationView.OnNavigationItemSelectedListener { item ->
                    when (item.itemId) {
                        R.id.login -> {
                            startActivity(Intent(this@DetailActivity, LoginActivity::class.java))
                            return@OnNavigationItemSelectedListener true
                        }
                        R.id.home -> {
                            startActivity(Intent(this@DetailActivity, MainActivity::class.java))
                            return@OnNavigationItemSelectedListener true
                        }
                    }
                    false
                }

            val nav = findViewById<BottomNavigationView>(R.id.nav)
            nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
            //pokud uživatel je přihlášený
        } else {

            setContentView(R.layout.activity_detail_logged)

            //nastavení jména
            GlobalScope.launch(Dispatchers.IO) {
                detail_pub = appDatabase.getHospodaDao().findHospodaById(Id_hospoda)
                withContext(Dispatchers.Main) {
                    val nazev_hospody = findViewById<TextView>(R.id.pub_name_detail)
                    nazev_hospody.text = detail_pub[0].nazev.toString()

                }
            }

            //nastavení hodnocení
            val ratingBar = findViewById<RatingBar>(R.id.rating_bar)
            GlobalScope.launch {
                rating = appDatabase.getRecenzeDao().getRating(Id_hospoda)
                withContext(Dispatchers.Main) {
                    ratingBar.rating = rating.toFloat()

                }
            }

            //tlačítko zpět
            val buttonBack = findViewById<Button>(R.id.button_back_to_search)
            buttonBack.setOnClickListener {
                finish()
            }

            //tlačítko recenze
            val buttonRecenze = findViewById<Button>(R.id.button_recenze)
            buttonRecenze.setOnClickListener {
                Intent(this, RecenzeActivity::class.java).also {
                    it.putExtra("hospoda", Id_hospoda)
                    startActivity(it)
                }
            }

            //tlačítko navigovat
            val buttonNavigovat = findViewById<Button>(R.id.navigovat_button)
            buttonNavigovat.setOnClickListener {
                val uri = "http://maps.google.com/maps"
                val intent = Intent(Intent.ACTION_VIEW, Uri.parse(uri))
                startActivity(intent)
            }

            //text s aktualitami
            val aktualityText = findViewById<TextView>(R.id.popis_content)
            GlobalScope.launch {
                last_aktualita = appDatabase.getAktualitaDao().getLastAktualitaByID(Id_hospoda)
                withContext(Dispatchers.Main) {
                    if(last_aktualita.isEmpty())
                        aktualityText.text = ""
                    else
                        aktualityText.text = last_aktualita[0].aktualita

                }
            }

            //text na cepu
            val naCepuText = findViewById<TextView>(R.id.na_cepu_content)
            GlobalScope.launch {
                all_piva = appDatabase.getPivoDao().getPivaByHospodaID(Id_hospoda)
                withContext(Dispatchers.Main) {
                    if(all_piva.isEmpty())
                        aktualityText.text = ""
                    else
                        all_piva.forEach{
                            if(it.dostupnost == true) {
                                naCepuText.text = naCepuText.text.toString() + it.nazev + "\n"
                            }
                        }

                }
            }

            //obsluha menu
            val mOnNavigationItemSelectedListener =
                BottomNavigationView.OnNavigationItemSelectedListener { item ->
                    when (item.itemId) {
                        R.id.profil -> {
                            startActivity(Intent(this@DetailActivity, ProfilActivity::class.java))
                            return@OnNavigationItemSelectedListener true
                        }
                        R.id.home -> {
                            startActivity(Intent(this@DetailActivity, MainActivity::class.java))
                            return@OnNavigationItemSelectedListener true
                        }
                    }
                    false
                }

            val nav = findViewById<BottomNavigationView>(R.id.nav)
            nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
        }
    }
}