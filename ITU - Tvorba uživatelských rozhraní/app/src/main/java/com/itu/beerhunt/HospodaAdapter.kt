/**
 * @author Tony Pham, xphamt00
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
import androidx.core.view.isVisible
import androidx.recyclerview.widget.RecyclerView
import com.itu.beerhunt.Database.AppDatabase
import com.itu.beerhunt.Models.FavoriteModel
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch


class HospodaAdapter(private val hospody: ArrayList<Hospody>, var mContext: Context) :
    RecyclerView.Adapter<HospodaAdapter.HospodaHolder>() {

    class HospodaHolder(view: View) : RecyclerView.ViewHolder(view) {
        //proměnné pro veiw prvky
        val pub_name: TextView = view.findViewById(R.id.pub_name)
        val pub_image: ImageButton = view.findViewById(R.id.image_button_pub)
        val favorite: CheckBox = view.findViewById(R.id.favorite)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): HospodaHolder {
        val view =
            LayoutInflater.from(parent.context).inflate(R.layout.hospoda_layout, parent, false)
        return HospodaHolder(view)


    }

    override fun onBindViewHolder(holder: HospodaHolder, position: Int) {
        //obsluha jednotlivých prvků recycler viewu
        val hospoda = hospody[position]
        var appDatabase: AppDatabase = AppDatabase.getDatabase(mContext)

        //název hospody
        holder.pub_name.text = hospoda.pub_name

        //tlačítko na detail hospody
        holder.pub_image.setOnClickListener {
            Intent(mContext, DetailActivity::class.java).also {
                it.putExtra("hospoda", hospoda.hospoda_id)
                mContext.startActivity(it)
            }

        }

        //checkbox pro přidání do oblíbených
        if (GlobalClass.globalUserId != 0) {
            GlobalScope.launch(Dispatchers.IO) {
                var favorites: List<FavoriteModel> = appDatabase.getFavoriteDao().getAllFavorite()
                favorites.forEach {
                    if (it.hospoda == hospoda.hospoda_id && it.user == GlobalClass.globalUserId) {
                        holder.favorite.isChecked = true
                    }
                }
            }
            holder.favorite.setOnCheckedChangeListener { checkBox, isChecked ->

                if (isChecked) {
                    GlobalScope.launch(Dispatchers.IO) {
                        appDatabase.getFavoriteDao().insertFavorite(
                            FavoriteModel(
                                hospoda.hospoda_id,
                                GlobalClass.globalUserId
                            )
                        )
                    }
                } else {
                    GlobalScope.launch(Dispatchers.IO) {
                        appDatabase.getFavoriteDao().deleteFavorite(
                            hospoda.hospoda_id,
                            GlobalClass.globalUserId
                        )
                    }
                }

            }
        } else {
            holder.favorite.isVisible = false
            holder.favorite.isClickable = false
        }
    }

    override fun getItemCount(): Int {
        return hospody.size
    }

}