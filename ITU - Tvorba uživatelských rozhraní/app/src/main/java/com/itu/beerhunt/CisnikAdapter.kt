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
 * Třída pro adapter, který připravý zobrazení pro recycler view s číšníky
 */
class CisnikAdapter(private var cisnik: ArrayList<Cisnik>, var c: Context) : RecyclerView.Adapter<CisnikAdapter.CisnikHolder>() {
    private lateinit var appDatabase: AppDatabase

    //jednotlivý číšník
    class CisnikHolder(view: View) : RecyclerView.ViewHolder(view){
        val cisnik_name: TextView = view.findViewById(R.id.jmeno_cisnika)
        val cisnik_button: Button = view.findViewById(R.id.remove_cisnik_button)
    }

    //vytvoření číšníka
    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): CisnikHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.cisnik_hospoda_layout,parent,false)
        return CisnikHolder(view)
    }

    //nahrání dat a při klinutí na button
    @SuppressLint("NotifyDataSetChanged")
    override fun onBindViewHolder(holder: CisnikHolder, position: Int) {
        val jedenCisnik : Cisnik = cisnik[position]
        appDatabase = AppDatabase.getDatabase(c)

        holder.cisnik_name.text = jedenCisnik.cisnik_name
        //button pro odebrání číšníka
        holder.cisnik_button.setOnClickListener {
            GlobalScope.launch {
                appDatabase.getUserDao().deleteCisnik(jedenCisnik.Id_cisnik) //delete z Db
                withContext(Dispatchers.Main){
                    cisnik.removeAt(position)
                    notifyDataSetChanged() //aktualizace
                }
            }
        }
    }

    override fun getItemCount(): Int {
        return cisnik.size
    }

    //při změně počtu číšníků
    @SuppressLint("NotifyDataSetChanged")
    fun updateData(cisnik: ArrayList<Cisnik>){
        this.cisnik = cisnik
        notifyDataSetChanged()
    }
}