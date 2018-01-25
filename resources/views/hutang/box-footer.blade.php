<div class="box-footer" >
    <div class="pull-right" >
        {{ $data->links() }}
    </div>
    <table style="font-size: 13pt!important;" >
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