<!DOCTYPE html>
<html>
    <head>
        <base href="{{ URL::to('/') }}/" />
        <style>
            table.table-product {
                border-collapse: collapse;
              }
            table.table-product th, table.table-product td {
                border: 1px solid black;
                padding: 10px;
                text-align: left;
              }
            table.table-product td {
                padding: 5px;
                vertical-align: top;
            }
        </style>        
    </head>
    <body >
        <table style="width: 100%;" >
            <tbody>
                <tr>
                    <td style="width: 15%;" >
                        <img src="img/logo.png" style="width: 90%;" />
                    </td>
                    <td style="width: 50%;" >
                        <strong>{{Appsetting('company_name')}}</strong><br/>
                        {{Appsetting('alamat_1')}}<br/>
                        {{Appsetting('alamat_2')}}<br/>
                        Phone. {{Appsetting('telp')}}
                    </td>
                    <td style="margin:0;padding:0;width: 35%;text-align: right;" >
                        <h3 style="margin:0;padding:0;">SURAT JALAN</h3>
                        <p style="margin:0;padding:0;" >{{$pengiriman->name}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <br/>
        <br/>
        <br/>
        <table style="width: 100%;" >
            <tbody>
                <tr>
                    <td style="width: 14%;vertical-align: top;" >
                        <strong>Kepada</strong>
                    </td>
                    <td style="width: 1%;vertical-align: top;" >:</td>
                    <td style="width: 35%;vertical-align: top;" >
                        {{$pengiriman->customer}}<br/>
                    </td>

                    <td style="width: 14%;" >
                        <strong>Tanggal</strong>
                    </td>
                    <td style="width: 1%;" >:</td>
                    <td style="width: 35%;" >
                        {{$pengiriman->order_date_format}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 14%;vertical-align: top;" >
                        
                    </td>
                    <td style="width: 1%;vertical-align: top;" ></td>
                    <td style="width: 35%;vertical-align: top;" >
                        {{$pengiriman->pekerjaan}}
                    </td>
                    <td style="width: 14%;" >
                        <strong>Nopol</strong>
                    </td>
                    <td style="width: 1%;" >:</td>
                    <td style="width: 35%;" >
                        {{$pengiriman->nopol}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 14%;vertical-align: top;" >
                    </td>
                    <td style="width: 1%;vertical-align: top;" ></td>
                    <td style="width: 35%;vertical-align: top;" >
                        @if($alamat)
                        {{$alamat->alamat . ($alamat->desa !=''?', ':'') . $alamat->desa . ($alamat->kecamatan !=''?', ':'') . $alamat->kecamatan }}
                        @endif
                    </td>
                    <td style="width: 14%;" >
                        
                    </td>
                    <td style="width: 1%;" ></td>
                    <td style="width: 35%;" >
                        
                    </td>
                </tr>
            </tbody>
        </table>    
        <br/>
        <br/>
        <br/>
        <table style="width: 100%;"  class="table-product">
            <thead>
                <tr>
                    <th style="width: 90%;" >
                        MATERIAL
                    </th>
                    <th style="width: 10%;text-align: center;">
                        QTY
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr  >
                    <td style="height: 60px;" >
                        {{$pengiriman->material}}
                    </td>
                    <td style="text-align: center;" >1</td>
                </tr>
            </tbody>
        </table>
        <br/>

        <table class="table-product" >
            <tbody>
                <tr>
                    <td style="width: 35%;text-align: center;padding: 0;padding-bottom: 10px;" >
                        <p>Dibuat,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(_______________________)</strong>
                    </td>
                    <td style="width: 35%;text-align: center;padding: 0;" >
                        <p>Sopir,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>{{$pengiriman->karyawan}}</strong>
                    </td>
                    <td style="width: 30%;text-align: center;padding: 0;" >
                        <p>Diterima,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(_______________________)</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        @if(Appsetting('surat_jalan_copy') == 'Y')
        <br />
        <br />
        <hr style="border:0.5px dashed black;">
        <br/>
        <br/>
        <table style="width: 100%;" >
            <tbody>
                <tr>
                    <td style="width: 15%;" >
                        <img src="img/logo.png" style="width: 90%;" />
                    </td>
                    <td style="width: 50%;" >
                        <strong>{{Appsetting('company_name')}}</strong><br/>
                        {{Appsetting('alamat_1')}}<br/>
                        {{Appsetting('alamat_2')}}<br/>
                        Phone. {{Appsetting('telp')}}
                    </td>
                    <td style="margin:0;padding:0;width: 35%;text-align: right;" >
                        <h3 style="margin:0;padding:0;">SURAT JALAN COPY</h3>
                        <p style="margin:0;padding:0;" >{{$pengiriman->name}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <br/>
        <br/>
        <br/>
        <table style="width: 100%;" >
            <tbody>
                <tr>
                    <td style="width: 14%;vertical-align: top;" >
                        <strong>Kepada</strong>
                    </td>
                    <td style="width: 1%;vertical-align: top;" >:</td>
                    <td style="width: 35%;vertical-align: top;" >
                        {{$pengiriman->customer}}<br/>
                    </td>

                    <td style="width: 14%;" >
                        <strong>Tanggal</strong>
                    </td>
                    <td style="width: 1%;" >:</td>
                    <td style="width: 35%;" >
                        {{$pengiriman->order_date_format}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 14%;vertical-align: top;" >
                        
                    </td>
                    <td style="width: 1%;vertical-align: top;" ></td>
                    <td style="width: 35%;vertical-align: top;" >
                        {{$pengiriman->pekerjaan}}
                    </td>
                    <td style="width: 14%;" >
                        <strong>Nopol</strong>
                    </td>
                    <td style="width: 1%;" >:</td>
                    <td style="width: 35%;" >
                        {{$pengiriman->nopol}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 14%;vertical-align: top;" >
                    </td>
                    <td style="width: 1%;vertical-align: top;" ></td>
                    <td style="width: 35%;vertical-align: top;" >
                        @if($alamat)
                        {{$alamat->alamat . ($alamat->desa !=''?', ':'') . $alamat->desa . ($alamat->kecamatan !=''?', ':'') . $alamat->kecamatan }}
                        @endif
                    </td>
                    <td style="width: 14%;" >
                        
                    </td>
                    <td style="width: 1%;" ></td>
                    <td style="width: 35%;" >
                        
                    </td>
                </tr>
            </tbody>
        </table>    
        <br/>
        <br/>
        <br/>
        <table style="width: 100%;"  class="table-product">
            <thead>
                <tr>
                    <th style="width: 90%;" >
                        MATERIAL
                    </th>
                    <th style="width: 10%;text-align: center;">
                        QTY
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr  >
                    <td style="height: 60px;" >
                        {{$pengiriman->material}}
                    </td>
                    <td style="text-align: center;" >1</td>
                </tr>
            </tbody>
        </table>
        <br/>

        <table class="table-product" >
            <tbody>
                <tr>
                    <td style="width: 35%;text-align: center;padding: 0;padding-bottom: 10px;" >
                        <p>Dibuat,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(_______________________)</strong>
                    </td>
                    <td style="width: 35%;text-align: center;padding: 0;" >
                        <p>Sopir,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>{{$pengiriman->karyawan}}</strong>
                    </td>
                    <td style="width: 30%;text-align: center;padding: 0;" >
                        <p>Diterima,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(_______________________)</strong>
                    </td>
                </tr>
            </tbody>
        </table>
        @endif

    </body>
</html>
