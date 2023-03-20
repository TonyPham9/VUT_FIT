/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt

import android.annotation.SuppressLint
import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.itu.beerhunt.Database.AppDatabase
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext

/**
 * Třída pro adapter, který připravý zobrazení pro recycler view s pivy hospody, ve které číšník pracuje
 */
class PivoCisnikAdapter(private var piva:ArrayList<PivoCisnik>, val c: Context) : RecyclerView.Adapter<PivoCisnikAdapter.PivoCisnikHolder>(){
    private lateinit var appDatabase: AppDatabase

    //jednotlivé pivo
    class PivoCisnikHolder(view: View) : RecyclerView.ViewHolder(view) {
        val nazev: TextView = view.findViewById(R.id.nazev_piva_cisnik)
        val dostupnostBtn: Button = view.findViewById(R.id.dostupnost_button)
    }

    //vytvoření piva
    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PivoCisnikHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.pivo_cisnik_layout, parent, false)
        return PivoCisnikHolder(view)
    }

    //nahrání dat a při klinutí na button
    @SuppressLint("SetTextI18n", "NotifyDataSetChanged")
    override fun onBindViewHolder(holder: PivoCisnikHolder, position: Int) {
        val pivo : PivoCisnik = piva[position]
        appDatabase = AppDatabase.getDatabase(c)

        holder.nazev.text = pivo.nazev
        //vizuální změna dostupnosti
        if (!pivo.dostupnost){
            holder.dostupnostBtn.text = "Dostupnost \n Ne"
        }else{
            holder.dostupnostBtn.text = "Dostupnost \n Ano"
        }

        //změna dostupnosti v databázi
        holder.dostupnostBtn.setOnClickListener {
            GlobalScope.launch {
                if (!pivo.dostupnost){
                    appDatabase.getPivoDao().updateDostupnost(pivo.Id_pivo,true) // update Db
                    withContext(Dispatchers.Main){
                        piva[position].dostupnost = true
                        notifyDataSetChanged() //aktualizace
                    }
                }else{
                    appDatabase.getPivoDao().updateDostupnost(pivo.Id_pivo,false) // update Db
                    withContext(Dispatchers.Main){
                        piva[position].dostupnost = false
                        notifyDataSetChanged() //aktualizace
                    }
                }
            }
        }
    }

    override fun getItemCount(): Int {
        return piva.size
    }
}