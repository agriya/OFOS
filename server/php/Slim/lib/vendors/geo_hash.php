<?php
/**
 * Geohash generation class
 * http://blog.dixo.net/downloads/
 *
 * This file copyright (C) 2008 Paul Dixon (paul@elphin.com)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */



/**
* Encode and decode geohashes
*
*/
class Geohash
{
	private $coding="0123456789bcdefghjkmnpqrstuvwxyz";
	private $codingMap=array();
	private static $base32 = '0123456789bcdefghjkmnpqrstuvwxyz';
	
	private static $neighbors = array(
	'odd' => array('bottom' => '238967debc01fg45kmstqrwxuvhjyznp',
				 'top' => 'bc01fg45238967deuvhjyznpkmstqrwx',
				 'left' => '14365h7k9dcfesgujnmqp0r2twvyx8zb',
				 'right' => 'p0r21436x8zb9dcf5h7kjnmqesgutwvy'),
	'even' => array('right' => 'bc01fg45238967deuvhjyznpkmstqrwx',
				  'left' => '238967debc01fg45kmstqrwxuvhjyznp',
				  'top' => 'p0r21436x8zb9dcf5h7kjnmqesgutwvy',
				  'bottom' => '14365h7k9dcfesgujnmqp0r2twvyx8zb'));
	
	private static $borders = array(
	'odd' => array('bottom' => '0145hjnp', 'top' => 'bcfguvyz',
				 'left' => '028b', 'right' => 'prxz'),
	'even' => array('right' => 'bcfguvyz', 'left' => '0145hjnp',
				  'top' => 'prxz', 'bottom' => '028b'));
	
	private static $bits = array(16, 8, 4, 2, 1);
	private static $latRange = array(-90.0, 90.0);
	private static $lngRange = array(-180.0, 180.0);
	public function Geohash()
	{
		//build map from encoding char to 0 padded bitfield
		for($i=0; $i<32; $i++)
		{
			$this->codingMap[substr($this->coding,$i,1)]=str_pad(decbin($i), 5, "0", STR_PAD_LEFT);
		}
		
	}
	
	/**
	* Decode a geohash and return an array with decimal lat,long in it
	*/
	public function decode($hash)
	{
		//decode hash into binary string
		$binary="";
		$hl=strlen($hash);
		for($i=0; $i<$hl; $i++)
		{
			$binary.=$this->codingMap[substr($hash,$i,1)];
		}
		
		//split the binary into lat and log binary strings
		$bl=strlen($binary);
		$blat="";
		$blong="";
		for ($i=0; $i<$bl; $i++)
		{
			if ($i%2)
				$blat=$blat.substr($binary,$i,1);
			else
				$blong=$blong.substr($binary,$i,1);
			
		}
		
		//now concert to decimal
		$lat=$this->binDecode($blat,-90,90);
		$long=$this->binDecode($blong,-180,180);
		
		//figure out how precise the bit count makes this calculation
		$latErr=$this->calcError(strlen($blat),-90,90);
		$longErr=$this->calcError(strlen($blong),-180,180);
				
		//how many decimal places should we use? There's a little art to
		//this to ensure I get the same roundings as geohash.org
		$latPlaces=max(1, -round(log10($latErr))) - 1;
		$longPlaces=max(1, -round(log10($longErr))) - 1;
		
		//round it
		$lat=round($lat, $latPlaces);
		$long=round($long, $longPlaces);
		
		return array($lat,$long);
	}

	
	/**
	* Encode a hash from given lat and long
	*/
	public function encode($lat,$long)
	{
		//how many bits does latitude need?	
		$plat=$this->precision($lat);
		$latbits=1;
		$err=45;
		while($err>$plat)
		{
			$latbits++;
			$err/=2;
		}
		
		//how many bits does longitude need?
		$plong=$this->precision($long);
		$longbits=1;
		$err=90;
		while($err>$plong)
		{
			$longbits++;
			$err/=2;
		}
		
		//bit counts need to be equal
		$bits=max($latbits,$longbits);
		
		//as the hash create bits in groups of 5, lets not
		//waste any bits - lets bulk it up to a multiple of 5
		//and favour the longitude for any odd bits
		$longbits=$bits;
		$latbits=$bits;
		$addlong=1;
		while (($longbits+$latbits)%5 != 0)
		{
			$longbits+=$addlong;
			$latbits+=!$addlong;
			$addlong=!$addlong;
		}
		
		
		//encode each as binary string
		$blat=$this->binEncode($lat,-90,90, $latbits);
		$blong=$this->binEncode($long,-180,180,$longbits);
		
		//merge lat and long together
		$binary="";
		$uselong=1;
		while (strlen($blat)+strlen($blong))
		{
			if ($uselong)
			{
				$binary=$binary.substr($blong,0,1);
				$blong=substr($blong,1);
			}
			else
			{
				$binary=$binary.substr($blat,0,1);
				$blat=substr($blat,1);
			}
			$uselong=!$uselong;
		}
		
		//convert binary string to hash
		$hash="";
		for ($i=0; $i<strlen($binary); $i+=5)
		{
			$n=bindec(substr($binary,$i,5));
			$hash=$hash.$this->coding[$n];
		}
		
		
		return $hash;
	}
	
	/**
	* What's the maximum error for $bits bits covering a range $min to $max
	*/
	private function calcError($bits,$min,$max)
	{
		$err=($max-$min)/2;
		while ($bits--)
			$err/=2;
		return $err;
	}
	
	/*
	* returns precision of number
	* precision of 42 is 0.5
	* precision of 42.4 is 0.05
	* precision of 42.41 is 0.005 etc
	*/
	private function precision($number)
	{
		$precision=0;
		$pt=strpos($number,'.');
		if ($pt!==false)
		{
			$precision=-(strlen($number)-$pt-1);
		}
		
		return pow(10,$precision)/2;
	}
	
	
	/**
	* create binary encoding of number as detailed in http://en.wikipedia.org/wiki/Geohash#Example
	* removing the tail recursion is left an exercise for the reader
	*/
	private function binEncode($number, $min, $max, $bitcount)
	{
		if ($bitcount==0)
			return "";
		
		#echo "$bitcount: $min $max<br>";
			
		//this is our mid point - we will produce a bit to say
		//whether $number is above or below this mid point
		$mid=($min+$max)/2;
		if ($number>$mid)
			return "1".$this->binEncode($number, $mid, $max,$bitcount-1);
		else
			return "0".$this->binEncode($number, $min, $mid,$bitcount-1);
	}
	

	/**
	* decodes binary encoding of number as detailed in http://en.wikipedia.org/wiki/Geohash#Example
	* removing the tail recursion is left an exercise for the reader
	*/
	private function binDecode($binary, $min, $max)
	{
		$mid=($min+$max)/2;
		
		if (strlen($binary)==0)
			return $mid;
			
		$bit=substr($binary,0,1);
		$binary=substr($binary,1);
		
		if ($bit==1)
			return $this->binDecode($binary, $mid, $max);
		else
			return $this->binDecode($binary, $min, $mid);
	}
	
   public static function getNeighbors($geohash){
      $neighbors = array();
	  
      $neighbors[0] = Geohash::calcNeighbors($geohash, 'top');
	  $neighbors[1] = Geohash::calcNeighbors($geohash, 'bottom');
	  $neighbors[2] = Geohash::calcNeighbors($geohash, 'right');
	  $neighbors[3] = Geohash::calcNeighbors($geohash, 'left');
      $neighbors[4] = Geohash::calcNeighbors($neighbors[3], 'top');
      $neighbors[5] = Geohash::calcNeighbors($neighbors[2], 'top');
      $neighbors[6] = Geohash::calcNeighbors($neighbors[2], 'bottom');
      $neighbors[7] = Geohash::calcNeighbors($neighbors[3], 'bottom');
      
      return $neighbors;
   }

   public static function calcNeighbors($geohash, $direction){
    
      $geohash = strtolower($geohash);
      $last = $geohash[strlen($geohash)-1];
      $type = (strlen($geohash) % 2)? 'odd' : 'even';
	  $base = substr($geohash, 0, strlen($geohash)-1);

      $b = Geohash::$borders[$type];
      $n = Geohash::$neighbors[$type];
      $val = strpos($b[$direction], $last);
      if (($val !== false) && ($val != -1))
         $base = Geohash::calcNeighbors($base, $direction);

      $ni = strpos($n[$direction], $last);
      return $base . Geohash::$base32[$ni];
   }

   public static function deHashisize($geohash){
      $isEven = true;
      $lat = Geohash::$latRange;
      $lng = Geohash::$lngRange;

      for ($i=0; $i<strlen($geohash); $i++){
         $c = $geohash[$i];
         $cd = strpos(Geohash::$base32, $c);
         for ($j=0; $j<5; $j++){
            $mask = Geohash::$bits[$j];
            $val = ($cd & $mask)? 0 : 1;
            if ($isEven)
               $lng[$val] = ($lng[0] + $lng[1]) / 2;
            else $lat[$val] = ($lat[0] + $lat[1]) / 2;
            $isEven = !$isEven;
         }
      }

      return array('latitude' => $lat, 'longitude' => $lng);
   }

   public static function geoHashize($latitude, $longitude){
      $ch = 0;
      $bit = 0;
      $geohash = "";
      $isEven = true;
      $precision = 12;
      $lat = Geohash::$latRange;
      $lng = Geohash::$lngRange;

      while (strlen($geohash) < $precision){
         if ($isEven){
            $mid = ($lng[0] + $lng[1]) / 2;
            if ($longitude > $mid){
               $ch |= Geohash::$bits[$bit];
               $lng[0] = $mid;
            }
            else $lng[1] = $mid;
         }
         else {
            $mid = ($lat[0] + $lat[1]) / 2;
            if ($latitude > $mid){
               $ch |= Geohash::$bits[$bit];
               $lat[0] = $mid;
            }
            else $lat[1] = $mid;
         }

         $isEven = !$isEven;
         if ($bit < 4) $bit++;
         else {
            $geohash .= Geohash::$base32[$ch];
            $bit = 0;
            $ch = 0;
         }
      }

      return $geohash;
   }
}






?>