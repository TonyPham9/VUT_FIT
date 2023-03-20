/**
 * @author Tony Pham, xphamt00
 */

package com.itu.beerhunt

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle

class  MainActivity : AppCompatActivity() {

    //Když je vytvořena tato aktivita tak se provede tato funkce
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        //startnu aktivitu FilteredActivity
        startActivity(Intent(this@MainActivity, FilteredActivity::class.java))
    }
}