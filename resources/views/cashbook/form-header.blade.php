<div class="box-header with-border" >
    <label><h3 style="margin:0;padding:0;font-weight:bold;" >{{isset($data) ? $data->cash_number : 'New'}}</h3></label>
    
    <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label>
    <a class="btn btn-arrow-right pull-right disabled {{isset($data) ? 'bg-blue':'bg-gray'}}" >Posted</a>

    <label class="pull-right" >&nbsp;&nbsp;&nbsp;</label> 
    <a class="btn btn-arrow-right pull-right disabled {{isset($data) ? 'bg-gray' : 'bg-blue'}} " >Draft</a>
</div>