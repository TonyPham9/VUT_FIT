/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import androidx.viewpager2.widget.ViewPager2
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.HospodaModel
import kotlinx.coroutines.*
import me.relex.circleindicator.CircleIndicator3

class FilteredActivity : AppCompatActivity() {
    //Pomocné proměnné
    private var titlesList = mutableListOf<String>()
    private lateinit var hospody: ArrayList<Hospody>
    private lateinit var hospodaAdapter: HospodaAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager
    private lateinit var appDatabase: AppDatabase
    private lateinit var hospoda: List<HospodaModel>

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        //inicializuji databázi
        appDatabase = AppDatabase.getDatabase(this)

        //vytvořím si pole pro kartičky hospod
        hospody = arrayListOf()

        //přidám nadpisi filtrů
        addToList("Poloha")
        addToList("Max Cena")
        addToList("Pivo")
        addToList("Místa k sezení")

        //načtu kartičky hospod
        readData()

        //pokud se jedná o nepřihlášeného uživatele
        if (GlobalClass.globalUserId == 0) {

            setContentView(R.layout.activity_filtered)

            //inicializace kontroleru pro recycler view pro hospody
            hospodaAdapter = HospodaAdapter(hospody, this@FilteredActivity)
            linearLayoutManager = LinearLayoutManager(this)

            val recyclerview = findViewById<RecyclerView>(R.id.searched_pubs)
            recyclerview.apply {
                adapter = hospodaAdapter
                layoutManager = linearLayoutManager
            }

            //zobrazím filtry a indikátor
            val filters = findViewById<ViewPager2>(R.id.filters)
            filters.adapter = FiltersAdapter(titlesList,this@FilteredActivity)
            filters.orientation = ViewPager2.ORIENTATION_HORIZONTAL

            val indicator = findViewById<CircleIndicator3>(R.id.indicator)
            indicator.setViewPager(filters)

            //tlačítko SEARCH
            val but = findViewById<Button>(R.id.button_search)
            but.setOnClickListener {
                recreate()
            }

            //obsluha menu
            val mOnNavigationItemSelectedListener =
                BottomNavigationView.OnNavigationItemSelectedListener { item ->
                    when (item.itemId) {
                        R.id.login -> {
                            startActivity(
                                Intent(
                                    this@FilteredActivity,
                                    LoginActivity::class.java
                                )
                            )
                            return@OnNavigationItemSelectedListener true
                        }
                    }
                    false
                }

            val nav = findViewById<BottomNavigationView>(R.id.nav)
            nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
            //pokud uživatel je přihlášený
        } else {

            setContentView(R.layout.activity_filtered_logged)

            //nastavím adaptér s recyclerview pro jednotlivé kartičky hospod
            hospodaAdapter = HospodaAdapter(hospody, this@FilteredActivity)
            linearLayoutManager = LinearLayoutManager(this)

            val recyclerview = findViewById<RecyclerView>(R.id.searched_pubs)
            recyclerview.apply {
                adapter = hospodaAdapter
                layoutManager = linearLayoutManager
            }

            //zobrazím filtry a indikátor
            val filters = findViewById<ViewPager2>(R.id.filters)
            filters.adapter = FiltersAdapter(titlesList,this@FilteredActivity)
            filters.orientation = ViewPager2.ORIENTATION_HORIZONTAL

            val indicator = findViewById<CircleIndicator3>(R.id.indicator)
            indicator.setViewPager(filters)

            //tlačítko SEARCH
            val but = findViewById<Button>(R.id.button_search)
            but.setOnClickListener {
                recreate()
            }

            //obsluha menu
            val mOnNavigationItemSelectedListener =
                BottomNavigationView.OnNavigationItemSelectedListener { item ->
                    when (item.itemId) {
                        R.id.profil -> {
                            startActivity(
                                Intent(
                                    this@FilteredActivity,
                                    ProfilActivity::class.java
                                )
                            )
                            return@OnNavigationItemSelectedListener true
                        }
                        R.id.notification -> {
                            startActivity(
                                Intent(
                                    this@FilteredActivity,
                                    NotificationActivity::class.java
                                )
                            )
                            return@OnNavigationItemSelectedListener true
                        }
                    }
                    false
                }

            val nav = findViewById<BottomNavigationView>(R.id.nav)
            nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)

        }

    }

    //přidání filtrů
    private fun addToList(title: String) {
        titlesList.add(title)
    }

    @SuppressLint("NotifyDataSetChanged")
    @OptIn(DelicateCoroutinesApi::class)

    //Funkce načte všechny hospody z databáze, které odpovídají zadaným filtrům
    private fun readData() {
        GlobalScope.launch(Dispatchers.IO) {
            if(GlobalClass.globalFiltrPivo == "%" && GlobalClass.globalFiltrMista == "%" && GlobalClass.globalFiltrMaxCena == "%") {
                hospoda = appDatabase.getHospodaDao().getAllHospoda()
            } else if (GlobalClass.globalFiltrPivo == "%" && GlobalClass.globalFiltrMista != "%" && GlobalClass.globalFiltrMaxCena == "%"){
                hospoda = appDatabase.getHospodaDao().getFitrovanePocetStolu(GlobalClass.globalFiltrMista)
            } else {
                val pivo : String = "%" + GlobalClass.globalFiltrPivo + "%"
                hospoda = appDatabase.getHospodaDao().getFitrovane(GlobalClass.globalFiltrMista, GlobalClass.globalFiltrMaxCena, pivo)
            }
            GlobalClass.globalFiltrMista = "%"
            GlobalClass.globalFiltrPivo = "%"
            GlobalClass.globalFiltrMaxCena = "%"
            withContext(Dispatchers.Main) {
                hospoda.forEach {
                    val nullableInt : Int? = it.ID_hospoda
                    val nonNullableInt : Int = nullableInt!!
                    hospody.add(Hospody(it.nazev.toString(),nonNullableInt))
                }
                hospodaAdapter.notifyDataSetChanged()
            }
        }
    }


}