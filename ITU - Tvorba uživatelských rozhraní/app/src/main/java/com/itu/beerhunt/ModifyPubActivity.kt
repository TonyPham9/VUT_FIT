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
import com.itu.beerhunt.databinding.ActivityMainBinding
import kotlinx.coroutines.*

class ModifyPubActivity : AppCompatActivity() {
    //pomocné proměnné
    private var Id_hospoda : Int = 0
    private lateinit var appDatabase: AppDatabase
    private lateinit var hospoda : HospodaModel
    private lateinit var piva : ArrayList<Pivo>
    private lateinit var piva_database : List<PivoModel>
    private lateinit var pivoAdapter: PivoAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager

    //proměnné pro veiw prvky
    private lateinit var jmeno_in : EditText
    private lateinit var stoly_in : EditText
    private lateinit var poloha_in : EditText
    private lateinit var po_od_in : EditText
    private lateinit var po_do_in : EditText
    private lateinit var ut_od_in : EditText
    private lateinit var ut_do_in : EditText
    private lateinit var st_od_in : EditText
    private lateinit var st_do_in : EditText
    private lateinit var ct_od_in : EditText
    private lateinit var ct_do_in : EditText
    private lateinit var pa_od_in : EditText
    private lateinit var pa_do_in : EditText
    private lateinit var so_od_in : EditText
    private lateinit var so_do_in : EditText
    private lateinit var ne_od_in : EditText
    private lateinit var ne_do_in : EditText

    private lateinit var po_checked_in : CheckBox
    private lateinit var ut_checked_in : CheckBox
    private lateinit var st_checked_in : CheckBox
    private lateinit var ct_checked_in : CheckBox
    private lateinit var pa_checked_in : CheckBox
    private lateinit var so_checked_in : CheckBox
    private lateinit var ne_checked_in : CheckBox

    @OptIn(DelicateCoroutinesApi::class)
    @SuppressLint("NotifyDataSetChanged")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_modify_pub)
        appDatabase = AppDatabase.getDatabase(this)

        //získání prvků z viewu
        jmeno_in = findViewById(R.id.input_jmeno_hospoda_upravit)
        poloha_in = findViewById(R.id.input_poloha_upravit)
        stoly_in = findViewById(R.id.input_stoly_upravit)
        po_od_in = findViewById(R.id.po_od_upravit)
        po_do_in = findViewById(R.id.po_do_upravit)
        ut_od_in = findViewById(R.id.ut_od_upravit)
        ut_do_in = findViewById(R.id.ut_do_upravit)
        st_od_in = findViewById(R.id.st_od_upravit)
        st_do_in = findViewById(R.id.st_do_upravit)
        ct_od_in = findViewById(R.id.ct_od_upravit)
        ct_do_in = findViewById(R.id.ct_do_upravit)
        pa_od_in = findViewById(R.id.pa_od_upravit)
        pa_do_in = findViewById(R.id.pa_do_upravit)
        so_od_in = findViewById(R.id.so_od_upravit)
        so_do_in = findViewById(R.id.so_do_upravit)
        ne_od_in = findViewById(R.id.ne_od_upravit)
        ne_do_in = findViewById(R.id.ne_do_upravit)
        po_checked_in = findViewById(R.id.po_checked_upravit)
        ut_checked_in = findViewById(R.id.ut_checked_upravit)
        st_checked_in = findViewById(R.id.st_checked_upravit)
        ct_checked_in = findViewById(R.id.ct_checked_upravit)
        pa_checked_in = findViewById(R.id.pa_checked_upravit)
        so_checked_in = findViewById(R.id.so_checked_upravit)
        ne_checked_in = findViewById(R.id.ne_checked_upravit)

        val updateHospodaBtn = findViewById<Button>(R.id.button_modify_pub)
        val addAktualitaBtn = findViewById<Button>(R.id.button_pridat_aktualitu)
        val spravovatcisnikyBtn = findViewById<Button>(R.id.button_spravovat_cisniky)
        val addpivoBtn = findViewById<Button>(R.id.pivo_button_upravit)
        val recyclerview = findViewById<RecyclerView>(R.id.piva_recycler_upravit)
        val pivo = findViewById<EditText>(R.id.input_pivo_upravit)
        val cena = findViewById<EditText>(R.id.input_pivo_cena_upravit)

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

        //získání parametru
        Id_hospoda = intent.getIntExtra("hospoda",0)
        //spuštění funkce pro předvyplnění prvků viewu
        downloadData()

        //tlačítko pro spuštění aktivity na přidání aktuality
        addAktualitaBtn.setOnClickListener(){
            Intent(this,AddAktualitaActivity::class.java).also {
                it.putExtra("hospoda_id",Id_hospoda)
                it.putExtra("hospoda_jmeno",hospoda.nazev)
                startActivity(it)
            }
        }

        //tlačítko pro spuštění aktivity na přidání číšníka
        spravovatcisnikyBtn.setOnClickListener(){
            //startActivity(Intent(this@ModifyPubActivity,AddCisnikActivity::class.java))
            Intent(this,AddCisnikActivity::class.java).also {
                it.putExtra("hospoda", Id_hospoda)
                startActivity(it)
            }
        }

        //tlačítko pro obsluhu přidání piva
        addpivoBtn.setOnClickListener(){
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

        //tlačítko pro obsluhu upravení hospody
        updateHospodaBtn.setOnClickListener(){
            //upravení modelu
            hospoda.nazev = jmeno_in.text.toString()
            hospoda.poloha = poloha_in.text.toString()
            hospoda.pocet_stolu = stoly_in.text.toString().toInt()

            //upravení otvírací doby
            if(po_checked_in.isChecked){
                hospoda.po_od = po_od_in.text.toString()
                hospoda.po_do = po_do_in.text.toString()
            }else{
                hospoda.po_od = ""
                hospoda.po_do = ""
            }
            if(ut_checked_in.isChecked){
                hospoda.ut_od = ut_od_in.text.toString()
                hospoda.ut_do = ut_do_in.text.toString()
            }else{
                hospoda.ut_od = ""
                hospoda.ut_do = ""
            }
            if(st_checked_in.isChecked){
                hospoda.st_od = st_od_in.text.toString()
                hospoda.st_do = st_do_in.text.toString()
            }else{
                hospoda.st_od = ""
                hospoda.st_do = ""
            }
            if(ct_checked_in.isChecked){
                hospoda.ct_od = ct_od_in.text.toString()
                hospoda.ct_do = ct_do_in.text.toString()
            }else{
                hospoda.ct_od = ""
                hospoda.ct_do = ""
            }
            if(pa_checked_in.isChecked){
                hospoda.pa_od = pa_od_in.text.toString()
                hospoda.pa_do = pa_do_in.text.toString()
            }else{
                hospoda.pa_od = ""
                hospoda.pa_do = ""
            }
            if(so_checked_in.isChecked){
                hospoda.so_od = so_od_in.text.toString()
                hospoda.so_do = so_do_in.text.toString()
            }else{
                hospoda.so_od = ""
                hospoda.so_do = ""
            }
            if(ne_checked_in.isChecked){
                hospoda.ne_od = ne_od_in.text.toString()
                hospoda.ne_do = ne_do_in.text.toString()
            }else{
                hospoda.ne_od = ""
                hospoda.ne_do = ""
            }
            GlobalScope.launch {
                appDatabase.getHospodaDao().updateHospoda(hospoda)
                appDatabase.getPivoDao().deletePivoByHospodaID(Id_hospoda)
                withContext(Dispatchers.IO){
                    piva.forEach{
                        appDatabase.getPivoDao().insertPivo(PivoModel(null,it.nazev,it.cena,Id_hospoda, true))
                    }
                }
            }
            startActivity(Intent(this@ModifyPubActivity,ProfilActivity::class.java))
        }

        //tlačítko pro obsluhu menu
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this@ModifyPubActivity, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this@ModifyPubActivity, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }
        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
    }

    //funkce pro předvyplnění polí viewu daty z databáze
    @SuppressLint("NotifyDataSetChanged")
    private fun downloadData() {
        GlobalScope.launch(Dispatchers.IO) {
            hospoda = appDatabase.getHospodaDao().getHospodaByID(Id_hospoda)
            piva_database = appDatabase.getPivoDao().getPivaByHospodaID(Id_hospoda)
            withContext(Dispatchers.Main){
                piva_database.forEach{
                    piva.add(Pivo(it.nazev,it.cena))

                }

                jmeno_in.setText(hospoda.nazev)
                stoly_in.setText(hospoda.pocet_stolu.toString())
                poloha_in.setText(hospoda.poloha)
                po_od_in.setText(hospoda.po_od)
                po_do_in.setText(hospoda.po_do)
                ut_od_in.setText(hospoda.ut_od)
                ut_do_in.setText(hospoda.ut_do)
                st_od_in.setText(hospoda.st_od)
                st_do_in.setText(hospoda.st_do)
                ct_od_in.setText(hospoda.ct_od)
                ct_do_in.setText(hospoda.ct_do)
                pa_od_in.setText(hospoda.pa_od)
                pa_do_in.setText(hospoda.pa_do)
                so_od_in.setText(hospoda.so_od)
                so_do_in.setText(hospoda.so_do)
                ne_od_in.setText(hospoda.ne_od)
                ne_do_in.setText(hospoda.ne_do)

                if (hospoda.po_od != "" || hospoda.po_do!= ""){
                    po_checked_in.isChecked = true
                }

                if (hospoda.ut_od != "" || hospoda.ut_do!= ""){
                    ut_checked_in.isChecked = true
                }

                if (hospoda.st_od != "" || hospoda.st_do!= ""){
                    st_checked_in.isChecked = true
                }

                if (hospoda.st_od != "" || hospoda.st_do!= ""){
                    st_checked_in.isChecked = true
                }

                if (hospoda.ct_od != "" || hospoda.ct_do!= ""){
                    ct_checked_in.isChecked = true
                }

                if (hospoda.pa_od != "" || hospoda.pa_do!= ""){
                    pa_checked_in.isChecked = true
                }

                if (hospoda.so_od != "" || hospoda.so_do!= ""){
                    so_checked_in.isChecked = true
                }

                if (hospoda.ne_od != "" || hospoda.ne_do!= ""){
                    ne_checked_in.isChecked = true
                }
                pivoAdapter.notifyDataSetChanged()
            }
        }
    }
}