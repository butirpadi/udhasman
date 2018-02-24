    <!DOCTYPE html>
<html>
    <head>
        <title>{{$data->in_out == 'I' ? 'BUKTI KAS MASUK' : 'BUKTI KAS KELUAR'}}</title>
        <base href="{{ URL::to('/') }}/" />
        <style>
        body{           
            font-size: 13px;
        }
        table{
            font-size: inherit;
        }
        table.table-product, table.table-total {
            border-collapse: collapse;
          }
        table.table-product thead, table.table-product tbody{
            border:0.5px solid black;
        }
        table.table-product th{
            padding: 2px;
        }
        table.table-product td {
            /*border:0.5px solid black;*/
            padding: 0px;
            text-align: left;
          }
        /*table.table-product th{
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
        }*/
        table.table-product td {
            padding: 5px;
            vertical-align: top;
        }
        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }
        .header {
            top: 0px;
        }
        .footer {
            bottom: 0px;
        }
        .pagenum:before {
            content: counter(page);
        }
        
        table.table-detail tr > td, table.table-detail tr > th {
            padding: 0;
        }
    </style>         
    </head>
    <body  >
        <table class="table-product" style="width: 100%;padding:5px;font-size: 11px;" >
            <thead>
                <tr style="height: 50px;">
                    <th colspan="6" >
                        <strong style="margin: 0;padding: 0;font-size: 11px;" >{{strtoupper(Appsetting('company_name'))}}</strong>
                        <h4 style="margin: 0;padding: 0;font-size: 12px;" >{{$data->in_out == 'I' ? 'BUKTI KAS MASUK' : 'BUKTI KAS KELUAR'}}</h4>            
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6" style="height: 10px;" ></td></tr>
                <tr>
                    <td style="vertical-align: top;" >
                        <strong>{{$data->in_out == 'I' ? 'Diterima dari' : 'Dibayar kepada'}}</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;" >
                        ...........................................................
                    </td>

                    <td style="" >
                        <strong>Tanggal</strong>
                    </td>
                    <td style="" >:</td>
                    <td style="" >
                        {{$data->tanggal_format}}
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;" >
                        <strong>Keterangan</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;width: 30%;" rowspan="2" >
                        {{$data->desc}}
                    </td>
                    <td style="" >
                        <strong>#</strong>
                    </td>
                    <td style="" >:</td>
                    <td style="" >
                        {{$data->cash_number}}
                    </td>
                </tr>
                <tr>
                    <td style="" >
                        
                    </td>
                    <td style="" ></td>
                    
                    <td style="vertical-align: top;" >
                        <strong>Jumlah</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;" >
                        {{number_format($data->jumlah,2,'.',',')}}
                    </td>
                </tr>
                <tr>
                    <td colspan="6" >
                        <strong>Terbilang: </strong>
                        <i>{{ucwords($data->terbilang)}} Rupiah</i>
                    </td>
                </tr>
                <tr><td colspan="6" style="height: 10px;" ></td></tr>
                <tr style="border-top: 0.5px solid black;" >
                    <td colspan="3" style="text-align: center;padding: 0;padding-bottom: 10px;" >
                        <p>Diterima,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(__________________)</strong>
                    </td>
                    <td colspan="3" style="text-align: center;padding: 0;" >
                        <p>Dibayar,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(__________________)</strong>
                    </td>
                </tr>
            </tbody>
        </table>    
        
        <hr style="border:none;border-top: 1px dashed #8c8b8b;margin-bottom: 20px;margin-top: 20px;">
        
        <table class="table-product" style="width: 100%;padding:5px;font-size: 11px;" >
            <thead>
                <tr style="height: 50px;">
                    <th colspan="6" >
                        <strong style="margin: 0;padding: 0;font-size: 11px;" >{{strtoupper(Appsetting('company_name'))}}</strong>
                        <h4 style="margin: 0;padding: 0;font-size: 12px;" >{{$data->in_out == 'I' ? 'BUKTI KAS MASUK' : 'BUKTI KAS KELUAR'}}</h4>            
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6" style="height: 10px;" ></td></tr>
                <tr>
                    <td style="vertical-align: top;" >
                        <strong>{{$data->in_out == 'I' ? 'Diterima dari' : 'Dibayar kepada'}}</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;" >
                        ...........................................................
                    </td>

                    <td style="" >
                        <strong>Tanggal</strong>
                    </td>
                    <td style="" >:</td>
                    <td style="" >
                        {{$data->tanggal_format}}
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;" >
                        <strong>Keterangan</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;width: 30%;" rowspan="2" >
                        {{$data->desc}}
                    </td>
                    <td style="" >
                        <strong>#</strong>
                    </td>
                    <td style="" >:</td>
                    <td style="" >
                        {{$data->cash_number}}
                    </td>
                </tr>
                <tr>
                    <td style="" >
                        
                    </td>
                    <td style="" ></td>
                    
                    <td style="vertical-align: top;" >
                        <strong>Jumlah</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;" >
                        {{number_format($data->jumlah,2,'.',',')}}
                    </td>
                </tr>
                <tr>
                    <td colspan="6" >
                        <strong>Terbilang: </strong>
                        <i>{{ucwords($data->terbilang)}} Rupiah</i>
                    </td>
                </tr>
                <tr><td colspan="6" style="height: 10px;" ></td></tr>
                <tr style="border-top: 0.5px solid black;" >
                    <td colspan="3" style="text-align: center;padding: 0;padding-bottom: 10px;" >
                        <p>Diterima,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(__________________)</strong>
                    </td>
                    <td colspan="3" style="text-align: center;padding: 0;" >
                        <p>Dibayar,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(__________________)</strong>
                    </td>
                </tr>
            </tbody>
        </table>    



    </body>
</html>
