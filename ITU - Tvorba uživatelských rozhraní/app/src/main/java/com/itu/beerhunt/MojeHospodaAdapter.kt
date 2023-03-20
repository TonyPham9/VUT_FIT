/**
 * @author Radek Šerejch, xserej00
 */
package com.itu.beerhunt

import android.content.Context
import android.content.Intent
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.CheckBox
import android.widget.ImageButton
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView

class MojeHospodaAdapter(private val hospody: ArrayList<Hospody>, var c: Context) : RecyclerView.Adapter<MojeHospodaAdapter.MojeHospodaHolder>() {

    class MojeHospodaHolder(view: View) : RecyclerView.ViewHolder(view){
        //proměnné pro veiw prvky
        val pub_name: TextView = view.findViewById(R.id.my_pub_name)
        val pub_image: ImageButton = view.findViewById(R.id.my_image_button_pub)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): MojeHospodaHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.moje_hospoda_layout,parent,false)
        return MojeHospodaHolder(view)
    }

    override fun onBindViewHolder(holder: MojeHospodaHolder, position: Int) {
        val mojeHospoda : Hospody = hospody[position]
        //obsluha jednotlivých prvků recycler viewu
        holder.pub_name.text = mojeHospoda.pub_name
        holder.pub_image.setOnClickListener {
            Intent(c,ModifyPubActivity::class.java).also {
                it.putExtra("hospoda", mojeHospoda.hospoda_id)
                c.startActivity(it)
            }
        }
    }

    override fun getItemCount(): Int {
        return hospody.size
    }

}