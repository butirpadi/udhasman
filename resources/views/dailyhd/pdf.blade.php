<!DOCTYPE html>
<html>
    <head>
        <base href="{{ URL::to('/') }}/" />
        <title>.:: Form Operasional Alat Berat ::.</title>
        <style>
            table.table-product {
                border-collapse: collapse;
              }
            table.table-product th, table.table-product td {
                border:0.5px solid black;
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
                    <td style="width: 30%;" >
                        <strong>{{Appsetting('company_name')}}</strong><br/>
                        {{Appsetting('alamat_1')}}<br/>
                        {{Appsetting('alamat_2')}}<br/>
                        Phone. {{Appsetting('telp')}}
                    </td>
                    <td style="margin:0;padding:0;width: 55%;text-align: right;" >
                        <h3 style="margin:0;padding:0;">FORM OPERASIONAL ALAT BERAT</h3>
                        <p style="margin:0;padding:0;" >{{$data->ref}}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <br/>
        <br/>
        <br/>
        <table style="width: 100%;" class="table-product">
            <tbody>
                <tr>
                    <td style="width: 20%;" >
                        <strong>Tanggal</strong>
                    </td>
                    <td style="width: 30%;"  >
                        {{$data->tanggal_format}}
                    </td>

                    <td style="width: 20%;" >
                        <strong>Jam Kerja</strong>
                    </td>
                    <td style="width: 30%;">
                        {{$data->mulai . ' - ' .$data->selesai}}
                    </td>
                    
                </tr>
                <tr>
                    <td  >
                        <strong>Lokasi Galian</strong>
                    </td>
                    <td  >
                        {{$data->lokasi_galian}}
                    </td>
                    
                    <td >
                        <strong>Istirahat</strong>
                    </td>
                    <td >
                        {{$data->istirahat_mulai . ' - ' .$data->istirahat_selesai}}
                    </td> 
                    
                </tr>

                <tr>
                    <td  >
                        <strong>Alat</strong>
                    </td>
                    <td  >
                        {{$data->kode_alat . ' - ' . $data->alat}}
                    </td>
                                       
                    <td  >
                        <strong>Total Jam Kerja</strong>
                    </td>
                    <td align="right" >
                        {{$data->jam_kerja}}
                    </td>
                </tr>

                <tr>
                    <td  >
                        <strong>Pengawas</strong>
                    </td>
                    <td  >
                        {{$data->kode_pengawas . ' - ' .$data->pengawas}}
                    </td>
                    <td >
                        <strong>Konsumsi Solar (liter)</strong>
                    </td>
                    <td align="right" >
                        {{$data->solar}}
                    </td>
                    
                </tr>
                <tr>
                    <td  >
                        <strong>Operator</strong>
                    </td>
                    <td  >
                        {{$data->kode_operator . ' - ' .$data->operator}}
                    </td>

                    
                    <td >
                        <strong>Konsumsi Oli (liter)</strong>
                    </td>
                    <td align="right" >
                        {{$data->oli}}
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="height: 100px;" >
                        <strong>Keterangan:</strong>
                        {{$data->desc}}
                    </td>
                </tr>
                
                
            </tbody>
        </table>    
        <br/>

        <table  style="width: 100%;" >
            <tbody>
                <tr>
                    <td style="width: 50%;text-align: center;padding: 0;padding-bottom: 10px;" >
                        <p>Pengawas,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>{{$data->pengawas}}</strong>
                    </td>
                    <td style="width: 50%;text-align: center;padding: 0;" >
                        <p>Operator,</p>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <strong>{{$data->operator}}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
