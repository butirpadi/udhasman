<div class="box-footer" >
	<i>Showing {{($data->currentpage()-1)*$data->perpage()+1}} to {{(($data->currentpage()-1)*$data->perpage())+$data->count()}} of {{$data->total()}} entries</i>
    
    <div class="pull-right" >
        {{$data->links()}}
    </div>
</div>