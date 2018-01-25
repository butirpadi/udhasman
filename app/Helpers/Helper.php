<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function GenerateCustomerInvoiceNumber()
    {
        $invoice_counter = \DB::table('appsetting')->where('name','invoice_counter')->first()->value;
		$invoice_number = 'INV/' . date('Y') . '/000' . $invoice_counter++;
		// update invoice counter
		\DB::table('appsetting')->where('name','invoice_counter')->update(['value'=>$invoice_counter]);

		return $invoice_number;
    }


    public static function convertTerbilang($number)
    {
    	return terbilangs($number);
    }

    function terbilangs($x){

        if($x<0){
             $hasil = "minus ".trim(konversi(x));
        }else{
             $poin = trim(tkoma($x));
             $hasil = trim(konversi($x));
        }
        if($poin){
             $hasil = $hasil." koma ".$poin;
        }else{
             $hasil = $hasil;
        }
        
        return $hasil; 
    }

    function konversi($x){
        $x = abs($x);
        $angka = array ("","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";

        if($x < 12){
        $temp = " ".$angka[$x];
        }else if($x<20){
        $temp = konversi($x - 10)." belas";
        }else if ($x<100){
        $temp = konversi($x/10)." puluh". konversi($x%10);
        }else if($x<200){
        $temp = " seratus".konversi($x-100);
        }else if($x<1000){
        $temp = konversi($x/100)." ratus".konversi($x%100); 
        }else if($x<2000){
        $temp = " seribu".konversi($x-1000);
        }else if($x<1000000){
        $temp = konversi($x/1000)." ribu".konversi($x%1000); 
        }else if($x<1000000000){
        $temp = konversi($x/1000000)." juta".konversi($x%1000000);
        }else if($x<1000000000000){
        $temp = konversi($x/1000000000)." milyar".konversi($x%1000000000);
        }

        return $temp;
    }


    function tkoma($x){
        $str = stristr($x,".");
        $ex = explode('.',$x);
        if(isset($ex[1]))
        {
            if(($ex[1]/10) >= 1){
                $a = abs($ex[1]);
            }
            $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan","sepuluh", "sebelas");
            $temp = "";

            $a2 = $ex[1]/10;
            $pjg = strlen($str);
            $i =1;


            if($a>=1 && $a< 12){ 
                $temp .= " ".$string[$a];
            }else if($a>12 && $a<20){ 
                $temp .= konversi($a - 10)." belas";
            }else if ($a>20 && $a<100){ 
                $temp .= konversi($a/10)." puluh". konversi($a%10);
            }else{
                if($a2<1){

                    while ($i<$pjg){ 
                        $char = substr($str,$i,1); 
                        $i++;
                        $temp .= " ".$string[$char];
                    }
                }
            }
            
            return $temp;

        }else{
            return FALSE;
        }

    }

}