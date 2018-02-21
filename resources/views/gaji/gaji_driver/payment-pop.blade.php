<style>
	.table.table-payment-info tbody tr td{
		border:none;
		padding:0;
		margin:0;
	}
</style>

<table class="table table-condensed table-payment-info" >
	<tbody>
		<tr>
			<td>
				<label>Ref#:</label>
			</td>
			<td>
				&nbsp;&nbsp;{{$data->name}}
			</td>
		</tr>
		<tr>
			<td>
				<label>Tanggal:</label>
			</td>
			<td>
				&nbsp;&nbsp;{{$data->tanggal_format}}
			</td>
		</tr>
		<tr>
			<td>
				<label>Jumlah:</label>
			</td>
			<td>
				&nbsp;&nbsp;{{number_format($data->jumlah,2,'.',',')}}
			</td>
		</tr>
		<tr>
			<td colspan="2" >
				<a class="btn btn-danger btn-xs pull-right btn-delete-payment" style="margin-bottom: 0;" href="gaji/gaji-driver/delete-dp/{{$data->id}}" data-paymentid="{{$data->id}}" >Delete</a>
				<a target="_blank" href="gaji/gaji-driver/payment-to-print/{{$data->id}}" class="btn btn-success btn-xs pull-left" style="margin-bottom: 0;" >Print</a>
			</td>
		</tr>
	</tbody>
</table>

<script>
	(function ($) {
		$('.btn-delete-payment').click(function(){
			if(!confirm('Anda akan menghapus data payment?')){
				return false;
			}
		});
	})(jQuery);
</script>