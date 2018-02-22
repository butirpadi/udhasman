<div class="box-footer" >
    <div class="row" >
        <div class="col-xs-3" >
            <i>Showing {{($data->currentpage()-1)*$data->perpage()+1}} to {{(($data->currentpage()-1)*$data->perpage())+$data->count()}} of {{$data->total()}} entries</i>
        </div>
        <div class="col-xs-9" >
            <div class="pull-right" >
                {{ $data->links() }}
            </div>  
        </div>
    </div>
</div>