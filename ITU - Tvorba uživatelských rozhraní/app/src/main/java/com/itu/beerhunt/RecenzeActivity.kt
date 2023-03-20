/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Intent
import android.os.Build
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import androidx.annotation.RequiresApi
import androidx.core.view.isVisible
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.RecenzeModel
import com.itu.beerhunt.Models.UserModel
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch

class RecenzeActivity : AppCompatActivity() {
    //Pomocné proměnné
    private var Id_hospoda: Int = 0
    private lateinit var recenze: ArrayList<Recenze>
    private lateinit var recenzeAdapter: RecenzeAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager
    private lateinit var appDatabase: AppDatabase
    private lateinit var allRecenze: List<RecenzeModel>
    private lateinit var buttonAddReview: Button

    @SuppressLint("SuspiciousIndentation")
    @RequiresApi(Build.VERSION_CODES.O)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        //inicializuji databázi
        appDatabase = AppDatabase.getDatabase(this)

        //stažení paramentrů
        Id_hospoda = intent.getIntExtra("hospoda", 0)

        //vytvořím si pole pro kartičky recenzí
        recenze = arrayListOf()

        //stáhnu si data z databáze o všech recenzích
        GlobalScope.launch {
            var reviews: List<RecenzeModel> = appDatabase.getRecenzeDao().findRecenzeByHospoda(Id_hospoda)
            reviews.forEach {
                val user_name: List<UserModel> = appDatabase.getUserDao().findUserById(it.user)
                recenze.add(Recenze(user_name[0].username.toString(), it.recenze, it.date))
            }
        }

        //pokud se jedná o nepřihlášeného uživatele
        if (GlobalClass.globalUserId == 0) {
            setContentView(R.layout.activity_recenze)

            //inicializace kontroleru pro recycler view pro recenze
            recenzeAdapter = RecenzeAdapter(recenze)
            linearLayoutManager = LinearLayoutManager(this)

            val recyclerview = findViewById<RecyclerView>(R.id.searched_reviews)
            recyclerview.apply {
                adapter = recenzeAdapter
                layoutManager = linearLayoutManager
            }

            //tlačítko zpět
            val buttonBack = findViewById<Button>(R.id.button_back_to_pub)
            buttonBack.setOnClickListener {
                finish()
            }

            //obsluha menu
            val mOnNavigationItemSelectedListener =
                BottomNavigationView.OnNavigationItemSelectedListener { item ->
                    when (item.itemId) {
                        R.id.login -> {
                            startActivity(Intent(this@RecenzeActivity, LoginActivity::class.java))
                            return@OnNavigationItemSelectedListener true
                        }
                        R.id.home -> {
                            startActivity(Intent(this@RecenzeActivity, MainActivity::class.java))
                            return@OnNavigationItemSelectedListener true
                        }
                    }
                    false
                }

            val nav = findViewById<BottomNavigationView>(R.id.nav)
            nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
            //pokud uživatel je přihlášený
        } else {
            setContentView(R.layout.activity_recenze_logged)

            //inicializace kontroleru pro recycler view pro recenze
            recenzeAdapter = RecenzeAdapter(recenze)
            linearLayoutManager = LinearLayoutManager(this)

            val recyclerview = findViewById<RecyclerView>(R.id.searched_reviews)
            recyclerview.apply {
                adapter = recenzeAdapter
                layoutManager = linearLayoutManager
            }

            //tlačítko zpět
            val buttonBack = findViewById<Button>(R.id.button_back_to_pub)
            buttonBack.setOnClickListener {
                finish()
            }

            //pokud uživatel již pro dannou hospodu recenzi napsat nemůže napsat další
            GlobalScope.launch {
                allRecenze = appDatabase.getRecenzeDao().findRecenzeByIds(Id_hospoda,GlobalClass.globalUserId)
                    if (allRecenze.isNotEmpty()) {
                        buttonAddReview.isVisible=false
                        }
                }

            //tlačítko napsat recenzi
            buttonAddReview = findViewById(R.id.button_napsat_rezenci)
            buttonAddReview.setOnClickListener {
                Intent(this, AddRecenzeActivity::class.java).also {
                    it.putExtra("hospoda", Id_hospoda)
                    startActivity(it)
                }
            }

            //obsluha menu
            val mOnNavigationItemSelectedListener =
                BottomNavigationView.OnNavigationItemSelectedListener { item ->
                    when (item.itemId) {
                        R.id.profil -> {
                            startActivity(Intent(this@RecenzeActivity, ProfilActivity::class.java))
                            return@OnNavigationItemSelectedListener true
                        }
                        R.id.home -> {
                            startActivity(Intent(this@RecenzeActivity, MainActivity::class.java))
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
