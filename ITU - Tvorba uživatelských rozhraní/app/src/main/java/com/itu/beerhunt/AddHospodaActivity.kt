/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.CheckBox
import android.widget.EditText
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.HospodaModel
import com.itu.beerhunt.Models.PivoModel
import com.itu.beerhunt.Models.UserModel
import kotlinx.coroutines.*

class AddHospodaActivity : AppCompatActivity() {
    //pomocné proměnné
    private lateinit var piva : ArrayList<Pivo>
    private lateinit var pivoAdapter: PivoAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager
    private lateinit var appDatabase: AppDatabase

    @OptIn(DelicateCoroutinesApi::class)
    @SuppressLint("NotifyDataSetChanged")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_add_hospoda)

        //získání prvků z viewu
        val pivo_button = findViewById<Button>(R.id.pivo_button)
        val pivo = findViewById<EditText>(R.id.input_pivo)
        val cena = findViewById<EditText>(R.id.input_pivo_cena)
        val recyclerview = findViewById<RecyclerView>(R.id.piva_recycler)
        val sendBtn = findViewById<Button>(R.id.send_button)

        val jmeno_in = findViewById<EditText>(R.id.input_jmeno_hospoda)
        val poloha_in = findViewById<EditText>(R.id.input_poloha)
        val stoly_in = findViewById<EditText>(R.id.input_stoly)
        val po_od_in = findViewById<EditText>(R.id.po_od)
        val po_do_in = findViewById<EditText>(R.id.po_do)
        val ut_od_in = findViewById<EditText>(R.id.ut_od)
        val ut_do_in = findViewById<EditText>(R.id.ut_do)
        val st_od_in = findViewById<EditText>(R.id.st_od)
        val st_do_in = findViewById<EditText>(R.id.st_do)
        val ct_od_in = findViewById<EditText>(R.id.ct_od)
        val ct_do_in = findViewById<EditText>(R.id.ct_do)
        val pa_od_in = findViewById<EditText>(R.id.pa_od)
        val pa_do_in = findViewById<EditText>(R.id.pa_do)
        val so_od_in = findViewById<EditText>(R.id.so_od)
        val so_do_in = findViewById<EditText>(R.id.so_do)
        val ne_od_in = findViewById<EditText>(R.id.ne_od)
        val ne_do_in = findViewById<EditText>(R.id.ne_do)
        val po_checked_in = findViewById<CheckBox>(R.id.po_checked)
        val ut_checked_in = findViewById<CheckBox>(R.id.ut_checked)
        val st_checked_in = findViewById<CheckBox>(R.id.st_checked)
        val ct_checked_in = findViewById<CheckBox>(R.id.ct_checked)
        val pa_checked_in = findViewById<CheckBox>(R.id.pa_checked)
        val so_checked_in = findViewById<CheckBox>(R.id.so_checked)
        val ne_checked_in = findViewById<CheckBox>(R.id.ne_checked)

        //inicializace databáze
        appDatabase = AppDatabase.getDatabase(this)

        //inicializace kontroleru pro recycler view pro piva
        piva = arrayListOf(
        )
        pivoAdapter = PivoAdapter(piva, this)
        linearLayoutManager = LinearLayoutManager(this)

        recyclerview.apply {
            adapter = pivoAdapter
            layoutManager = linearLayoutManager
        }

        //obsluha menu
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this@AddHospodaActivity, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this@AddHospodaActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }
        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)

        //obsluha přidání piva
        pivo_button.setOnClickListener(){
            val pivo_val = pivo.text.toString()
            val cena_val = cena.text.toString().toDoubleOrNull()

            if(pivo_val.isEmpty()){
                pivo.error = "Zadejte spravne pivo"
            }
            else if(cena_val == null){
                cena.error = "Zadejte spravnou cenu"
            }
            else{
                piva.add(Pivo(pivo.text.toString(), cena.text.toString().toDouble()))

            }
            pivoAdapter.notifyDataSetChanged()

        }

        //obsluha přidání nové hospody
        sendBtn.setOnClickListener(){
            val jmeno_val = jmeno_in.text.toString()
            val poloha_val = poloha_in.text.toString()
            val stoly_val = stoly_in.text.toString()

            var po_od_val : String = ""
            var po_do_val : String = ""
            var ut_od_val : String = ""
            var ut_do_val : String = ""
            var st_od_val : String = ""
            var st_do_val : String = ""
            var ct_od_val : String = ""
            var ct_do_val : String = ""
            var pa_od_val : String = ""
            var pa_do_val : String = ""
            var so_od_val : String = ""
            var so_do_val : String = ""
            var ne_od_val : String = ""
            var ne_do_val : String = ""

            //ošetření otvírací doby
            if (po_checked_in.isChecked){
                po_od_val = po_od_in.text.toString()
                po_do_val = po_do_in.text.toString()
            }
            if(ut_checked_in.isChecked){
                ut_od_val = ut_od_in.text.toString()
                ut_do_val = ut_do_in.text.toString()
            }
            if(st_checked_in.isChecked){
                st_od_val = st_od_in.text.toString()
                st_do_val = st_do_in.text.toString()
            }
            if(ct_checked_in.isChecked){
                ct_od_val = ct_od_in.text.toString()
                ct_do_val = ct_do_in.text.toString()
            }
            if(pa_checked_in.isChecked){
                pa_od_val = pa_od_in.text.toString()
                pa_do_val = pa_do_in.text.toString()
            }
            if(so_checked_in.isChecked){
                so_od_val = so_od_in.text.toString()
                so_do_val = so_do_in.text.toString()
            }
            if(ne_checked_in.isChecked){
                ne_od_val = ne_od_in.text.toString()
                ne_do_val = ne_do_in.text.toString()
            }

            //vytvoření modelu, odeslání modelu
            val hospoda = HospodaModel(null, jmeno_val, poloha_val, stoly_val.toInt(), po_od_val,
                po_do_val, ut_od_val, ut_do_val, st_od_val, st_do_val, ct_od_val, ct_do_val, pa_od_val,
                pa_do_val, so_od_val, so_do_val, ne_od_val, ne_do_val, GlobalClass.globalUserId)
            GlobalScope.launch(Dispatchers.IO) {
                val id_hospody = appDatabase.getHospodaDao().insertHospoda(hospoda)
                withContext(Dispatchers.IO){
                    piva.forEach{
                        appDatabase.getPivoDao().insertPivo(PivoModel(null,it.nazev,it.cena,id_hospody.toInt(), true))
                    }

                }
            }
            startActivity(Intent(this@AddHospodaActivity,ProfilActivity::class.java))
        }
    }
}