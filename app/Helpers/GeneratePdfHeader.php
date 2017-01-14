<?php

if (!function_exists('GeneratePdfHeader')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function GeneratePdfHeader(&$pdf, $header_titel, $header_subtitle)
    {
    	$logo_cetak = \DB::table('appsetting')->whereName('logo_cetak')->first()->value;
    	$company_name = strtoupper(\DB::table('appsetting')->whereName('company_name')->first()->value);
    	$alamat_1 = \DB::table('appsetting')->whereName('alamat_1')->first()->value;
    	$alamat_2 = \DB::table('appsetting')->whereName('alamat_2')->first()->value;
    	$telp = \DB::table('appsetting')->whereName('telp')->first()->value;
    	$email = \DB::table('appsetting')->whereName('email')->first()->value;

    	// image/logo
        $top_y =$pdf->GetY();
    	$pdf->Image('img/' . $logo_cetak,8,$pdf->GetY(),35);
    	// company name
    	$pdf->SetX(45);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','B',8);
    	$pdf->Cell(0,4,$company_name,0,2,'L',false);
    	$pdf->SetFont('Arial',null,6);
    	$pdf->Cell(0,3,$alamat_1,0,2,'L',false);
    	$pdf->Cell(0,3,$alamat_2,0,2,'L',false);
    	$pdf->Cell(0,3,'T. ' . $telp . ' | ' . 'E. ' . $email ,0,2,'L',false);
        // $pdf->Ln(3);
        
        // Line di bawah header
        $pdf->SetDrawColor(0,128,128);
        $pdf->SetX(8);
        $pdf->SetLineWidth(0.6);
        $pdf->Cell($pdf->GetPageWidth()-16,2,null,'B',0,'L',false);
        // $pdf->Cell(0,1,'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,0,'L',false);
        $last_y = $pdf->GetY();

        // TEXT header titel
        $pdf->SetXY(8,$top_y);
        $pdf->SetFont('Arial','B',18);
        $pdf->SetTextColor(0,128,128);
        $pdf->Cell($pdf->GetPageWidth()-16,8,$header_titel,0,2,'R',false);
        $pdf->Ln(2);
        $pdf->SetXY(8,$top_y+6);
        $pdf->SetFont('Arial',null,10);
        $pdf->Cell($pdf->GetPageWidth()-16,8,'#'.$header_subtitle,0,0,'R',false);

        $pdf->SetXY(0,$last_y);
        $pdf->SetFont('Arial',null,8);
        $pdf->Ln(1);
    }
}
