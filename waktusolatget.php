<?php

//Waktu Solat//

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

# prayertime calculation based on the ITL-project by
# arabeyes.org
# Thnx to Youcef Rabah Rahal
# and Thamer Mahmoud
# translated into php by Omar Abo-Namous
# contact at kontakt[at]islaminhannover.de
# don't hesitate to ask some technical questions :)
# see the calculation in action at www.islaminhannover.de
# Converted into class  from original PHP functions By Sidik Al Amini Zailani http://sidik.org/

define('hour','hour');
define('minute','minute');
define('hourmin','hourmin');
define('isExtreme','isExtreme');
define('KAABA_LAT' , 21  + 25.4 / 60);
define('KAABA_LONG', 39 + 49.4 / 60);

class solat{
	var $degreeLat ;
	var $degreeLong ;
	var $gmtDiff ;
	var $seaLevel ;
	var $dst ;
	var $fajrAng ;
	var $ishaaAng ;
	var $imsaakAng ;
	var $fajrInv ;
	var $ishaaInv;
	var $imsaakInv;
	var $mathhab ;
	var $nearestLat ;
	var $extreme ;
	var $nDay;
	var	$lastDay;
	var	$nextDay;
	var $day;
	var $month;
	var $year;
	var $temp_hour;
	var $temp_minute;
	var $hourmin;
	var $pt = array();

	function getPrayerTimes ($timestamp,$meth,$lat,$long,$gmtdiff,$seaLevel,$dst)
	{
		$this->degreeLat = $lat;
		$this->degreeLong = $long;
		$this->gmtDiff = $gmtdiff;
		$this->seaLevel = $seaLevel;
		$this->dst = $dst;
		$this->getMethod($meth);
		$this->day = date("j",$timestamp);
		$this->month = date ("n",$timestamp);
		$this->year = date ('Y',$timestamp);
		$this->getDayInfo ();
		$this->getPrayerTimesByDay();

	}

	function getPrayerTimesByDay ()
	{
		# locally store needed variables
		$lat = $this->degreeLat;
		$lon = $this->degreeLong;
		$gmt = $this->gmtDiff * 15;
		$seaLevel = $this->seaLevel;
		$fajrAng = $this->fajrAng;
		$ishaaAng = $this->ishaaAng;
		$mathhab = $this->mathhab;
		$eot = $this->timeEquation($this->nDay, $this->lastDay);
		$dec = $this->sunDeclination($this->nDay);

		# First Step: Get Prayer Times results for this day of year
		# and this location. The results are NOT the actual prayer
		# times
		$th = $this->getThuhr($lon, $gmt, $eot);
		$shMg = $this->getShoMag ($lat, $dec, $seaLevel);
		$fj = $this->getFajIsh ($lat, $dec, $fajrAng);
		$is = $this->getFajIsh ($lat, $dec, $ishaaAng);
		$ar = $this->getAssr ($lat, $dec, $mathhab);

		# Second Step A: Calculate all salat times as Base-10 numbers
		# in Normal circumstances

		# Fajr
		if ($this->fajrInv != 0) {
			$interval = $this->fajrInv / 60.0;
			$tempPrayer[0] = $th - $shMg - $interval;
		} else if ($fj == 0) {
			$tempPrayer[0] = 0;
		} else
		$tempPrayer[0] = $th - $fj;
		$tempPrayer[1] = $th - $shMg;
		$tempPrayer[2] = $th;
		$tempPrayer[3] = $th + $ar;
		$tempPrayer[4] = $th + $shMg;
		# Ishaa
		if ($is == 0)
		$tempPrayer[5] = 0;
		else {$tempPrayer[5] = $th + $is;
		# Ishaa Interval
		if ($this->ishaaInv!= 0) {
			$interval = $this->ishaaInv/ 60.0;
			$tempPrayer[5] = $th + $shMg + $interval;
		}}

		# Second Step B: Calculate all salat times as Base-10 numbers
		# in Extreme Latitudes (if set)

		# Reset status of extreme switches
		for ($i=0; $i<6; $i++)
		$pt[$i][isExtreme] = 0;

		if ($this->extreme != 0)
		{
			# Nearest Latitude (Method.nearestLat)
			if ($this->extreme <= 3)
			{
				$exFj = $this->getFajIsh($this->nearestLat, $dec, $this->fajrAng);
				$exIs = $this->getFajIsh($this->nearestLat, $dec, $this->ishaaAng);
				$exAr = $this->getAssr($this->nearestLat, $dec, $this->mathhab);
				$exShMg = $this->getShoMag($this->nearestLat, $dec, $this->seaLevel);

				switch($this->extreme)
				{
					case 1: # All salat Always: Nearest Latitude
					$tempPrayer[0] = $th - $exFj;
					$tempPrayer[1] = $th - $exShMg;
					$tempPrayer[3] = $th + $exAr;
					$tempPrayer[4] = $th + $exShMg;
					$tempPrayer[5] = $th + $exIs;
					$pt[0][isExtreme] = 1;
					$pt[1][isExtreme] = 1;
					$pt[3][isExtreme] = 1;
					$pt[4][isExtreme] = 1;
					$pt[5][isExtreme] = 1;
					break;

					case 2: # Fajr Ishaa Always: Nearest Latitude
					$tempPrayer[0] = $th - $exFj;
					$tempPrayer[5] = $th + $exIs;
					$pt[0][isExtreme] = 1;
					$pt[5][isExtreme] = 1;
					break;

					case 3: # Fajr Ishaa if invalid: Nearest Latitude
					if ($tempPrayer[0] <= 0) {
						$tempPrayer[0] = $th - $exFj;
						$pt[0][isExtreme] = 1;
					}
					if ($tempPrayer[5] <= 0) {
						$tempPrayer[5] = $th + $exIs;
						$pt[5][isExtreme] = 1;
					}
					break;
				}
			} # End: Nearest latitude


			# Nearest Good Day
			if (($this->extreme > 3) && ($this->extreme <= 5))
			{
				$nGoodDay = 0;
				# Start by getting last or next nearest Good Day
				for($i=0; $i<=$this->lastDay; $i++)
				{
					# last closest day
					$nGoodDay = $this->nDay - $i;
					$exeot = $this->timeEquation ($nGoodDay, $this->lastDay);
					$exdec = $this->sunDeclination($nGoodDay);
					$exTh = $this->getThuhr($lon, $gmt, $exeot);
					$exFj = $this->getFajIsh($lat, $exdec, $this->fajrAng);
					$exIs = $this->getFajIsh($lat, $exdec, $this->ishaaAng);
					if (($exFj > 0) && ($exIs > 0))
					break; # loop
					# Next closest day
					$nGoodDay = $this-nDay + $i;
					$exdec = $this->sunDeclination($nGoodDay);
					$exTh = $this->getThuhr($lon, $gmt, $exeot);
					$exFj = $this->getFajIsh($lat, $exdec, $this->fajrAng);
					$exIs = $this->getFajIsh($lat, $exdec, $this->ishaaAng);

					if (($exFj > 0) && ($exIs > 0))
					break;
				}

				# Get equation results for that day
				$exeot = $this->timeEquation(nGoodDay, $this->lastDay);
				$exdec = $this->sunDeclination ($nGoodDay);
				$exTh = $this->getThuhr ($lon, $gmt, $exeot);
				$exFj = $this->getFajIsh ($lat, $exdec, $this->fajrAng);
				$exIs = $this->getFajIsh ($lat, $exdec, $this->ishaaAng);
				$exShMg = $this->getShoMag ($lat, $exdec, $this->seaLevel);
				$exAr = $this->getAssr ($lat, $exdec, $this->mathhab);

				switch($this->extreme)
				{
					case 4: # All salat Always: Nearest Day
					$tempPrayer[0] = $exTh - $exFj;
					$tempPrayer[1] = $exTh - $exShMg;
					$tempPrayer[2] = $exTh;
					$tempPrayer[3] = $exTh + $exAr;
					$tempPrayer[4] = $exTh + $exShMg;
					$tempPrayer[5] = $exTh + $exIs;
					for ($i=0; $i<6; $i++)
					$pt[$i][isExtreme] = 1;
					break;
					case 5: # Fajr Ishaa if invalid:: Nearest Day
					if ($tempPrayer[0] <= 0) {
						$tempPrayer[0] = $exTh - $exFj;
						$pt[0][isExtreme] = 1;
					}
					if ($tempPrayer[5] <= 0) {
						$tempPrayer[5] = $exTh + $exIs;
						$pt[5][isExtreme] = 1;
					}
					break;

				} # end switch


			} # end nearest day

			# 1/7th of Night
			if ($this->extreme == 6 || $this->extreme == 7)
			{
				$allInterval = 24 - ($th - $shMg);
				$allInterval = $allInterval + (12 - ($th + $shMg));

				switch($this->extreme)
				{
					case 6: # Fajr Ishaa Always: 1/7th of Night
					$tempPrayer[0] = ($th - $shMg) - ((1/7.0) * $allInterval);
					$pt[0][isExtreme] = 1;
					$tempPrayer[5] = ((1/7.0) * $allInterval) + ($th + $shMg);
					$pt[5][isExtreme] = 1;
					break;

					case 7: # Fajr Ishaa if invalid: 1/7th of Night
					if ($tempPrayer[0] <= 0) {
						$tempPrayer[0] = ($th - $shMg) - ((1/7.0) * $allInterval);
						$pt[0][isExtreme] = 1;
					}
					if ($tempPrayer[5] <= 0) {
						$tempPrayer[5] = ((1/7.0) * $allInterval) + ($th + $shMg);
						$pt[5][isExtreme] = 1;
					}
					break;
				}
			} # end 1/7th of Night

			# n Minutes from Shorooq Maghrib
			if ($this->extreme == 8 || $this->extreme == 9)
			{
				switch($this->extreme)
				{
					case 8: # Minutes from Shorooq/Maghrib Always
					$tempPrayer[0] = $th - $shMg;
					$pt[0][isExtreme] = 1;
					$tempPrayer[5] = $th + $shMg;
					$pt[5][isExtreme] = 1;
					break;

					case 9: # Minutes from Shorooq/Maghrib if invalid
					if ($tempPrayer[0] <= 0) {
						$tempPrayer[0] = $th - $shMg;
						$pt[0][isExtreme] = 1;
					}
					if ($tempPrayer[0] <= 0) {
						$tempPrayer[5] = $th + $shMg;
						$pt[5][isExtreme] = 1;
					}
					break;

				} # end switch
			} # end n Minutes

		} # End Extreme


		# Third and Final Step: Fill the Prayer array and do decimal
		# to minutes conversion
		for ($i=0; $i<6; $i++) {
			$this->base6hm($tempPrayer[$i],$this->dst);
			$pt[$i][hour]=$this->temp_hour;
			$pt[$i][minute]=$this->temp_minute;
			$pt[$i][hourmin]=$this->hourmin;
		}
		$this->pt=