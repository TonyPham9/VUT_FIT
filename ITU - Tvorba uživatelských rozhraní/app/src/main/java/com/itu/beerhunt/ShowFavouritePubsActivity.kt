package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.HospodaModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

class ShowFavouritePubsActivity : AppCompatActivity() {
    //pomocné proměnné
    private lateinit var appDatabase: AppDatabase
    private lateinit var hospody_database : List<HospodaModel>
    private lateinit var hospody : ArrayList<Hospody>
    private lateinit var hospodaAdapter: HospodaAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_show_favourite_pubs)
        //inicializace databáze
        appDatabase = AppDatabase.getDatabase(this)
        hospody = arrayListOf()
        readData()

        hospodaAdapter = HospodaAdapter(hospody, this@ShowFavouritePubsActivity)
        linearLayoutManager = LinearLayoutManager(this)

        //získání prvků z viewu
        val recyclerview = findViewById<RecyclerView>(R.id.oblibeneHospody_recycler)
        val zpetBtn = findViewById<Button>(R.id.button_zpet)

        recyclerview.apply {
            adapter = hospodaAdapter
            layoutManager = linearLayoutManager
        }

        //obsluha tlačítka zpět
        zpetBtn.setOnClickListener(){
            finish()
        }

        //obsluha menu
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this@ShowFavouritePubsActivity, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this@ShowFavouritePubsActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }
        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)


    }

    //funkce pro načtení dat z databáze a předání kontroleru recycler viewu
    @SuppressLint("NotifyDataSetChanged")
    private fun readData(){
        GlobalScope.launch(Dispatchers.IO) {
            hospody_database = appDatabase.getHospodaDao().getOblibene(GlobalClass.globalUserId)
            withContext(Dispatchers.Main){
                hospody_database.forEach{
                    val nullableInt : Int? = it.ID_hospoda
                    val nonNullableInt : Int = nullableInt!!
                    hospody.add(Hospody(it.nazev.toString(),nonNullableInt))
                }
                hospodaAdapter.notifyDataSetChanged()
            }
        }
    }
}