<?php

use Illuminate\Database\Seeder;

class AppsettingTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('appsetting')->delete();
        
        \DB::table('appsetting')->insert(array (
            0 => 
            array (
                'name' => 'alamat_1',
                'value' => '',
                'desc' => 'Company address first line',
                'active' => 'Y',
            ),
            1 => 
            array (
                'name' => 'alamat_2',
                'value' => '',
                'desc' => 'Company address second line',
                'active' => 'Y',
            ),
            2 => 
            array (
                'name' => 'alat_counter',
                'value' => '6',
                'desc' => 'Alat Berat Counter',
                'active' => 'Y',
            ),
            3 => 
            array (
                'name' => 'alat_prefix',
                'value' => '',
                'desc' => 'Alat Berat Prefix',
                'active' => 'Y',
            ),
            4 => 
            array (
                'name' => 'armada_counter',
                'value' => '68',
                'desc' => 'Armada Counter',
                'active' => 'Y',
            ),
            5 => 
            array (
                'name' => 'armada_prefix',
                'value' => 'ARD',
                'desc' => 'Armada Prefix',
                'active' => 'Y',
            ),
            6 => 
            array (
                'name' => 'attendance_in_time',
                'value' => '',
                'desc' => 'Attendance in Time',
                'active' => 'N',
            ),
            7 => 
            array (
                'name' => 'attendance_libur_minggu',
                'value' => 'N',
                'desc' => 'Attendance Libur Minggu',
                'active' => 'N',
            ),
            8 => 
            array (
                'name' => 'attendance_libur_sabtu',
                'value' => 'N',
                'desc' => 'Attendance Libur Sabtu',
                'active' => 'N',
            ),
            9 => 
            array (
                'name' => 'attendance_out_time',
                'value' => '',
                'desc' => 'Attendance Out Time',
                'active' => 'Y',
            ),
            10 => 
            array (
                'name' => 'cashbook_counter',
                'value' => '99',
                'desc' => 'Cashbook Counter',
                'active' => 'Y',
            ),
            11 => 
            array (
                'name' => 'cashbook_credit_prefix',
                'value' => '',
                'desc' => 'Cashbook Credit Prefix',
                'active' => 'Y',
            ),
            12 => 
            array (
                'name' => 'cashbook_debit_prefix',
                'value' => '',
                'desc' => 'Cashbook Debit Prefix',
                'active' => 'Y',
            ),
            13 => 
            array (
                'name' => 'company_logo',
                'value' => 'logo_5EV58TedQp.png',
                'desc' => NULL,
                'active' => 'Y',
            ),
            14 => 
            array (
                'name' => 'company_name',
                'value' => '',
                'desc' => 'Company Name',
                'active' => 'Y',
            ),
            15 => 
            array (
                'name' => 'customer_counter',
                'value' => '15',
                'desc' => 'Customer Counter',
                'active' => 'Y',
            ),
            16 => 
            array (
                'name' => 'customer_jatuh_tempo',
                'value' => '10',
                'desc' => 'Customer Jatuh Tempo',
                'active' => 'N',
            ),
            17 => 
            array (
                'name' => 'customer_payment_counter',
                'value' => '43',
                'desc' => 'Customer Payment Counter',
                'active' => 'Y',
            ),
            18 => 
            array (
                'name' => 'customer_payment_prefix',
                'value' => 'CUST.IN',
                'desc' => 'Customer Payment Prefix',
                'active' => 'Y',
            ),
            19 => 
            array (
                'name' => 'customer_prefix',
                'value' => '',
                'desc' => 'Customer Prefix',
                'active' => 'Y',
            ),
            20 => 
            array (
                'name' => 'dailyhd_counter',
                'value' => '43',
                'desc' => 'Operasional Alat Berat Counter',
                'active' => 'Y',
            ),
            21 => 
            array (
                'name' => 'default_pdf_action',
                'value' => 'I',
                'desc' => NULL,
                'active' => 'N',
            ),
            22 => 
            array (
                'name' => 'delivery_order_catatan_kaki',
                'value' => 'Barang telah diterima dalam keadaan baik dan cukup oleh: ',
                'desc' => 'Footnote Surat Jalan',
                'active' => 'Y',
            ),
            23 => 
            array (
                'name' => 'delivery_order_slip_copy_number',
                'value' => '4',
                'desc' => NULL,
                'active' => 'N',
            ),
            24 => 
            array (
                'name' => 'delivery_order_slip_tertanda',
                'value' => 'Admin',
                'desc' => NULL,
                'active' => 'N',
            ),
            25 => 
            array (
                'name' => 'do_counter',
                'value' => '491',
                'desc' => 'Pengiriman Counter',
                'active' => 'Y',
            ),
            26 => 
            array (
                'name' => 'do_prefix',
                'value' => 'DO',
                'desc' => 'Pengiriman Prefix',
                'active' => 'Y',
            ),
            27 => 
            array (
                'name' => 'dp_counter',
                'value' => '43',
                'desc' => ' ',
                'active' => 'N',
            ),
            28 => 
            array (
                'name' => 'dp_pay_prefix',
                'value' => 'DP',
                'desc' => NULL,
                'active' => 'N',
            ),
            29 => 
            array (
                'name' => 'dp_prefix',
                'value' => 'DP',
                'desc' => NULL,
                'active' => 'N',
            ),
            30 => 
            array (
                'name' => 'driver_counter',
                'value' => '122',
                'desc' => 'Driver Counter',
                'active' => 'Y',
            ),
            31 => 
            array (
                'name' => 'driver_prefix',
                'value' => '',
                'desc' => 'Driver Prefix',
                'active' => 'Y',
            ),
            32 => 
            array (
                'name' => 'email',
                'value' => 'info@hasilmancing.com',
                'desc' => 'Company Email',
                'active' => 'Y',
            ),
            33 => 
            array (
                'name' => 'hutang_counter',
                'value' => '76',
                'desc' => 'Hutang Counter',
                'active' => 'Y',
            ),
            34 => 
            array (
                'name' => 'hutang_prefix',
                'value' => '',
                'desc' => 'Hutang Prefix',
                'active' => 'Y',
            ),
            35 => 
            array (
                'name' => 'invoice_counter',
                'value' => '118',
                'desc' => NULL,
                'active' => 'N',
            ),
            36 => 
            array (
                'name' => 'invoice_item_number',
                'value' => '1',
                'desc' => NULL,
                'active' => 'N',
            ),
            37 => 
            array (
                'name' => 'invoice_prefix',
                'value' => 'INV',
                'desc' => NULL,
                'active' => 'N',
            ),
            38 => 
            array (
                'name' => 'login_background',
                'value' => 'logo_h4YWzULzPD.jpg',
                'desc' => 'Login Background',
                'active' => 'Y',
            ),
            39 => 
            array (
                'name' => 'logo_cetak',
                'value' => 'logo_cetak.png',
                'desc' => 'Logo Cetak',
                'active' => 'Y',
            ),
            40 => 
            array (
                'name' => 'lokasi_galian_counter',
                'value' => '8',
                'desc' => 'Lokasi Galian Counter',
                'active' => 'N',
            ),
            41 => 
            array (
                'name' => 'lokasi_galian_prefix',
                'value' => 'GLC',
                'desc' => 'Lokasi Galian Prefix',
                'active' => 'N',
            ),
            42 => 
            array (
                'name' => 'master_payment_in_counter',
                'value' => '39',
                'desc' => NULL,
                'active' => 'N',
            ),
            43 => 
            array (
                'name' => 'master_payment_in_prefix',
                'value' => '',
                'desc' => NULL,
                'active' => 'Y',
            ),
            44 => 
            array (
                'name' => 'material_counter',
                'value' => '12',
                'desc' => 'Materil Counter',
                'active' => 'Y',
            ),
            45 => 
            array (
                'name' => 'material_prefix',
                'value' => 'MTR',
                'desc' => 'Material Prefix',
                'active' => 'Y',
            ),
            46 => 
            array (
                'name' => 'nota_timbang_counter',
                'value' => '72',
                'desc' => NULL,
                'active' => 'N',
            ),
            47 => 
            array (
                'name' => 'nota_timbang_prefix',
                'value' => 'TMB',
                'desc' => NULL,
                'active' => 'N',
            ),
            48 => 
            array (
                'name' => 'operasional_alat_prefix',
                'value' => '',
                'desc' => 'Operasional Alat Berat Prefix',
                'active' => 'N',
            ),
            49 => 
            array (
                'name' => 'paging_item_number',
                'value' => '',
                'desc' => 'Paging Item Number',
                'active' => 'Y',
            ),
            50 => 
            array (
                'name' => 'partner_counter',
                'value' => '8',
                'desc' => 'Partner Counter',
                'active' => 'N',
            ),
            51 => 
            array (
                'name' => 'partner_prefix',
                'value' => '',
                'desc' => 'Partner Prefix',
                'active' => 'Y',
            ),
            52 => 
            array (
                'name' => 'payment_in_counter',
                'value' => '830',
                'desc' => NULL,
                'active' => 'Y',
            ),
            53 => 
            array (
                'name' => 'payment_in_prefix',
                'value' => 'PAY.IN',
                'desc' => NULL,
                'active' => 'Y',
            ),
            54 => 
            array (
                'name' => 'payment_out_counter',
                'value' => '17',
                'desc' => NULL,
                'active' => 'Y',
            ),
            55 => 
            array (
                'name' => 'payment_out_prefix',
                'value' => 'PAY.OUT',
                'desc' => NULL,
                'active' => 'Y',
            ),
            56 => 
            array (
                'name' => 'payroll_counter',
                'value' => '2108',
                'desc' => NULL,
                'active' => 'Y',
            ),
            57 => 
            array (
                'name' => 'payroll_date_range',
                'value' => '7',
                'desc' => NULL,
                'active' => 'Y',
            ),
            58 => 
            array (
                'name' => 'payroll_day',
                'value' => '4',
                'desc' => NULL,
                'active' => 'Y',
            ),
            59 => 
            array (
                'name' => 'payroll_prefix',
                'value' => '',
                'desc' => NULL,
                'active' => 'Y',
            ),
            60 => 
            array (
                'name' => 'payslip_catatan_1',
                'value' => 'PERHATIAN :',
                'desc' => NULL,
                'active' => 'Y',
            ),
            61 => 
            array (
                'name' => 'payslip_catatan_2',
                'value' => '1. Teliti dahulu struk pembayaran sebelum meninggalkan kantor.',
                'desc' => NULL,
                'active' => 'Y',
            ),
            62 => 
            array (
                'name' => 'payslip_catatan_3',
                'value' => '2.  Laporkan kepada bagian administrasi jika terjadi kesalahan.',
                'desc' => NULL,
                'active' => 'Y',
            ),
            63 => 
            array (
                'name' => 'payslip_tertanda',
                'value' => ' BAGIAN ADMINISTRASI',
                'desc' => NULL,
                'active' => 'Y',
            ),
            64 => 
            array (
                'name' => 'pembelian_counter',
                'value' => '57',
                'desc' => NULL,
                'active' => 'Y',
            ),
            65 => 
            array (
                'name' => 'pembelian_prefix',
                'value' => '',
                'desc' => NULL,
                'active' => 'Y',
            ),
            66 => 
            array (
                'name' => 'pengiriman_counter',
                'value' => '1336',
                'desc' => NULL,
                'active' => 'Y',
            ),
            67 => 
            array (
                'name' => 'pengiriman_prefix',
                'value' => '',
                'desc' => NULL,
                'active' => 'Y',
            ),
            68 => 
            array (
                'name' => 'penjualan_counter',
                'value' => '38',
                'desc' => NULL,
                'active' => 'Y',
            ),
            69 => 
            array (
                'name' => 'penjualan_prefix',
                'value' => 'SO',
                'desc' => NULL,
                'active' => 'Y',
            ),
            70 => 
            array (
                'name' => 'piutang_counter',
                'value' => '740',
                'desc' => NULL,
                'active' => 'Y',
            ),
            71 => 
            array (
                'name' => 'piutang_prefix',
                'value' => '',
                'desc' => NULL,
                'active' => 'Y',
            ),
            72 => 
            array (
                'name' => 'po_counter',
                'value' => '27',
                'desc' => NULL,
                'active' => 'Y',
            ),
            73 => 
            array (
                'name' => 'po_invoice_prefix',
                'value' => 'BILL',
                'desc' => NULL,
                'active' => 'Y',
            ),
            74 => 
            array (
                'name' => 'po_prefix',
                'value' => 'PO',
                'desc' => NULL,
                'active' => 'Y',
            ),
            75 => 
            array (
                'name' => 'printer_address',
                'value' => '//localhost/LX-300',
                'desc' => NULL,
                'active' => 'Y',
            ),
            76 => 
            array (
                'name' => 'product_counter',
                'value' => '9',
                'desc' => NULL,
                'active' => 'Y',
            ),
            77 => 
            array (
                'name' => 'product_prefix',
                'value' => 'PRD',
                'desc' => NULL,
                'active' => 'Y',
            ),
            78 => 
            array (
                'name' => 'sidebar_collapse',
                'value' => '0',
                'desc' => NULL,
                'active' => 'Y',
            ),
            79 => 
            array (
                'name' => 'slip_copy_number',
                'value' => '3',
                'desc' => NULL,
                'active' => 'Y',
            ),
            80 => 
            array (
                'name' => 'slip_kota',
                'value' => 'Banyuwangi',
                'desc' => NULL,
                'active' => 'Y',
            ),
            81 => 
            array (
                'name' => 'so_counter',
                'value' => '160',
                'desc' => NULL,
                'active' => 'Y',
            ),
            82 => 
            array (
                'name' => 'so_prefix',
                'value' => 'SO',
                'desc' => NULL,
                'active' => 'Y',
            ),
            83 => 
            array (
                'name' => 'staff_counter',
                'value' => '19',
                'desc' => NULL,
                'active' => 'Y',
            ),
            84 => 
            array (
                'name' => 'staff_prefix',
                'value' => 'STF',
                'desc' => NULL,
                'active' => 'Y',
            ),
            85 => 
            array (
                'name' => 'supplier_bill_counter',
                'value' => '26',
                'desc' => NULL,
                'active' => 'Y',
            ),
            86 => 
            array (
                'name' => 'supplier_counter',
                'value' => '9',
                'desc' => NULL,
                'active' => 'Y',
            ),
            87 => 
            array (
                'name' => 'supplier_payment_counter',
                'value' => '15',
                'desc' => NULL,
                'active' => 'Y',
            ),
            88 => 
            array (
                'name' => 'supplier_payment_prefix',
                'value' => 'SUPP.OUT',
                'desc' => NULL,
                'active' => 'Y',
            ),
            89 => 
            array (
                'name' => 'supplier_prefix',
                'value' => '',
                'desc' => NULL,
                'active' => 'Y',
            ),
            90 => 
            array (
                'name' => 'surat_jalan_copy',
                'value' => 'Y',
                'desc' => NULL,
                'active' => 'Y',
            ),
            91 => 
            array (
                'name' => 'telp',
                'value' => '',
                'desc' => 'Company Telp',
                'active' => 'Y',
            ),
        ));
        
        
    }
}
