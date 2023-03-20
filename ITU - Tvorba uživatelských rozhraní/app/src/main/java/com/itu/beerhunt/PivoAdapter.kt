/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt

import android.annotation.SuppressLint
import android.app.AlertDialog
import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class PivoAdapter(private val piva:ArrayList<Pivo>, val c:Context) : RecyclerView.Adapter<PivoAdapter.PivoHolder>() {
    @SuppressLint("NotifyDataSetChanged")
    inner class PivoHolder(view:View) : RecyclerView.ViewHolder(view){
        //proměnné pro veiw prvky
        val jmeno : TextView = view.findViewById(R.id.jmenopiva)
        val cena : TextView = view.findViewById(R.id.cenapiva)
        init {
            //obsluha odstranění piva
            val deleteBtn = view.findViewById<Button>(R.id.remove_beer_button)
            deleteBtn.setOnClickListener(){
                AlertDialog.Builder(view.context)
                    .setTitle("odstranit pivo")
                    .setMessage("opravdu chcete toto pivo odstranit?")
                    .setPositiveButton("Ano"){
                        dialog,_->
                        piva.removeAt(adapterPosition)
                        notifyDataSetChanged()
                        dialog.dismiss()
                    }
                    .setNegativeButton("Ne"){
                        dialog,_->
                        dialog.dismiss()
                    }.create().show()
            }
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PivoHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.pivo_cena_layout, parent, false)
        return PivoHolder(view)
    }

    override fun onBindViewHolder(holder: PivoHolder, position: Int) {
        val pivo : Pivo = piva[position]
        //obsluha jednotlivých prvků recycler viewu
        holder.cena.text = "%.1f kč".format(pivo.cena)
        holder.jmeno.text = pivo.nazev
    }

    override fun getItemCount(): Int {
        return piva.size
    }

}