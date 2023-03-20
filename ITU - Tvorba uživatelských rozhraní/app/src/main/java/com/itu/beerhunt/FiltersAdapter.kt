/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import android.widget.Toast
import androidx.core.widget.doOnTextChanged
import androidx.recyclerview.widget.RecyclerView
import com.google.android.material.textfield.TextInputEditText

class FiltersAdapter(private var title : List<String>, var context: Context) : RecyclerView.Adapter<FiltersAdapter.Pager2ViewHolder>() {

    inner class Pager2ViewHolder (itemView : View) : RecyclerView.ViewHolder(itemView) {
        //proměnné pro veiw prvky
        val itemTitle : TextView = itemView.findViewById(R.id.filterTitle)
        val filter :TextInputEditText = itemView.findViewById(R.id.input_filtr)
    }


    override fun getItemCount(): Int {
        return title.size
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): FiltersAdapter.Pager2ViewHolder {
        return Pager2ViewHolder(LayoutInflater.from(parent.context).inflate(R.layout.item_page,parent,false))
    }

    override fun onBindViewHolder(holder: Pager2ViewHolder, position: Int) {
        //obsluha jednotlivých prvků recycler viewu
            holder.itemTitle.text = title[position]
        if(title[position] == "Max Cena") {
            holder.filter.doOnTextChanged { text, start, before, count ->
                GlobalClass.globalFiltrMaxCena =  holder.filter.text.toString()
            }
            //Toast.makeText(context, holder.filter.doOnTextChanged().toString(), Toast.LENGTH_SHORT).show()
        } else if(title[position] == "Pivo") {
            holder.filter.doOnTextChanged { text, start, before, count ->
                GlobalClass.globalFiltrPivo =  holder.filter.text.toString()
            }
        } else if(title[position] == "Místa k sezení") {
            holder.filter.doOnTextChanged { text, start, before, count ->
                GlobalClass.globalFiltrMista =  holder.filter.text.toString()
            }
        }

    }

}