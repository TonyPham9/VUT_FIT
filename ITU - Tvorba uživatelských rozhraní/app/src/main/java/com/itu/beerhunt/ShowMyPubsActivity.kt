/**
 * @author Radek Šerejch, xserej00
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
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.HospodaModel
import kotlinx.coroutines.*

class ShowMyPubsActivity : AppCompatActivity() {
    //pomocné proměnné
    private lateinit var appDatabase: AppDatabase
    private lateinit var hospody_database : List<HospodaModel>
    private lateinit var hospody : ArrayList<Hospody>
    private lateinit var hospodaAdapter: MojeHospodaAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager
    @SuppressLint("NotifyDataSetChanged")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_show_my_pubs)
        hospody = arrayListOf()
        hospodaAdapter = MojeHospodaAdapter(hospody, this@ShowMyPubsActivity)
        linearLayoutManager = LinearLayoutManager(this)
        //získání prvků z viewu
        val recyclerview = findViewById<RecyclerView>(R.id.mojeHospody_recycler)
        val zpetBtn = findViewById<Button>(R.id.button_zpet)

        recyclerview.apply {
            adapter = hospodaAdapter
            layoutManager = linearLayoutManager
        }

        //inicializace databáze
        appDatabase = AppDatabase.getDatabase(this)

        //získám data z databáze
        getData()
        //obsluha menu
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this@ShowMyPubsActivity, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this@ShowMyPubsActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }
        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
        //obsluha tlačítka zpět
        zpetBtn.setOnClickListener {
            finish()
        }


    }

    //funkce pro načtení dat z databáze a předání kontroleru recycler viewu
    @SuppressLint("NotifyDataSetChanged")
    @OptIn(DelicateCoroutinesApi::class)
    private fun getData(){
        GlobalScope.launch(Dispatchers.IO) {
            hospody_database = appDatabase.getHospodaDao().getHospodaByUser(GlobalClass.globalUserId)
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