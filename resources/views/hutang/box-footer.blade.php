<div class="box-footer" >
    <div class="row" >
        <div class="col-xs-3" >
            <i>Showing {{($data->currentpage()-1)*$data->perpage()+1}} to {{(($data->currentpage()-1)*$data->perpage())+$data->count()}} of {{$data->total()}} entries</i>
        </div>
        <div class="col-xs-4" >
            <div class="pull-right" >
                {{ $data->links() }}
            </div>  
        </div>
        <div class="col-xs-5" >
            <table style="font-size: 13pt!important;" class="pull-right" >
                <tr>
                    <td class="col-xs-6 text-right" >
                        <label><b>SALDO HUTANG :</b></label>
                    </td>
                    <td class="text-right col-xs-6"   >
                        <label class="uang">{{number_format($sum_amount_due,2,'.',',')}}</label>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>