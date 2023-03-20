/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt


import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView


class RecenzeAdapter(private val recenze: ArrayList<Recenze>) : RecyclerView.Adapter<RecenzeAdapter.RecenzeHolder>() {

    class RecenzeHolder(view: View) : RecyclerView.ViewHolder(view) {
        //proměnné pro veiw prvky
        val user_name : TextView = view.findViewById(R.id.user_name)
        val review : TextView  = view.findViewById(R.id.review)
        val date : TextView  = view.findViewById(R.id.date)

    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecenzeHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.recenze_layout, parent,false)
        return RecenzeHolder(view)
    }

    override fun onBindViewHolder(holder: RecenzeHolder, position: Int) {
        //obsluha jednotlivých prvků recycler viewu
        val user_name = recenze[position]
        holder.user_name.text = user_name.user_name
        val review = recenze[position]
        holder.review.text = review.review
        val date = recenze[position]
        holder.date.text = date.date.toString()
    }
    override fun getItemCount(): Int {
        return recenze.size
    }

}