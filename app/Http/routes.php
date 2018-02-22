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
        Route::get('search', 'DailyhdController@getSearch');
        Route::get('group-by/{val}', 'DailyhdController@groupBy');
        Route::get('pdf/{id}','DailyhdController@pdf');
    });
    
    Route::group(['prefix' => 'finance/cashbook'], function () {
        Route::get('/', 'CashbookController@index');
        Route::get('create', 'CashbookController@create');
        Route::post('insert', 'CashbookController@insert');
        Route::post('update', 'CashbookController@update');
        Route::get('edit/{cashbook_id}', 'CashbookController@edit');
        Route::get('delete/{cashbook_id}', 'CashbookController@delete');
    });

    Route::group(['prefix' => 'finance/hutang'], function () {
        Route::get('/', 'HutangController@index');
        Route::get('create', 'HutangController@create');
        Route::post('insert', 'HutangController@insert');
        Route::get('edit/{id}', 'HutangController@edit');
        Route::post('update', 'HutangController@update');
        Route::get('show-po/{hutangid}/{id}', 'HutangController@showPo');
        Route::get('reg-payment/{id}', 'HutangController@regPayment');
        Route::post('insert-payment', 'HutangController@insertPayment');
        Route::get('validate/{id}', 'HutangController@validateHutang');
        Route::get('get-payment-info/{id}', 'HutangController@getPaymentInfo');
        Route::get('del-payment/{id}', 'HutangController@delPayment');
        Route::get('cancel-hutang/{id}', 'HutangController@cancelHutang');
        Route::get('delete/{id}', 'HutangController@toDelete');
        Route::get('confirm/{id}', 'HutangController@toConfirm');
        Route::get('cancel/{id}', 'HutangController@toCancel');
        Route::get('filter/{state}', 'HutangController@toFilter');
        Route::get('filter/{filterby}/{val}', 'HutangController@filter');
        Route::get('groupby/{groupby}', 'HutangController@toGroupBy');
        Route::get('get-by-partner/{partnerid}', 'HutangController@getByPartner');
        Route::get('search', 'HutangController@getSearch');
    });

    Route::group(['prefix' => 'finance/piutang'], function () {
        Route::get('/', 'PiutangController@index');
        Route::get('search/{val}', 'PiutangController@index');
        Route::get('search', 'PiutangController@getSearch');
        Route::get('form-view', 'PiutangController@formView');
        Route::get('create', 'PiutangController@create');
        Route::post('insert', 'PiutangController@insert');
        Route::post('update', 'PiutangController@update');
        Route::get('edit/{id}', 'PiutangController@edit');
        Route::get('delete/{id}', 'PiutangController@toDelete');
        Route::get('confirm/{id}', 'PiutangController@toConfirm');
        Route::get('cancel/{id}', 'PiutangController@toCancel');
        Route::get('pay/{id}', 'PiutangController@toPay');
        Route::post('add-pay', 'PiutangController@addPay');
        Route::get('get-payment-info/{id}', 'PiutangController@getPaymentInfo');
        Route::get('del-payment/{id}', 'PiutangController@delPayment');
        Route::get('pdf/{id}', 'PiutangController@toPdf');
        Route::get('view-pdf/{id}', 'PiutangController@viewPdf');
        Route::get('print/{id}', 'PiutangController@toPrint');
        Route::get('payment-to-print/{id}', 'PiutangController@paymentToPrint');
        Route::get('show-so/{id}', 'PiutangController@showSo');
        Route::get('receive-pay', 'PiutangController@receivePay');
        Route::get('filter/{filterby}/{val}','PiutangController@filter');
        Route::get('groupby/{val}','PiutangController@groupby');
        Route::get('groupdetail/{groupby}/{id}','PiutangController@groupdetail');
        
    });

    Route::group(['prefix' => 'finance/payment'], function () {
        Route::get('/', 'PaymentController@index');
        Route::get('create', 'PaymentController@create');
        Route::post('insert', 'PaymentController@insert');
        Route::get('edit/{id}', 'PaymentController@edit');
        Route::post('update', 'PaymentController@update');
        Route::get('confirm/{id}', 'PaymentController@toConfirm');
        Route::get('reconcile/{id}', 'PaymentController@toReconcile');
        Route::get('unreconcile/{id}', 'PaymentController@toUnreconcile');
        Route::get('cancel/{id}', 'PaymentController@toCancel');
        Route::get('delete/{id}', 'PaymentController@toDelete');
        Route::get('print/{id}', 'PaymentController@toPrint');
        Route::get('get-amount-due/{customer_id}', 'PaymentController@getAmountDue');
        Route::get('filter/{filterby}/{val}','PaymentController@filter');
        Route::get('groupby/{val}','PaymentController@groupby');
        Route::get('groupdetail/{groupby}/{id}','PaymentController@groupdetail');
        Route::get('search', 'PaymentController@getSearch');
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

        // Partner
        Route::get('partner','PartnerController@index');
        Route::get('partner/create','PartnerController@create');
        Route::post('partner/insert','PartnerController@insert');
        Route::get('partner/edit/{id}','PartnerController@edit');
        Route::post('partner/update','PartnerController@update');
        Route::get('partner/filter/{val}','PartnerController@filter');
        Route::get('partner/search','PartnerController@search');
        Route::post('partner/delete','PartnerController@delete');

        // STAFF
        Route::get('staff','StaffController@index');
        Route::get('staff/create','StaffController@create');
        Route::post('staff/insert','StaffController@insert');
        Route::get('staff/edit/{id}','StaffController@edit');
        Route::post('staff/update','StaffController@update');
        Route::post('staff/delete','StaffController@delete');

        // DRIVER
        Route::get('driver','DriverController@index');
        Route::get('driver/create','DriverController@create');
        Route::post('driver/insert','DriverController@insert');
        Route::get('driver/edit/{id}','DriverController@edit');
        Route::post('driver/update','DriverController@update');
        Route::post('driver/delete','DriverController@delete');
        Route::get('driver/get-by-id/{id}','DriverController@getById');

        // CUSTOMER
        Route::get('customer','CustomerController@index');
        Route::get('customer/create','CustomerController@create');
        Route::post('customer/insert','CustomerController@insert');
        Route::get('customer/edit/{id}','CustomerController@edit');
        Route::post('customer/update','CustomerController@update');
        Route::post('customer/delete','CustomerController@delete');
        Route::get('customer/create-pekerjaan/{id}','CustomerController@createPekerjaan');
        Route::post('customer/update-pekerjaan','CustomerController@updatePekerjaan');
        Route::post('customer/insert-pekerjaan','CustomerController@insertPekerjaan');
        Route::get('customer/del-pekerjaan/{id}','CustomerController@delPekerjaan');

        // SUPPLIER
        Route::get('supplier','SupplierController@index');
        Route::get('supplier/create','SupplierController@create');
        Route::post('supplier/insert','SupplierController@insert');
        Route::get('supplier/edit/{id}','SupplierController@edit');
        Route::post('supplier/update','SupplierController@update');
        Route::post('supplier/delete','SupplierController@delete');
        Route::get('customer/edit-pekerjaan/{id}','CustomerController@editPekerjaan');

        // // KARYAWAN
        // Route::get('karyawan','KaryawanController@index');
        // Route::get('karyawan/create','KaryawanController@create');
        // Route::post('karyawan/insert','KaryawanController@insert');
        // Route::get('karyawan/edit/{id}','KaryawanController@edit');
        // Route::post('karyawan/update','KaryawanController@update');
        // Route::post('karyawan/delete','KaryawanController@delete');
        // Route::get('karyawan-get-driver-by-id/{id}','KaryawanController@getDriverById');

        // // SUPPLIER
        // Route::get('supplier','SupplierController@index');
        // Route::get('supplier/create','SupplierController@create');
        // Route::post('supplier/insert','SupplierController@insert');
        // Route::get('supplier/edit/{id}','SupplierController@edit');
        // Route::post('supplier/update','SupplierController@update');
        // Route::post('supplier/delete','SupplierController@delete');

        // // CUSTOMER
        // Route::get('customer','CustomerController@index');
        // Route::get('customer/create','CustomerController@create');
        // Route::post('customer/insert','CustomerController@insert');
        // Route::get('customer/edit/{id}','CustomerController@edit');
        // Route::post('customer/update','CustomerController@update');
        // Route::post('customer/delete','CustomerController@delete');
        // Route::get('customer/edit-pekerjaan/{id}','CustomerController@editPekerjaan');
        // Route::get('customer/create-pekerjaan/{id}','CustomerController@createPekerjaan');
        // Route::post('customer/update-pekerjaan','CustomerController@updatePekerjaan');
        // Route::post('customer/insert-pekerjaan','CustomerController@insertPekerjaan');
        // Route::get('customer/del-pekerjaan/{id}','CustomerController@delPekerjaan');

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
        Route::get('pekerjaan/get-by-customer-id/{id}','PekerjaanController@getPekerjaanByPartnerId');

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
        Route::post('search','PembelianController@search');
        Route::get('filter/{filterby}/{val}','PembelianController@filter');
        Route::get('groupby/{val}','PembelianController@groupby');
        Route::get('groupdetail/{groupby}/{id}','PembelianController@groupdetail');
        // Route::get('index','PembelianController@index');
        
    });

    Route::group(['prefix' => 'delivery'], function () {
        // ORDERS
        Route::get('/','DeliveryController@index');
        Route::get('create','DeliveryController@create');
        Route::post('insert','DeliveryController@insert');
        Route::post('update','DeliveryController@update');
        Route::get('show/{id}','DeliveryController@show');
        Route::get('edit/{id}','DeliveryController@edit');
        Route::get('todone/{id}','DeliveryController@toDone');
        Route::get('validate/{id}','DeliveryController@toValidate');
        Route::get('confirm/{id}','DeliveryController@toConfirm');
        Route::post('delete','DeliveryController@delete');
        Route::get('delete-single/{id}','DeliveryController@deleteSingle');
        Route::get('topdf/{id}','DeliveryController@toPdf');
        Route::get('showpdf/{id}','DeliveryController@showPdf');
        Route::get('groupby/{val}','DeliveryController@groupby');
        Route::get('groupdetail/{groupby}/{id}','DeliveryController@groupdetail');
        Route::post('search','DeliveryController@search');
        Route::get('search','DeliveryController@getSearch');
        Route::get('filter/{filterby}/{val}','DeliveryController@filter');
        Route::get('import','DeliveryController@import');
        Route::post('post-import','DeliveryController@postImport');
        Route::get('validate-all','DeliveryController@validateAll');
        // Route::get('edit/{id}','PembelianController@edit');
        // Route::post('update','PembelianController@update');
        // Route::get('validate/{id}','PembelianController@validateIt');
        // Route::get('cancel/{id}','PembelianController@cancelIt');
        // Route::post('delete','PembelianController@delete');
        // Route::get('index','PembelianController@index');
        
    });

    Route::group(['prefix' => 'penjualan'], function () {
        // ORDERS
        Route::get('/','PenjualanController@index');
        Route::get('create','PenjualanController@create');
        Route::post('insert','PenjualanController@insert');
        Route::get('edit/{penjualan_id}','PenjualanController@edit');
        Route::post('update','PenjualanController@update');
        Route::get('validate/{penjualan_id}','PenjualanController@validateIt');
        Route::get('cancel-penjualan/{penjualan_id}','PenjualanController@cancelPenjualan');
        Route::post('delete','PenjualanController@delete');

        // FILTER PENJUALAN
        Route::post('filter','PenjualanController@filter');
        Route::post('cetak-filter','PenjualanController@cetakFilter');
        Route::post('cetak-filter-detail','PenjualanController@cetakFilterDetail');

        // direct sales
        Route::post('insert-direct-sales','PenjualanController@insertDirectSales');
        Route::post('update-direct-sales','PenjualanController@updateDirectSales');
        Route::get('validate-direct-sales/{penjualan_id}','PenjualanController@validateDirectSales');
        Route::get('edit/pengiriman/{penjualan_id}','PenjualanController@editPengiriman');
        Route::post('update/pengiriman','PenjualanController@updatePengiriman');
        // pengiriman
        Route::get('validate-pengiriman/{penjualan_id}','PenjualanController@validatePengiriman');
        Route::get('kalkulasi-pengiriman/{pengiriman_id}','PenjualanController@kalkulasiPengiriman');
        
    });

    Route::group(['prefix' => 'pengiriman'], function () {
        Route::get('/','PengirimanController@index');
        Route::get('edit/{id}','PengirimanController@edit');
        Route::post('update','PengirimanController@update');
        Route::get('open/{pengirian_id}','PengirimanController@open');
        Route::get('validate/{pengiriman_id}','PengirimanController@validateData');
        Route::get('view-validated/{pengiriman_id}','PengirimanController@viewValidated');
        Route::get('view-canceled/{pengiriman_id}','PengirimanController@viewCanceled');
        Route::get('view-done/{pengiriman_id}','PengirimanController@viewDone');
        Route::post('batch-edit','PengirimanController@batchEdit');
        Route::post('batch-edit-save','PengirimanController@batchEditSave');
        Route::post('batch-edit-save-validate','PengirimanController@batchEditSaveValidate');
        
    });

    Route::group(['prefix' => 'kalkulasi'], function () {
        // ORDERS
        Route::get('/','KalkulasiController@index');
        Route::get('edit/{kalkulasi_id}','KalkulasiController@edit');
        Route::get('st-draft/{kalkulasi_id}','KalkulasiController@stDraft');
        Route::get('st-open/{kalkulasi_id}','KalkulasiController@stOpen');
        Route::get('st-canceled/{kalkulasi_id}','KalkulasiController@stCanceled');
        Route::get('st-validated/{kalkulasi_id}','KalkulasiController@stValidated');
        Route::post('update','KalkulasiController@update');
        Route::get('validate/{kalkulasi_id}','KalkulasiController@validateKalkulasi');
        Route::post('batch-edit','KalkulasiController@batchEdit');
        Route::post('batch-edit-by-pekerjaan','KalkulasiController@batchEditByPekerjaan');
        Route::post('batch-edit-update','KalkulasiController@batchEditUpdate');
        Route::get('filter-by-status/{status}','KalkulasiController@filterByStatus');
        
    });

    Route::group(['prefix' => 'tagihan-customer'], function () {
        // ORDERS
        Route::get('/','TagihanCustomerController@index');
        Route::get('get-pekerjaan/{customer_id}','TagihanCustomerController@getPekerjaan');
        Route::get('filter-by-status/{status}','TagihanCustomerController@filterByStatus');
        Route::get('cetak-tagihan','TagihanCustomerController@cetakTagihan');
        Route::post('batch-edit','TagihanCustomerController@batchEdit');
        Route::post('get-data-tagihan','TagihanCustomerController@dataTagihan');
        Route::post('get-data-tagihan-by-material','TagihanCustomerController@dataTagihanByMaterial');
       
        
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

    Route::group(['prefix' => 'presensi'], function () {
        Route::get('/','PresensiController@index');
        Route::get('get-presensi-by-bulan/{bulan}','PresensiController@getPresensiByBulan');
    });

    Route::group(['prefix' => 'attendance'], function () {
        // SETTING
        Route::get('setting','AttendanceController@setting');
        Route::post('update-time-setting','AttendanceController@updateTimeSetting');
        Route::post('setting/insert-holiday','AttendanceController@insertHoliday');
        Route::get('setting/delete-holiday/{holiday_id}','AttendanceController@deleteHoliday');
        // ATTENDANCE
        Route::get('attend','AttendanceController@attend');
        Route::get('attend/{val}','AttendanceController@attend');
        Route::post('attend/insert','AttendanceController@insertAttend');
        Route::post('get-attendance-table','AttendanceController@getAttendanceTable');
    });

    // Route::group(['prefix' => 'payroll'], function () {




    //     // // Default Payroll System
    //     // Route::get('/','PayrollController@index');
    //     // Route::get('/{val}','PayrollController@index');
    //     // Route::get('addnew','PayrollController@addnew');
    //     // Route::get('payroll/show-payroll-table/{tanggal}/{kode_jabatan}','PayrollController@showPayrollTable');
    //     // Route::post('payroll/get-pay-day','PayrollController@getPayDay');
        

    //     // // PAYROLL STAFF
    //     // Route::get('payroll-staff/show-payroll-table/{tanggal}','PayrollStaffController@showPayrollTable');
    //     // Route::get('payroll-staff/pay/{karyawan_id}/{tanggal}','PayrollStaffController@addPay');
    //     // Route::get('payroll-staff/edit-pay/{payroll_id}','PayrollStaffController@editPay');
    //     // Route::post('payroll-staff/insert-pay','PayrollStaffController@insertPay');
    //     // Route::post('payroll-staff/update-pay','PayrollStaffController@updatePay');
    //     // Route::get('payroll-staff/validate-pay/{payroll_id}','PayrollStaffController@validatePay');
    //     // Route::get('payroll-staff/reset/{payroll_id}','PayrollStaffController@resetPay');
    //     // Route::get('payroll-staff/print-pdf/{payroll_id}','PayrollStaffController@printPdf');
    //     // Route::get('payroll-staff/print-copy/{payroll_id}','PayrollStaffController@printCopy');
    //     // Route::get('payroll-staff/print-direct/{payroll_id}','PayrollStaffController@printDirect');


    //     // // PAYROLL DRIVER
    //     // Route::get('driver/show-payroll-table/{tanggal}','PayrollDriverController@showPayrollTable');
    //     // Route::get('driver/pay/{karyawan_id}/{tanggal}','PayrollDriverController@addPay');
    //     // Route::get('driver/edit-pay/{payroll_id}','PayrollDriverController@editPay');
    //     // Route::post('driver/insert-pay','PayrollDriverController@insertPay');
    //     // Route::post('driver/update-pay','PayrollDriverController@updatePay');
    //     // Route::get('driver/validate-pay/{payroll_id}','PayrollDriverController@validatePay');
    //     // Route::get('driver/reset/{payroll_id}','PayrollDriverController@resetPay');
    //     // Route::get('driver/print-pdf/{payroll_id}','PayrollDriverController@printPdf');
    //     // Route::get('driver/print-copy/{payroll_id}','PayrollDriverController@printCopy');
    //     // Route::get('driver/print-direct/{payroll_id}','PayrollDriverController@printDirect');

    //     // // PAYROLL STAFF
    //     // // Route::get('staff','PayrollDriverController@staff');

    //     // // PAYROLL DRIVER
    //     // Route::get('driver','PayrollDriverController@driver');
    //     // Route::get('driver/create','PayrollDriverController@driverCreate');
    //     // Route::post('driver/insert','PayrollDriverController@insert');
    //     // Route::post('driver/update','PayrollDriverController@update');
    //     // Route::get('driver/delete/{payroll_id}','PayrollDriverController@deletePayroll');
    //     // Route::get('driver/edit/{payroll_id}','PayrollDriverController@edit');
    //     // Route::get('driver/validate/{payroll_id}','PayrollDriverController@validatePayroll');
    //     // Route::get('driver/cancel-payroll/{payroll_id}','PayrollDriverController@cancelPayroll');
    //     // Route::get('driver/get-delivery-order/{driver_id}/{start_date}/{end_date}','PayrollDriverController@getDeliveryOrderList');

    //     // // PAYROLL STAFF
    //     // Route::get('staff','PayrollStaffController@index');
    //     // Route::get('staff/create','PayrollStaffController@create');
    //     // Route::get('staff/edit/{payroll_id}','PayrollStaffController@edit');
    //     // Route::post('staff/insert','PayrollStaffController@insert');
    //     // Route::post('staff/update','PayrollStaffController@update');
    //     // Route::get('staff/get-attendance/{staff_id}/{awal}/{akhir}','PayrollStaffController@getAttendance');
    //     // Route::get('staff/get-workday/{staff_id}/{awal}/{akhir}','PayrollStaffController@getWorkday');
    //     // Route::get('staff/validate/{payroll_id}','PayrollStaffController@validatePayroll');
    //     // Route::get('staff/cancel-payroll/{payroll_id}','PayrollStaffController@cancelPayroll');

    // });

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
        // REPORT PEMBELIAN
        Route::group(['prefix' => 'pembelian'], function () {
            Route::get('/','ReportPembelianController@index');
            Route::post('default-report','ReportPembelianController@defaultReport');
            Route::post('group-report','ReportPembelianController@groupReport');
            Route::post('submit','ReportPembelianController@submit');
            Route::post('submit-pdf','ReportPembelianController@submitPdf');
            Route::post('submit-excel','ReportPembelianController@submitExcel');
        });

        // REPORT PENGIRIMAN
        Route::get('pengiriman','ReportPengirimanController@index');
        Route::post('pengiriman/default-report','ReportPengirimanController@defaultReport');
        Route::post('pengiriman/group-report','ReportPengirimanController@groupReport');
        Route::post('pengiriman/group-report-inline','ReportPengirimanController@groupReportInline');
        Route::post('pengiriman/group-detail-report','ReportPengirimanController@groupDetailReport');
        Route::post('pengiriman/group-detail-report-inline','ReportPengirimanController@groupDetailReportInline');
        Route::get('pengiriman/test','ReportPengirimanController@testExel');
        Route::post('pengiriman/group-detail-report-excel','ReportPengirimanController@groupDetailReportExcel');
        Route::post('pengiriman/group-report-excel','ReportPengirimanController@groupReportExcel');
        // Route::post('pengiriman/group-report','ReportPengirimanController@groupReport');

        // REPORT HUTANG
        Route::group(['prefix' => 'hutang'], function () {
            Route::get('/','ReportHutangController@index');
            Route::post('submit','ReportHutangController@submit');
            Route::post('submit-pdf','ReportHutangController@submitPdf');
            Route::post('submit-excel','ReportHutangController@submitExcel');
        });

        // REPORT PIUTANG
        Route::group(['prefix' => 'piutang'], function () {
            Route::get('/','ReportPiutangController@index');
            Route::post('submit','ReportPiutangController@submit');
            Route::post('submit-pdf','ReportPiutangController@submitPdf');
            Route::post('submit-excel','ReportPiutangController@submitExcel');
        });

        // REPORT DAILYHD
        Route::group(['prefix' => 'dailyhd'], function () {
            Route::get('/','ReportDailyhdController@index');
            Route::post('submit','ReportDailyhdController@submit');
            Route::post('submit-pdf','ReportDailyhdController@submitPdf');
            Route::post('submit-excel','ReportDailyhdController@submitExcel');
        });

        // REPORT PRESENSI
        Route::group(['prefix' => 'presensi'], function () {
            Route::get('/','ReportPresensiController@index');
            Route::post('submit','ReportPresensiController@submit');
            Route::post('submit-pdf','ReportPresensiController@submitPdf');
            Route::post('submit-excel','ReportPresensiController@submitExcel');
        });


        // // REPORT PURCHASE
        // Route::get('purchase','ReportPurchaseController@index');
        // Route::post('purchase/filter-by-date','ReportPurchaseController@filterByDate');
        // Route::post('purchase/filter-by-date-n-supplier','ReportPurchaseController@filterByDateNSupplier');
        // Route::get('purchase/filter-by-date/pdf/{start}/{end}','ReportPurchaseController@filterByDateToPdf');

        // // REPORT SALES
        // Route::get('sales','ReportSalesController@index');
        // Route::get('sales/get-pekerjaan-by-customer/{customer_id}','ReportSalesController@getPekerjaanByCustomer');
        // Route::post('sales/report-by-date','ReportSalesController@reportByDate');
        // Route::post('sales/report-by-date-detail','ReportSalesController@reportByDateDetail');
        // Route::post('sales/report-by-customer','ReportSalesController@reportByCustomer');
        // Route::post('sales/report-by-customer-pekerjaan','ReportSalesController@reportByCustomerPekerjaan');
        // Route::post('sales/report-by-customer-detail','ReportSalesController@reportByCustomerDetail');
        // Route::post('sales/report-by-lokasi-galian','ReportSalesController@reportByLokasiGalian');
        // Route::post('sales/report-by-sales-type','ReportSalesController@reportBySalesType');
        // Route::post('sales/report-by-sales-type-all','ReportSalesController@reportBySalesTypeAll');

        // //  REPORT DELIVERY
        // Route::get('delivery','ReportDeliveryController@index');
        // Route::post('delivery/report-by-date','ReportDeliveryController@reportByDate');
        // Route::post('delivery/report-by-customer','ReportDeliveryController@reportByCustomer');
        // Route::post('delivery/report-by-lokasi-galian','ReportDeliveryController@reportByLokasiGalian');
        // Route::post('delivery/report-by-driver','ReportDeliveryController@reportByDriver');
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


    // essentioal
    // generate recordset provinsi, kabupaten & kecamatan untuk odoo
    Route::get('generate-provinsi-rec',function(){
        $prov = \DB::table('provinsi')->get();
        $rownum = 1;
        $provinsi_id=1;
        $kabupaten_id=1;
        $kecamatan_id=1;
        foreach($prov as $dt){
            // echo '<!--PROVINSI : '.$dt->name.'-->';
            echo '<record id="data_provinsi_'. $rownum++ .'" model="udhasman.provinsi">
                        <field name="id">'.$provinsi_id.'</field>
                        <field name="name">'.$dt->name.'</field>
                    </record>'; 

            // GET DATA KABUPATEN
            // echo '<!--DAFTAR KABUPATEN PROVINSI : '.$dt->name.' -->';
            $kabupatens = \DB::table('kabupaten')->where('provinsi_id',$dt->id)->get();
            foreach($kabupatens as $kab){
                echo '<record id="data_kabupaten_'. $rownum++ .'" model="udhasman.kabupaten">
                        <field name="id">'.$kabupaten_id.'</field>
                        <field name="name">'.str_replace('Kabupaten', 'Kab.', $kab->name).'</field>
                        <field name="provinsi_id">'.$provinsi_id.'</field>
                    </record>'; 

                // GET DATA KECAMATAN
                // echo '<!--DAFTAR KECAMATAN KABUPATEN : '.$dt->name.' -->';
                $kecamatans = \DB::table('kecamatan')->where('kabupaten_id',$kab->id)->get();
                foreach($kecamatans as $kec){
                    echo '<record id="data_kecamatan_'. $rownum++ .'" model="udhasman.kecamatan">
                            <field name="id">'.$kecamatan_id.'</field>
                            <field name="name">'.$kec->name.'</field>
                            <field name="kabupaten_id">'.$kabupaten_id.'</field>
                        </record>'; 

                    $kecamatan_id++;
                }
                $kabupaten_id++;
            }
            $provinsi_id++;
        }
    });


    Route::group(['prefix'=>'gaji'],function(){
        Route::get('driver','GajiController@indexDriver');
        Route::get('driver/add','GajiController@driverAdd');
        Route::get('driver/show-payroll-table/{tanggal}','GajiController@showPayrollTable');
        Route::post('driver/get-pay-day','GajiController@getPayDay');
        Route::get('driver/edit-pay/{payroll_id}','GajiController@editPay');
        Route::get('driver/pay/{karyawan_id}/{tanggal}','GajiController@addPay');
        Route::get('driver/get-payroll-at-month/{bulan}','GajiController@getPayrollAtMonth');
        Route::get('driver/get-pengiriman/{karyawanid}/{tanggal_awal}/{tanggal_akhir}/{pay_id}','GajiController@getPengiriman');
        Route::post('driver/save-pay','GajiController@savePay');

        // Gaji Driver Revisi
        Route::group(['prefix'=>'gaji-driver'],function(){
            Route::get('','GajiDriverController@index');
            Route::get('create','GajiDriverController@create');
            Route::post('save','GajiDriverController@save');
            Route::get('edit/{id}','GajiDriverController@edit');
            Route::post('get-pay-day','GajiDriverController@getPayDay');
            Route::post('update','GajiDriverController@update');
            Route::get('confirm/{id}','GajiDriverController@confirm');
            Route::get('delete/{id}','GajiDriverController@delete');
            Route::post('save-payment','GajiDriverController@savePayment');
            Route::get('validate/{id}','GajiDriverController@toValidate');
            Route::get('print-pdf/{id}','GajiDriverController@printPdf');
            Route::get('get-payment-info/{payment_id}','GajiDriverController@getPaymentInfo');
            Route::get('delete-dp/{payment_id}','GajiDriverController@deleteDP');
            Route::get('to-cancel/{id}','GajiDriverController@toCancel');
            Route::get('payment-to-print/{payment_id}','GajiDriverController@paymentToPrint');
            Route::get('search', 'GajiDriverController@getSearch');
            Route::get('filter/{filterby}/{val}','GajiDriverController@filter');
            Route::get('group-by/{val}','GajiDriverController@groupby');
            Route::get('group-detail/{groupby}/{id}','GajiDriverController@groupdetail');
        });

        // Gaji Driver Revisi
        Route::group(['prefix'=>'gaji-staff'],function(){
            Route::get('','GajiStaffController@index');
            Route::get('create','GajiStaffController@create');
            Route::post('save','GajiStaffController@save');
            Route::get('edit/{id}','GajiStaffController@edit');
            Route::post('update','GajiStaffController@update');
            Route::get('confirm/{id}','GajiStaffController@confirm');
            Route::get('delete/{id}','GajiStaffController@delete');
            Route::get('validate/{id}','GajiStaffController@toValidate');
            Route::post('save-payment','GajiStaffController@savePayment');
            Route::get('get-payment-info/{payment_id}','GajiStaffController@getPaymentInfo');
            Route::get('delete-dp/{payment_id}','GajiStaffController@deleteDP');
            Route::get('to-cancel/{id}','GajiStaffController@toCancel');
            Route::get('print-pdf/{id}','GajiStaffController@printPdf');
            Route::get('payment-to-print/{payment_id}','GajiStaffController@paymentToPrint');
            
        });

    });



});
