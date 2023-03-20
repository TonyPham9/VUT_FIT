/**
 * @author Vít Janeček, xjanec30
 */
package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.TextView
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.HospodaModel
import com.itu.beerhunt.Models.PivoModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

/**
 * Controller a pro pohled číšníka
 */
class CisnikActivity : AppCompatActivity() {
    private var Id_hospoda : Int = 0
    private lateinit var appDatabase: AppDatabase
    private lateinit var hospoda: HospodaModel
    private lateinit var piva: List<PivoModel>
    private lateinit var pivaArray: ArrayList<PivoCisnik>
    private lateinit var mistoText: TextView
    private lateinit var pivoCisnikAdapter: PivoCisnikAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager

    //Při vytvoření
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_cisnik)

        appDatabase = AppDatabase.getDatabase(this)
        //buttony
        val minusMistoBtn = findViewById<Button>(R.id.button_minus_mista)
        val plusMistoBtn = findViewById<Button>(R.id.button_plus_mista)
        //příprava pro recycle view
        val recyclerview = findViewById<RecyclerView>(R.id.piva_cisnik_recycler)
        mistoText = findViewById<TextView>(R.id.text_pocet_mist)

        pivaArray = arrayListOf()
        pivoCisnikAdapter = PivoCisnikAdapter(pivaArray, this)
        linearLayoutManager = LinearLayoutManager(this)
        recyclerview.apply {
            adapter = pivoCisnikAdapter
            layoutManager = linearLayoutManager
        }

        //přenesený atribut
        Id_hospoda = intent.getIntExtra("hospoda",0)

        getHospoda() //získání hospody ve které pracuje

        getPiva()  //získání piv hospody

        //akce pro buttony
        minusMistoBtn.setOnClickListener {
            minusMisto()
        }

        plusMistoBtn.setOnClickListener {
            plusMisto()
        }

        //menu handle
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.logout -> {
                        GlobalClass.globalUserId = 0
                        GlobalClass.globalUserPWD = ""
                        startActivity(Intent(this@CisnikActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }
        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
    }

    /**
     * Metoda pro získání piv na čepu hospody, ve které číšník pracuje
     */
    @SuppressLint("NotifyDataSetChanged")
    private fun getPiva() {
        GlobalScope.launch {
            piva = appDatabase.getPivoDao().getPivaByHospodaID(Id_hospoda) //list modelů z Db
            withContext(Dispatchers.Main) {
                piva.forEach {
                    val nullableInt : Int? = it.ID_piva
                    val nonNullableInt : Int = nullableInt!!
                    pivaArray.add(PivoCisnik(it.nazev, it.dostupnost, nonNullableInt)) //každý přidat do arrayListu pro zobrazení
                }
                pivoCisnikAdapter.notifyDataSetChanged() //aktualizace zobrazení
            }
        }
    }

    /**
     * Metoda pro získání hospody, ve které číšník pracuje
     */
    private fun getHospoda() {
        GlobalScope.launch {
            hospoda = appDatabase.getHospodaDao().getHospodaByID(Id_hospoda) //model z Db
            withContext(Dispatchers.Main){
                mistoText.text = hospoda.pocet_stolu.toString()
            }
        }
    }

    /**
     * Metoda pro zvýšení volných stolů
     */
    private fun plusMisto() {
        var mistoTextInt: Int = mistoText.text.toString().toInt()
        mistoTextInt++
        mistoText.text = mistoTextInt.toString()
        GlobalScope.launch {
            appDatabase.getHospodaDao().updatePocetStolu(Id_hospoda,mistoTextInt) //update Dp
        }
    }

    /**
     * Metoda pro snížení volných stolů
     */
    private fun minusMisto() {
        var mistoTextInt: Int = mistoText.text.toString().toInt()
        if (mistoTextInt > 0){
            mistoTextInt--
            mistoText.text = mistoTextInt.toString()
            GlobalScope.launch {
                appDatabase.getHospodaDao().updatePocetStolu(Id_hospoda,mistoTextInt) //update Dp
            }
        }
    }
}