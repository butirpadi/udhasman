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
            body{
                font-family: sans-serif;

            }
        </style>        
    </head>
    <body  >
        <div style="width: 100%;text-align: center;border: solid thin black;padding: 5px;" >
            <strong style="margin: 0;padding: 0;font-size: 11px;" >{{Appsetting('company_name')}}</strong>
            <h4 style="margin: 0;padding: 0;font-size: 12px;" >BUKTI KAS MASUK</h4>
        </div>
        <table style="width: 100%;padding:5px;font-size: 11px;border-left:thin solid black;border-right:thin solid black;padding-top: 15px;padding-bottom: 15px;" >
            <tbody>
                <tr>
                    <td style="vertical-align: top;width: 20%;" >
                        <strong>Diterima dari</strong>
                    </td>
                    <td style="width:2%;vertical-align: top;" >:</td>
                    <td style="width:38%;vertical-align: top;" >
                        {{$data->partner}}
                    </td>

                    <td style="width: 10%;" >
                        <strong>Tanggal</strong>
                    </td>
                    <td style="width: 2%;" >:</td>
                    <td style="width: 28%;" >
                        {{$data->tanggal_format}}
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;" >
                        <strong>Keterangan</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;" rowspan="2" >
                        Pembayaran Piutang Dagang:<br/>
                        {{$data->memo}}
                    </td>
                    <td style="" >
                        <strong>Ref#</strong>
                    </td>
                    <td style="" >:</td>
                    <td style="" >
                        {{$data->name}}
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
            </tbody>
        </table>    
        <table class="table-product" style="font-size: 10px;" >
            <tbody>
                <tr>
                    <td style="width: 35%;text-align: center;padding: 0;padding-bottom: 10px;" >
                        <p>Diterima,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(__________________)</strong>
                    </td>
                    <td style="width: 35%;text-align: center;padding: 0;" >
                        
                    </td>
                    <td style="width: 30%;text-align: center;padding: 0;" >
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
        <br/>


        <!-- Invoice Copy -->
        <div style="width: 100%;text-align: center;border: solid thin black;padding: 5px;" >
            <strong style="margin: 0;padding: 0;font-size: 11px;" >{{Appsetting('company_name')}}</strong>
            <h4 style="margin: 0;padding: 0;font-size: 12px;" >BUKTI KAS MASUK (COPY)</h4>
        </div>
        <table style="width: 100%;padding:5px;font-size: 11px;border-left:thin solid black;border-right:thin solid black;padding-top: 15px;padding-bottom: 15px;" >
            <tbody>
                <tr>
                    <td style="vertical-align: top;width: 20%;" >
                        <strong>Diterima dari</strong>
                    </td>
                    <td style="width:2%;vertical-align: top;" >:</td>
                    <td style="width:38%;vertical-align: top;" >
                        {{$data->partner}}
                    </td>

                    <td style="width: 10%;" >
                        <strong>Tanggal</strong>
                    </td>
                    <td style="width: 2%;" >:</td>
                    <td style="width: 28%;" >
                        {{$data->tanggal_format}}
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;" >
                        <strong>Keterangan</strong>
                    </td>
                    <td style="vertical-align: top;" >:</td>
                    <td style="vertical-align: top;" rowspan="2" >
                        Pembayaran Piutang Dagang:<br/>
                        {{$data->memo}}
                    </td>
                    <td style="" >
                        <strong>Ref#</strong>
                    </td>
                    <td style="" >:</td>
                    <td style="" >
                        {{$data->name}}
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
            </tbody>
        </table>    
        <table class="table-product" style="font-size: 10px;" >
            <tbody>
                <tr>
                    <td style="width: 35%;text-align: center;padding: 0;padding-bottom: 10px;" >
                        <p>Diterima,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>(__________________)</strong>
                    </td>
                    <td style="width: 35%;text-align: center;padding: 0;" >
                        
                    </td>
                    <td style="width: 30%;text-align: center;padding: 0;" >
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
