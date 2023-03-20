/**
 * @author Vít Janeček, xjanec30
 */

package com.itu.beerhunt

import android.app.Application

/**
 * Třída pro uložení globální proměné s ID přihlášeného uživatele a jeho hesla
 */
class GlobalClass : Application() {
    companion object{
        var globalUserId : Int = 0
        var globalUserPWD : String = ""
        var globalFiltrMaxCena : String = "%"
        var globalFiltrMista : String = "%"
        var globalFiltrPivo : String = "%"
    }
}