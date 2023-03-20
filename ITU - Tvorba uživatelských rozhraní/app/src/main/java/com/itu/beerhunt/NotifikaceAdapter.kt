/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.recyclerview.widget.RecyclerView
import com.itu.beerhunt.Database.AppDatabase
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

/**
 * Třída pro adapter, který připravý zobrazení pro recycler view s notifikacemi
 */
class NotifikaceAdapter(private val notifikace: ArrayList<Notifikace>, var c:Context) : RecyclerView.Adapter<NotifikaceAdapter.NotifikaceHolder>(){

    private lateinit var appDatabase: AppDatabase

    //jednotlivá notifikace
    class NotifikaceHolder(view: View) : RecyclerView.ViewHolder(view) {
        val hospoda_name : TextView = view.findViewById(R.id.text_nazev_notifikace)
        val aktualita : TextView = view.findViewById(R.id.text_popis_notifikace)
        val date : TextView = view.findViewById(R.id.text_datum_notifikace)

    }

    //vytvoření notifikace
    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): NotifikaceHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.notifikace_layout, parent,false)
        return NotifikaceHolder(view)
    }

    //nahrání dat
    @SuppressLint("SetTextI18n")
    override fun onBindViewHolder(holder: NotifikaceHolder, position: Int) {
        val jednaNotifikace: Notifikace = notifikace[position]
        appDatabase = AppDatabase.getDatabase(c)
        //získání názvu hospody
        GlobalScope.launch {
            val hospoda = appDatabase.getHospodaDao().findHospodaById(jednaNotifikace.Id_hospoda)
            withContext(Dispatchers.Main){
                holder.hospoda_name.text = hospoda[0].nazev.toString()
            }
        }
        holder.aktualita.text = jednaNotifikace.aktualita
        holder.date.text = jednaNotifikace.datum
    }
    override fun getItemCount(): Int {
        return notifikace.size
    }
}