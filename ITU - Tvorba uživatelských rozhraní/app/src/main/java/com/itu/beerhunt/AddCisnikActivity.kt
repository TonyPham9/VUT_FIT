/**
 * @author Vít Janeček, xjanec30
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
import com.google.android.material.textfield.TextInputEditText
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.UserModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

/**
 * Controller pro přidávání a oddebírání číšníků pro hospodu
 */
class AddCisnikActivity : AppCompatActivity() {
    private var Id_cisnik: Long = 0
    private var Id_hospoda: Int = 0
    private lateinit var appDatabase: AppDatabase
    private lateinit var cisnici : List<UserModel>
    private lateinit var user_name : List<UserModel>
    private lateinit var cisnik : ArrayList<Cisnik>
    private lateinit var cisnikAdapter: CisnikAdapter
    private lateinit var linearLayoutManager: LinearLayoutManager

    @SuppressLint("NotifyDataSetChanged")
    //Při vytvoření
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_add_cisnik)

        appDatabase = AppDatabase.getDatabase(this)
        //příprava pro recycle view
        cisnik = arrayListOf()
        cisnikAdapter = CisnikAdapter(cisnik, this@AddCisnikActivity)
        linearLayoutManager = LinearLayoutManager(this)
        val recyclerview = findViewById<RecyclerView>(R.id.cisnik_recycler)
        //přenesený atribut
        Id_hospoda = intent.getIntExtra("hospoda",0)

        recyclerview.apply {
            adapter = cisnikAdapter
            layoutManager = linearLayoutManager
        }

        getCisnici() //získání číšníků

        val backAddCisnikBtn = findViewById<Button>(R.id.back_button_add_cisnik)
        val addNewCisnikBtn = findViewById<Button>(R.id.button_add_new_cisnik)

        //akce pro buttony
        backAddCisnikBtn.setOnClickListener {
            finish()
        }

        addNewCisnikBtn.setOnClickListener {
            addCisnik()
        }

        //menu handle
        val mOnNavigationItemSelectedListener =
            BottomNavigationView.OnNavigationItemSelectedListener { item ->
                when (item.itemId) {
                    R.id.profil -> {
                        startActivity(Intent(this, ProfilActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                    R.id.home -> {
                        startActivity(Intent(this, MainActivity::class.java))
                        return@OnNavigationItemSelectedListener true
                    }
                }
                false
            }
        val nav = findViewById<BottomNavigationView>(R.id.nav)
        nav.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener)
    }

    /**
     * Metoda pro získání všech číšníků z DB pro dannou hospodu
     */
    @SuppressLint("NotifyDataSetChanged")
    private fun getCisnici() {
        GlobalScope.launch {
            cisnici = appDatabase.getUserDao().findCisnik(Id_hospoda) //list modelů z Db
            withContext(Dispatchers.Main){
                cisnici.forEach{
                    val nullableInt : Int? = it.ID_user
                    val nonNullableInt : Int = nullableInt!!
                    cisnik.add(Cisnik(it.username.toString(),nonNullableInt)) //každý přidat do arrayListu pro zobrazení
                }
                cisnikAdapter.notifyDataSetChanged() //aktualizace
            }
        }
    }

    /**
     * Metoda pro přidání číšníka
     */
    @SuppressLint("NotifyDataSetChanged")
    private fun addCisnik() {
        //inputy
        val input_username = findViewById<TextInputEditText>(R.id.input_cisnik_jmeno)
        val input_password = findViewById<TextInputEditText>(R.id.input_cisnik_heslo)

        val username = input_username.text.toString()
        val password = input_password.text.toString()

        if (username.isEmpty()) {
            input_username.error = "Zadejte jméno"
        }else if (password.isEmpty()) {
            input_password.error = "Zadejte heslo"
        }else{
            //pokud jsou vyplněné
            GlobalScope.launch {
                user_name = appDatabase.getUserDao().findUserByUsername(username)
                val cisnikModel = UserModel(null,username,"",password,null, Id_hospoda) //list modelů z Db
                withContext(Dispatchers.IO){
                    if(user_name.isEmpty()){ //ochrana před duplicitidou jmen
                        Id_cisnik = appDatabase.getUserDao().insertUser(cisnikModel)
                        cisnik.add(Cisnik(username,Id_cisnik.toInt()))
                    }
                }
                withContext(Dispatchers.Main){
                    if (user_name.isNotEmpty()){
                        input_username.error="Jméno existuje"
                    }
                }
                runOnUiThread(){
                    cisnikAdapter.updateData(cisnik) //aktualizace zobrazení
                }
            }
        }
    }
}