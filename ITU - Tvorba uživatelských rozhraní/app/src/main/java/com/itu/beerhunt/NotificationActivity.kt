/**
 * @author Vít Janeček, xjanec30
 */
package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.LinearLayout
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.AktualitaModel
import com.itu.beerhunt.Models.FavoriteModel
import com.itu.beerhunt.Models.HospodaModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

/**
 * Controller pro zobrazení aktualit
 */
class NotificationActivity : AppCompatActivity() {
    private lateinit var appDatabase: AppDatabase
    private lateinit var aktuality: List<AktualitaModel>
    private lateinit var mojeOblibeneHospody: List<HospodaModel>
    private lateinit var notifikace: ArrayList<Notifikace>
    private lateinit var notifikaceAdapter: NotifikaceAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager

    @SuppressLint("NotifyDataSetChanged")
    //Při vytvoření
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_notification)

        //příprava pro recycle view
        notifikace = arrayListOf()
        notifikaceAdapter = NotifikaceAdapter(notifikace, this@NotificationActivity)
        linearLayoutManager = LinearLayoutManager(this)
        val recyclerview = findViewById<RecyclerView>(R.id.notifikace_recycler)

        recyclerview.apply {
            adapter = notifikaceAdapter
            layoutManager = linearLayoutManager
        }

        appDatabase = AppDatabase.getDatabase(this)

        getData() //získání notifikací

        //menu handle
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this@NotificationActivity, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this@NotificationActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }

        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
    }

    /**
     * Metoda pro získání oblíbených hospod
     */
    private fun getData() {
        GlobalScope.launch {
            mojeOblibeneHospody = appDatabase.getHospodaDao().getOblibene(GlobalClass.globalUserId) //list modelů z Db
            withContext(Dispatchers.Main){
                getAktuality() //získání z nich aktualit
            }
        }
    }

    /**
     * Metoda pro získání poslední aktuality z každé hospody
     */
    @SuppressLint("NotifyDataSetChanged")
    private fun getAktuality(){
        GlobalScope.launch {
            //pro každou hospodu
            mojeOblibeneHospody.forEach {
                val nullableInt : Int? = it.ID_hospoda
                val nonNullableInt : Int = nullableInt!!
                aktuality = appDatabase.getAktualitaDao().getLastAktualitaByID(nonNullableInt) //list modelů z Db
                withContext(Dispatchers.Main){
                    if(aktuality.isNotEmpty()){
                        notifikace.add(Notifikace(nonNullableInt, aktuality[0].aktualita, aktuality[0].datum)) //každou přidat do arrayListu pro zobrazení
                        notifikaceAdapter.notifyDataSetChanged() //aktualizace zobrazení
                    }
                }
            }
        }
    }
}