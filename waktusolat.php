<?php

//Waktu Solat//

/*
Plugin Name: Waktu Solat Malaysia / Malaysia Prayer Times
Plugin URI: http://www.kurt-network.net
Description: Display Malaysia Prayer Times.
Version: 1.0
Author: Muhammad Hafiz Bin Harun @ kurt_penang
Author URI: http://www.kurt-network.net
*/
/* 
Please refer to the readme.txt for installation instructions. This release is deemed stable, please contact us if you find any errors or bugs.
*/
/*  Copyright 2008  Muhammad Hafiz Bin Harun @ kurt_penang  (email : kurt_penang@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function display_prayertimes(){
?>
<html>
<head>
<script language=javascript type='text/javascript'>
function hidediv() {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById('hideshow').style.visibility = 'hidden';
}
else {
if (document.layers) { // Netscape 4
document.hideshow.visibility = 'hidden';
}
else { // IE 4
document.all.hideshow.style.visibility = 'hidden';
}
}
}

function showdiv() {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById('hideshow').style.visibility = 'visible';
}
else {
if (document.layers) { // Netscape 4
document.hideshow.visibility = 'visible';
}
else { // IE 4
document.all.hideshow.style.visibility = 'visible';
}
}
}

</script>
<script type="text/javascript">
var xmlHttp

function showNegeri(str)
{ 
xmlHttp=GetXmlHttpObject()
if (xmlHttp==null)
 {
 alert ("Browser does not support HTTP Request")
 return
 }
var url="<?php echo "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF'])."/wp-content/plugins/wp-prayertimes/waktusolatget.php";?>"

url=url+"?negeri="+str
xmlHttp.onreadystatechange=stateChanged 
xmlHttp.open("GET",url,true)
xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 document.getElementById("txtHint").innerHTML=xmlHttp.responseText 
 } 
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 //Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}
</SCRIPT>

<style type="text/css">
#hideshow {
    position:absolute;
	visibility: hidden;
    width:550px;height:50px;left:0px;top:0px;
    padding:16px;background:#FFFFFF;
    border:2px solid #2266AA;

}

#close {
	float: right;
}

</style>
</head>
<body onLoad="javascript:hidediv()">
<center>
<SCRIPT language=JavaScript class=ver10>
	var fixd;

	function isGregLeapYear(year)
	{
		return year%4 == 0 && year%100 != 0 || year%400 == 0;
	}

	function gregToFixed(year, month, day)
	{
		var a = Math.floor((year - 1) / 4);
		var b = Math.floor((year - 1) / 100);
		var c = Math.floor((year - 1) / 400);
		var d = Math.floor((367 * month - 362) / 12);

		if (month <= 2)
			e = 0;
		else if (month > 2 && isGregLeapYear(year))
			e = -1;
		else
			e = -2;

		return 1 - 1 + 365 * (year - 1) + a - b + c + d + e + day;
	}

	function Hijri(year, month, day)
	{
		this.year = year;
		this.month = month;
		this.day = day;
		this.toFixed = hijriToFixed;
		this.toString = hijriToString;
	}

	function hijriToFixed()
	{
		return this.day + Math.ceil(29.5 * (this.month - 1)) + (this.year - 1) * 354 +
 			Math.floor((3 + 11 * this.year) / 30) + 227015 - 1;
	}

	function hijriToString()
	{
		var months = new Array("Muharam","Safar","Rabiul Awal","Rabiul Akhir","Jamadil Awal","Jamadil Akhir","Rejab","Sya'ban","Ramadan","Syawal","Zulka'edah","Zulhijjah");
  	return this.day + " " + months[this.month - 1]+ " " + this.year;
	}

	function fixedToHijri(f)
	{
  	var i=new Hijri(1100, 1, 1);
   	i.year = Math.floor((30 * (f - 227015) + 10646) / 10631);
   	var i2=new Hijri(i.year, 1, 1);
   	var m = Math.ceil((f - 29 - i2.toFixed()) / 29.5) + 1;
   	i.month = Math.min(m, 12);
   	i2.year = i.year;
	 	i2.month = i.month;
	 	i2.day = 1;
   	i.day = f - i2.toFixed() + 1;
   	return i;
	}

	var tod=new Date();
	var weekday=new Array("Ahad","Isnin","Selasa","Rabu","Khamis","Jumaat","Sabtu");
	var monthname=new Array("Jan","Feb","March","April","Mei","Jun","Julai","Ogos","Sept","Okt","Nov","Dis");

	var y = tod.getFullYear();
	var m = tod.getMonth();
	var d = tod.getDate();
	var dow = tod.getDay();

	document.write(weekday[dow] + " " + d + " " + monthname[m] + " " + y);

	m++;
	fixd=gregToFixed(y, m, d);
	var h=new Hijri(1421, 11, 28);
	h = fixedToHijri(fixd);
	document.write("<br> " + h.toString() + " H");

</SCRIPT>

<p align="center">
<div id="txtHint"><script type="text/javascript"> showNegeri("3.13333;101.7"); </script></div>
</p>


<a href="javascript:showdiv()"> [ Tukar Kawasan ]</a>

<div id="hideshow" >
<span id="close"><a href="javascript:hidediv()">Tutup</a></span>
<form >
<select name="negeri" id="negeri" onChange="showNegeri(this.value)">
<option value="3.13333;101.7">Pilih Kawasan</option>
<option value="2.448124;104.521763">Johor Zon 1 - Pulau Aur </option>
<option value="1.727473;103.903000">Johor Zon 2 - Kota Tinggi, Mersing, Johor Bahru </option>
<option value="6.036246;100.404160">Kedah Zon 1 - Kubang Pasu, Kota Setar, Pendang, Kulim </option>
<option value="5.676914;100.916397">Kedah Zon 2 - Baling, Sik, Padang Terap </option>
<option value="5.399612;102.006342">Kelantan</option>
<option value="3.13333;101.7">Kuala Lumpur & Putrajaya</option>
<option value="6.342418;99.731639">Langkawi</option>
<option value="5.300708;115.250799">Labuan</option>
<option value="3.823055;103.342903">Pahang Zon Timur - Kuantan, Pekan, Rompin, Muadzam Shah </option>
<option value="3.549227;102.781593">Pahang Zon Tengah - Maran, Chenor, Temerloh, Jerantut </option>
<option value="4.184257;102.041901">Pahang Zon Barat - Bentong, Kuala Lipis </option>
<option value="4.775050;100.951797">Perak Zon 1 - Hulu Perak, Kuala kangsar, Kinta, Batang Padang </option>
<option value="5.216513;100.697899">Perak Zon 2 - Kerian, Larut Matang, Selama </option>
<option value="6.442580;100.010875">Perlis</option>
<option value="5.346092;100.368683">Pulau Pinang</option>
<option value="2.296232;102.289136">Melaka</option>
<option value="2.812324;102.364304">N. Sembilan Zon 1 - Jempol, Tampin </option>
<option value="2.707500;101.949608">N. Sembilan Zon 2 - Port Dickson, Seremban, K. Pilah, Jelebu, Rembau </option>
<option value="5.841166;118.112900">Sabah Zon 1 - Sandakan, Bkt Garam, Semawang, Temanggong, Tambisan </option>
<option value="5.892304;117.557999">Sabah Zon 2 - Pinangah, Terusun, Beluran, Kuamut, Telupid </option>
<option value="5.029797;118.338097">Sabah Zon 3 - Lahad Datu, Kunak, Silabukan, Tungku, Sahabat </option>
<option value="4.249592;117.881500">Sabah Zon 4 - Tawau, Balong, Merotai, kalabakan </option>
<option value="6.897037;116.842003">Sabah Zon 5 - Kudat, Kota Maruda, Pitas </option>
<option value="5.972669;116.070503">Sabah Zon 7 - Kota Kinabalu, Penampang, Tuaran, Papar, Kota Belud </option>
<option value="5.341894;116.156799">Sabah Zon 8 - Keningau, Tambunan, Nabawan </option>
<option value="5.085158;115.557098">Sabah Zon 9 - Sipitang, Membakut, Beafout, Kuala Penyu </option>
<option value="4.759246;115.00.6599">Sarawak Zon 1 - Limbang, Sundar, Trusun, Lawas </option>
<option value="3.868817;113.716797">Sarawak Zon 2 - Niah, Belaga, Miri, Bekunu, Marudi </option>
<option value="2.005366;112.542603">Sarawak Zon 3 - Song, Belingan Sebauh, Bintulu, Tatau </option>
<option value="2.098989;112.147202">Sarawak Zon 4 - Kanowit, Sibu, Dalat, Oya </option>
<option value="2.233410;111.211967">Sarawak Zon 5 - Belawai, Matu, Daro, Sarikei, Julau </option>
<option value="1.809669;111.111397">Sarawak Zon 6 - Kabong, Lingga, Sri Aman, Engkelili, Betong </option>
<option value="1.393423;110.743698">Sarawak Zon 7 - Samarahan, Simunjan, Serian, Sebuyau, Meludam </option>
<option value="1.549073;110.344200">Sarawak Zon 8 - Kuching, Bau, Lundu, Semantan </option>
<option value="3.090608;101.529597">Selangor Zon 1 - H.Selangor, Rawang, H.Langat, Sepang, PJ, Shah Alam </option>
<option value="3.746830;100.961750">Selangor Zon 2 - Sabak Bernam, Kuala Selangor, Klang, Kuala Langat </option>
<option value="5.205378;103.201599">Terengganu Zon 1 - Kuala Terengganu, Marang </option>
<option value="5.823537;102.553398">Terengganu Zon 2 - Besut, Setiu </option>
<option value="4.763042;103.417198">Terengganu Zon 4 - Dungun, Kemaman </option>
</select>
</form>

</div>
<script type="text/javascript"><!--
/* Script by: www.jtricks.com
 * Version: 20071210
 * Latest version:
 * www.jtricks.com/javascript/navigation/floating.html
 */
var floatingMenuId = 'hideshow';
var floatingMenu =
{
    targetX: 10,
    targetY: 10,

    hasInner: typeof(window.innerWidth) == 'number',
    hasElement: document.documentElement
        && document.documentElement.clientWidth,

    menu:
        document.getElementById
        ? document.getElementById(floatingMenuId)
        : document.all
          ? document.all[floatingMenuId]
          : document.layers[floatingMenuId]
};

floatingMenu.move = function ()
{
    if (document.layers)
    {
        floatingMenu.menu.left = floatingMenu.nextX;
        floatingMenu.menu.top = floatingMenu.nextY;
    }
    else
    {
        floatingMenu.menu.style.left = floatingMenu.nextX + 'px';
        floatingMenu.menu.style.top = floatingMenu.nextY + 'px';
    }
}

floatingMenu.computeShifts = function ()
{
    var de = document.documentElement;

    floatingMenu.shiftX =
        floatingMenu.hasInner
        ? pageXOffset
        : floatingMenu.hasElement
          ? de.scrollLeft
          : document.body.scrollLeft;
    if (floatingMenu.targetX < 0)
    {
        if (floatingMenu.hasElement && floatingMenu.hasInner)
        {
            // Handle Opera 8 problems
            floatingMenu.shiftX +=
                de.clientWidth > window.innerWidth
                ? window.innerWidth
                : de.clientWidth
        }
        else
        {
            floatingMenu.shiftX +=
                floatingMenu.hasElement
                ? de.clientWidth
                : floatingMenu.hasInner
                  ? window.innerWidth
                  : document.body.clientWidth;
        }
    }

    floatingMenu.shiftY = 
        floatingMenu.hasInner
        ? pageYOffset
        : floatingMenu.hasElement
          ? de.scrollTop
          : document.body.scrollTop;
    if (floatingMenu.targetY < 0)
    {
        if (floatingMenu.hasElement && floatingMenu.hasInner)
        {
            // Handle Opera 8 problems
            floatingMenu.shiftY +=
                de.clientHeight > window.innerHeight
                ? window.innerHeight
                : de.clientHeight
        }
        else
        {
            floatingMenu.shiftY +=
                floatingMenu.hasElement
                ? document.documentElement.clientHeight
                : floatingMenu.hasInner
                  ? window.innerHeight
                  : document.body.clientHeight;
        }
    }
}

floatingMenu.doFloat = function()
{
    var stepX, stepY;

    floatingMenu.computeShifts();

    stepX = (floatingMenu.shiftX + 
        floatingMenu.targetX - floatingMenu.nextX) * .07;
    if (Math.abs(stepX) < .5)
    {
        stepX = floatingMenu.shiftX +
            floatingMenu.targetX - floatingMenu.nextX;
    }

    stepY = (floatingMenu.shiftY + 
        floatingMenu.targetY - floatingMenu.nextY) * .07;
    if (Math.abs(stepY) < .5)
    {
        stepY = floatingMenu.shiftY + 
            floatingMenu.targetY - floatingMenu.nextY;
    }

    if (Math.abs(stepX) > 0 ||
        Math.abs(stepY) > 0)
    {
        floatingMenu.nextX += stepX;
        floatingMenu.nextY += stepY;
        floatingMenu.move();
    }

    setTimeout('floatingMenu.doFloat()', 20);
};

// addEvent designed by Aaron Moore
floatingMenu.addEvent = function(element, listener, handler)
{
    if(typeof element[listener] != 'function' || 
       typeof element[listener + '_num'] == 'undefined')
    {
        element[listener + '_num'] = 0;
        if (typeof element[listener] == 'function')
        {
            element[listener + 0] = element[listener];
            element[listener + '_num']++;
        }
        element[listener] = function(e)
        {
            var r = true;
            e = (e) ? e : window.event;
            for(var i = element[listener + '_num'] -1; i >= 0; i--)
            {
                if(element[listener + i](e) == false)
                    r = false;
            }
            return r;
        }
    }

    //if handler is not already stored, assign it
    for(var i = 0; i < element[listener + '_num']; i++)
        if(element[listener + i] == handler)
            return;
    element[listener + element[listener + '_num']] = handler;
    element[listener + '_num']++;
};

floatingMenu.init = function()
{
    floatingMenu.initSecondary();
    floatingMenu.doFloat();
};

// Some browsers init scrollbars only after
// full document load.
floatingMenu.initSecondary = function()
{
    floatingMenu.computeShifts();
    floatingMenu.nextX = floatingMenu.shiftX +
        floatingMenu.targetX;
    floatingMenu.nextY = floatingMenu.shiftY +
        floatingMenu.targetY;
    floatingMenu.move();
}

if (document.layers)
    floatingMenu.addEvent(window, 'onload', floatingMenu.init);
else
{
    floatingMenu.init();
    floatingMenu.addEvent(window, 'onload',
        floatingMenu.initSecondary);
}

//--></script>

</center></body></html>

<?php 
}
?>