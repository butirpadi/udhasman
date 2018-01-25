<div class="box-header with-border" style="padding-top:5px;padding-bottom:5px;" >
    <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{isset($pengiriman) ? $pengiriman->name : 'New'}}</h3></label>
    <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
    <a class="btn  btn-arrow-right pull-right disabled {{isset($pengiriman) ? ($pengiriman->state == 'done' ? 'bg-blue' : 'bg-gray') : 'bg-gray'}}" >DONE</a>

    <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
    <a class="btn btn-arrow-right pull-right disabled {{isset($pengiriman) ? ($pengiriman->state == 'open' ? 'bg-blue' : 'bg-gray') : 'bg-gray'}}  " >OPEN</a>

    <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
    <a class="btn btn-arrow-right pull-right disabled {{isset($pengiriman) ? ($pengiriman->state == 'draft' ? 'bg-blue' : 'bg-gray') : 'bg-blue'}}" >DRAFT</a>
</div>