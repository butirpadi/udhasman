<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('sidebar-update', function() {
    $value = \DB::table('appsetting')->whereName('sidebar_collapse')->first()->value;
    \DB::table('appsetting')->whereName('sidebar_collapse')->update(['value' => $value == 1 ? '0' : '1']);
});

// Tampilkan View Login
Route::get('/', function() {
    return redirect('login');
});

Route::get('login', function () {
    $login_background = \DB::table('appsetting')->whereName('login_background')->first()->value;
    return view('login',['login_background'=>$login_background]);
});

Route::post('login', function() {
    //auth user
    Auth::attempt(['username' => Request::input('username'), 'password' => Request::input('password')]);

    if (Request::ajax()) {
        if (Auth::check()) {
            return "true";
        } else {
            return "false";
        }
    } else {
        if (Auth::check()) {
            return redirect('home');
        } else {
            return redirect('login');
        }
    }
});

// Logout
Route::get('logout', function() {
    Auth::logout();
    return redirect('login');
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('fpdf',function(){
        $pdfOpt = new Fpdf();
    });

    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

    // cetak 
    Route::get('cetak-sales-order/{id}',function($id){
        CetakSalesOrder($id);
    });
    Route::get('cetak-delivery-order/{id}','DeliveryOrderController@cetakDeliveryOrder');


    Route::group(['prefix' => 'dailyhd'], function () {
        Route::get('/','DailyhdController@index');
        Route::get('create','DailyhdController@create');
        Route::get('edit/{id}','DailyhdController@edit');
        Route::get('cek-duplikasi/{tanggal}/{id}','DailyhdController@cekDuplikasi');
        Route::post('insert','DailyhdController@insert');
        Route::post('update','DailyhdController@update');
        Route::post('delete','DailyhdController@delete');
        Route::post('validate','DailyhdController@toValidate');
    });
    
    Route::group(['prefix' => 'cashbook'], function () {
        Route::get('/', 'CashbookController@index');
        Route::get('create', 'CashbookController@create');
        Route::post('insert', 'CashbookController@insert');
        Route::post('update', 'CashbookController@update');
        Route::get('edit/{cashbook_id}', 'CashbookController@edit');
        Route::get('delete/{cashbook_id}', 'CashbookController@delete');
    });

    Route::group(['prefix' => 'master'], function () {
        // LOKASI GALIAN
        Route::get('lokasi','LokasiGalianController@index');
        Route::get('lokasi/create','LokasiGalianController@create');
        Route::post('lokasi/insert','LokasiGalianController@insert');
        Route::get('lokasi/edit/{id}','LokasiGalianController@edit');
        Route::post('lokasi/update','LokasiGalianController@update');
        Route::post('lokasi/delete','LokasiGalianController@delete');

        // ARMADA
        Route::get('armada','ArmadaController@index');
        Route::get('armada/create','ArmadaController@create');
        Route::post('armada/insert','ArmadaController@insert');
        Route::get('armada/edit/{id}','ArmadaController@edit');
        Route::post('armada/update','ArmadaController@update');
        Route::post('armada/delete','ArmadaController@delete');

        // JABATAN
        Route::get('jabatan','JabatanController@index');
        Route::get('jabatan/create','JabatanController@create');
        Route::post('jabatan/insert','JabatanController@insert');
        Route::get('jabatan/edit/{id}','JabatanController@edit');
        Route::post('jabatan/update','JabatanController@update');
        Route::post('jabatan/delete','JabatanController@delete');

        // KARYAWAN
        Route::get('karyawan','KaryawanController@index');
        Route::get('karyawan/create','KaryawanController@create');
        Route::post('karyawan/insert','KaryawanController@insert');
        Route::get('karyawan/edit/{id}','KaryawanController@edit');
        Route::post('karyawan/update','KaryawanController@update');
        Route::post('karyawan/delete','KaryawanController@delete');

        // SUPPLIER
        Route::get('supplier','SupplierController@index');
        Route::get('supplier/create','SupplierController@create');
        Route::post('supplier/insert','SupplierController@insert');
        Route::get('supplier/edit/{id}','SupplierController@edit');
        Route::post('supplier/update','SupplierController@update');
        Route::post('supplier/delete','SupplierController@delete');

        // CUSTOMER
        Route::get('customer','CustomerController@index');
        Route::get('customer/create','CustomerController@create');
        Route::post('customer/insert','CustomerController@insert');
        Route::get('customer/edit/{id}','CustomerController@edit');
        Route::post('customer/update','CustomerController@update');
        Route::post('customer/delete','CustomerController@delete');

        // MATERIAL
        Route::get('material','MaterialController@index');
        Route::get('material/create','MaterialController@create');
        Route::post('material/insert','MaterialController@insert');
        Route::get('material/edit/{id}','MaterialController@edit');
        Route::post('material/update','MaterialController@update');
        Route::post('material/delete','MaterialController@delete');

        // ALAT
        Route::get('alat','AlatController@index');
        Route::get('alat/create','AlatController@create');
        Route::post('alat/insert','AlatController@insert');
        Route::get('alat/edit/{id}','AlatController@edit');
        Route::post('alat/update','AlatController@update');
        Route::post('alat/delete','AlatController@delete');

        // PRODUCT
        Route::get('product','ProductController@index');
        Route::get('product/create','ProductController@create');
        Route::post('product/insert','ProductController@insert');
        Route::get('product/edit/{id}','ProductController@edit');
        Route::post('product/update','ProductController@update');
        Route::post('product/delete','ProductController@delete');

        // PRODUCT UNITS
        Route::get('unit','ProductUnitController@index');
        Route::get('unit/create','ProductUnitController@create');
        Route::post('unit/insert','ProductUnitController@insert');
        Route::get('unit/edit/{id}','ProductUnitController@edit');
        Route::post('unit/update','ProductUnitController@update');
        Route::post('unit/delete','ProductUnitController@delete');

        // PEKERJAAN
        Route::get('pekerjaan','PekerjaanController@index');
        Route::get('pekerjaan/create','PekerjaanController@create');
        Route::post('pekerjaan/insert','PekerjaanController@insert');
        Route::get('pekerjaan/edit/{id}','PekerjaanController@edit');
        Route::post('pekerjaan/update','PekerjaanController@update');
        Route::post('pekerjaan/delete','PekerjaanController@delete');

    });

    Route::group(['prefix' => 'purchase'], function () {
        // ORDERS
        Route::get('order','PurchaseOrderController@index');
        Route::post('order/delete','PurchaseOrderController@delete');
        Route::get('order/create','PurchaseOrderController@create');
        Route::get('order/edit/{id}','PurchaseOrderController@edit');
        Route::get('order/validate/{id}','PurchaseOrderController@validateOrder');
        Route::post('order/insert','PurchaseOrderController@insert');
        Route::post('order/update','PurchaseOrderController@update');
        Route::get('order/delivery/{so_id}','PurchaseOrderController@delivery');
        Route::get('order/delivery/edit/{delivery_id}','PurchaseOrderController@deliveryEdit');
        Route::post('order/delivery/update','PurchaseOrderController@deliveryUpdate');
        Route::post('order/create-pekerjaan','PurchaseOrderController@createPekerjaan');
        Route::get('order/filter','PurchaseOrderController@filter');
        Route::get('order/reconcile/{id}','PurchaseOrderController@reconcile');
        Route::get('order/invoices/{purchase_order_id}','PurchaseOrderController@invoices');
        Route::get('order/invoices/show/{invoice_id}','PurchaseOrderController@showInvoice');
        Route::get('order/can-delete/{order_id}','PurchaseOrderController@canDelete');
        Route::get('order/cancel-order/{purchase_order_id}','PurchaseOrderController@cancelOrder');
    });

    Route::group(['prefix' => 'pembelian'], function () {
        // ORDERS
        Route::get('/','PembelianController@index');
        Route::get('create','PembelianController@create');
        Route::post('insert','PembelianController@insert');
        Route::get('edit/{id}','PembelianController@edit');
        Route::post('update','PembelianController@update');
        Route::get('validate/{id}','PembelianController@validateIt');
        Route::get('cancel/{id}','PembelianController@cancelIt');
        Route::post('delete','PembelianController@delete');
        // Route::get('index','PembelianController@index');
        
    });

    Route::group(['prefix' => 'penjualan'], function () {
        // ORDERS
        Route::get('/','PenjualanController@index');
        Route::get('create','PenjualanController@create');
        Route::post('insert','PenjualanController@insert');
        Route::get('edit/{id}','PenjualanController@edit');
        Route::post('update','PenjualanController@update');
        Route::get('validate/{id}','PenjualanController@validateIt');
        Route::get('cancel/{id}','PenjualanController@cancelIt');
        Route::post('delete','PenjualanController@delete');
        // Route::get('index','PembelianController@index');
        
    });

    Route::group(['prefix' => 'billinvoice'], function () {
        // bill
        Route::group(['prefix' => 'bill-pembelian'], function () {
            Route::get('/','BillController@index');
            Route::get('edit/{bill_id}','BillController@edit');
            Route::get('register-pembayaran/{bill_id}','BillController@registerPembayaran');
            Route::post('save-register-pembayaran','BillController@saveRegisterPembayaran');
        }); 
        
        
    });

    Route::group(['prefix' => 'attendance'], function () {
        // SETTING
        Route::get('setting','AttendanceController@setting');
        Route::post('update-time-setting','AttendanceController@updateTimeSetting');
        Route::post('setting/insert-holiday','AttendanceController@insertHoliday');
        Route::get('setting/delete-holiday/{holiday_id}','AttendanceController@deleteHoliday');
        // ATTENDANCE
        Route::get('attend','AttendanceController@attend');
        Route::post('attend/insert','AttendanceController@insertAttend');
        Route::post('get-attendance-table','AttendanceController@getAttendanceTable');
    });

    Route::group(['prefix' => 'payroll'], function () {
        // Default Payroll System
        Route::get('payroll','PayrollController@index');
        Route::get('payroll/show-payroll-table/{tanggal}/{kode_jabatan}','PayrollController@showPayrollTable');
        Route::post('payroll/get-pay-day','PayrollController@getPayDay');
        

        // PAYROLL STAFF
        Route::get('payroll-staff/show-payroll-table/{tanggal}','PayrollStaffController@showPayrollTable');
        Route::get('payroll-staff/pay/{karyawan_id}/{tanggal}','PayrollStaffController@addPay');
        Route::get('payroll-staff/edit-pay/{payroll_id}','PayrollStaffController@editPay');
        Route::post('payroll-staff/insert-pay','PayrollStaffController@insertPay');
        Route::post('payroll-staff/update-pay','PayrollStaffController@updatePay');
        Route::get('payroll-staff/validate-pay/{payroll_id}','PayrollStaffController@validatePay');
        Route::get('payroll-staff/reset/{payroll_id}','PayrollStaffController@resetPay');
        Route::get('payroll-staff/print-pdf/{payroll_id}','PayrollStaffController@printPdf');
        Route::get('payroll-staff/print-copy/{payroll_id}','PayrollStaffController@printCopy');
        Route::get('payroll-staff/print-direct/{payroll_id}','PayrollStaffController@printDirect');


        // PAYROLL DRIVER
        Route::get('payroll-driver/show-payroll-table/{tanggal}','PayrollDriverController@showPayrollTable');
        Route::get('payroll-driver/pay/{karyawan_id}/{tanggal}','PayrollDriverController@addPay');
        Route::get('payroll-driver/edit-pay/{payroll_id}','PayrollDriverController@editPay');
        Route::post('payroll-driver/insert-pay','PayrollDriverController@insertPay');
        Route::post('payroll-driver/update-pay','PayrollDriverController@updatePay');
        Route::get('payroll-driver/validate-pay/{payroll_id}','PayrollDriverController@validatePay');
        Route::get('payroll-driver/reset/{payroll_id}','PayrollDriverController@resetPay');
        Route::get('payroll-driver/print-pdf/{payroll_id}','PayrollDriverController@printPdf');
        Route::get('payroll-driver/print-copy/{payroll_id}','PayrollDriverController@printCopy');
        Route::get('payroll-driver/print-direct/{payroll_id}','PayrollDriverController@printDirect');

        // // PAYROLL STAFF
        // // Route::get('staff','PayrollDriverController@staff');

        // // PAYROLL DRIVER
        // Route::get('driver','PayrollDriverController@driver');
        // Route::get('driver/create','PayrollDriverController@driverCreate');
        // Route::post('driver/insert','PayrollDriverController@insert');
        // Route::post('driver/update','PayrollDriverController@update');
        // Route::get('driver/delete/{payroll_id}','PayrollDriverController@deletePayroll');
        // Route::get('driver/edit/{payroll_id}','PayrollDriverController@edit');
        // Route::get('driver/validate/{payroll_id}','PayrollDriverController@validatePayroll');
        // Route::get('driver/cancel-payroll/{payroll_id}','PayrollDriverController@cancelPayroll');
        // Route::get('driver/get-delivery-order/{driver_id}/{start_date}/{end_date}','PayrollDriverController@getDeliveryOrderList');

        // // PAYROLL STAFF
        // Route::get('staff','PayrollStaffController@index');
        // Route::get('staff/create','PayrollStaffController@create');
        // Route::get('staff/edit/{payroll_id}','PayrollStaffController@edit');
        // Route::post('staff/insert','PayrollStaffController@insert');
        // Route::post('staff/update','PayrollStaffController@update');
        // Route::get('staff/get-attendance/{staff_id}/{awal}/{akhir}','PayrollStaffController@getAttendance');
        // Route::get('staff/get-workday/{staff_id}/{awal}/{akhir}','PayrollStaffController@getWorkday');
        // Route::get('staff/validate/{payroll_id}','PayrollStaffController@validatePayroll');
        // Route::get('staff/cancel-payroll/{payroll_id}','PayrollStaffController@cancelPayroll');

    });

    Route::group(['prefix' => 'sales'], function () {
        // ORDERS
        Route::get('order','SalesOrderController@index');
        Route::post('order/delete','SalesOrderController@delete');
        Route::get('order/create','SalesOrderController@create');
        Route::get('order/edit/{id}','SalesOrderController@edit');
        Route::get('order/validate/{id}','SalesOrderController@validateOrder');
        Route::post('order/insert','SalesOrderController@insert');
        Route::post('order/insert-direct-sales','SalesOrderController@insertDirectSales');
        Route::post('order/update','SalesOrderController@update');
        Route::get('order/delivery/{so_id}','SalesOrderController@delivery');
        Route::get('order/delivery/edit/{delivery_id}','SalesOrderController@deliveryEdit');
        Route::post('order/delivery/update','SalesOrderController@deliveryUpdate');
        Route::post('order/create-pekerjaan','SalesOrderController@createPekerjaan');
        Route::get('order/filter','SalesOrderController@filter');
        Route::get('order/reconcile/{id}','SalesOrderController@reconcile');
        Route::get('order/invoices/{sales_order_id}','SalesOrderController@invoices');
        Route::get('order/invoices/show/{invoice_id}','SalesOrderController@showInvoice');
        Route::post('order/update-direct-sales','SalesOrderController@updateDirectSales');
        Route::get('order/validate-direct-sales/{sales_order_id}','SalesOrderController@validateDirectSalesOrder');
        Route::get('order/pdf/{id}','SalesOrderController@pdf');
        Route::get('order/pdf-copy/{id}','SalesOrderController@pdfCopy');
        Route::get('order/cetak/{id}','SalesOrderController@cetak');


        // DIRECT SALES
        Route::post('order/insert-direct-sales','SalesOrderController@insertDirectSales');

    });

    Route::group(['prefix' => 'delivery'], function () {
        // ORDERS
        Route::get('order','DeliveryOrderController@index');
        Route::get('order/edit/{id}','DeliveryOrderController@edit');
        // Route::post('order/batch-edit','DeliveryOrderController@batchEdit');
        Route::get('order/batch-edit/{sales_order_id}','DeliveryOrderController@batchEdit');
        Route::post('order/batch-update','DeliveryOrderController@batchUpdate');
        Route::post('order/delete','DeliveryOrderController@delete');
        Route::post('order/update','DeliveryOrderController@update');
        Route::post('order/to-validate','DeliveryOrderController@toValidate');
        Route::get('order/reconcile/{id}','DeliveryOrderController@reconcile');
        Route::get('order/filter','DeliveryOrderController@filter');
        
    });

    Route::group(['prefix' => 'invoice'], function () {
        // CUSTOMER INVOICE
        Route::get('customer','CustomerInvoiceController@index');
        Route::get('customer/edit/{id}','CustomerInvoiceController@edit');
        Route::get('customer/show-one-invoice/{invoice_id}','CustomerInvoiceController@showOneInvoice');
        Route::get('customer/validate/{id}','CustomerInvoiceController@toValidate');
        Route::get('customer/reconcile/{invoice_id}','CustomerInvoiceController@reconcile');
        Route::get('customer/register-payment/{invoice_id}','CustomerInvoiceController@registerPayment');
        Route::get('customer/payments/{invoice_id}','CustomerInvoiceController@payments');
        Route::get('customer/payments/delete/{payment_id}','CustomerInvoiceController@deletePayment');
        Route::post('customer/save-register-payment','CustomerInvoiceController@saveRegisterPayment');

        // SUPPLIER BILL
        Route::get('supplier/bill','SupplierBillController@index');
        Route::get('supplier/bill/edit/{bill_id}','SupplierBillController@edit');
        Route::get('supplier/bill/reg-payment/{bill_id}','SupplierBillController@regPayment');
        Route::post('supplier/bill/save-register-payment','SupplierBillController@saveRegPayment');
        Route::get('supplier/bill/payments/{bill_id}','SupplierBillController@payments');
        Route::get('supplier/bill/payment/show/{payment_id}','SupplierBillController@showPayment');
        Route::get('supplier/bill/payment/delete/{payment_id}','SupplierBillController@deletePayment');
        Route::get('supplier/bill/cancel-order/{bill_id}','SupplierBillController@cancelOrder');

    });

    Route::group(['prefix' => 'report'], function () {
        // REPORT PURCHASE
        Route::get('purchase','ReportPurchaseController@index');
        Route::post('purchase/filter-by-date','ReportPurchaseController@filterByDate');
        Route::post('purchase/filter-by-date-n-supplier','ReportPurchaseController@filterByDateNSupplier');
        Route::get('purchase/filter-by-date/pdf/{start}/{end}','ReportPurchaseController@filterByDateToPdf');

        // REPORT SALES
        Route::get('sales','ReportSalesController@index');
        Route::get('sales/get-pekerjaan-by-customer/{customer_id}','ReportSalesController@getPekerjaanByCustomer');
        Route::post('sales/report-by-date','ReportSalesController@reportByDate');
        Route::post('sales/report-by-date-detail','ReportSalesController@reportByDateDetail');
        Route::post('sales/report-by-customer','ReportSalesController@reportByCustomer');
        Route::post('sales/report-by-customer-pekerjaan','ReportSalesController@reportByCustomerPekerjaan');
        Route::post('sales/report-by-customer-detail','ReportSalesController@reportByCustomerDetail');
        Route::post('sales/report-by-lokasi-galian','ReportSalesController@reportByLokasiGalian');
        Route::post('sales/report-by-sales-type','ReportSalesController@reportBySalesType');
        Route::post('sales/report-by-sales-type-all','ReportSalesController@reportBySalesTypeAll');

        //  REPORT DELIVERY
        Route::get('delivery','ReportDeliveryController@index');
        Route::post('delivery/report-by-date','ReportDeliveryController@reportByDate');
        Route::post('delivery/report-by-customer','ReportDeliveryController@reportByCustomer');
        Route::post('delivery/report-by-lokasi-galian','ReportDeliveryController@reportByLokasiGalian');
        Route::post('delivery/report-by-driver','ReportDeliveryController@reportByDriver');
    });

    Route::get('api/get-auto-complete-provinsi','ApiController@getAutoCompleteProvinsi');
    Route::get('api/get-auto-complete-kabupaten','ApiController@getAutoCompleteKabupaten');
    Route::get('api/get-auto-complete-kecamatan','ApiController@getAutoCompleteKecamatan');
    Route::get('api/get-auto-complete-desa','ApiController@getAutoCompleteDesa');
    Route::get('api/get-auto-complete-customer','ApiController@getAutoCompleteCustomer');
    Route::get('api/get-auto-complete-supplier','ApiController@getAutoCompleteSupplier');
    Route::get('api/get-auto-complete-armada','ApiController@getAutoCompleteArmada');
    Route::get('api/get-auto-complete-alat','ApiController@getAutoCompleteAlat');
    Route::get('api/get-auto-complete-driver','ApiController@getAutoCompleteDriver');
    Route::get('api/get-auto-complete-lokasi-galian','ApiController@getAutoCompleteLokasiGalian');
    Route::get('api/get-auto-complete-material','ApiController@getAutoCompleteMaterial');
    Route::get('api/get-auto-complete-product','ApiController@getAutoCompleteProduct');
    Route::get('api/get-auto-complete-staff','ApiController@getAutoCompleteStaff');
    Route::get('api/get-select-customer','ApiController@getSelectCustomer');
    Route::get('api/get-select-pekerjaan/{customer_id}','ApiController@getSelectPekerjaan');
    Route::get('api/get-pekerjaan-by-customer/{customer_id}','ApiController@getPekerjaanByCustomer');

    // DELIVERY ORDER WITH API CONTROLLER
    Route::get('api/do-cetak/{id}','ApiController@doCetak');
    Route::get('api/do-pdf/{id}','ApiController@doPdf');
    Route::get('api/do-pdf-copy/{id}','ApiController@doPdfCopy');
});
