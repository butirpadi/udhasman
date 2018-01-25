<div class="box-header with-border" >
    <a class="btn btn-primary " id="btn-add" href="finance/payment/create" ><i class="fa fa-plus-circle" ></i> Create</a>
    <div class="btn-group">
        <a  class="btn btn-success dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-filter" ></i>
            Filter
        </a>
        <ul class="dropdown-menu">
          <li><a href="finance/payment/filter/state/draft">State : Draft</a></li>
          <li><a href="finance/payment/filter/state/post">State : Posted</a></li>
          <li><a href="finance/payment/filter/state/rec">State : Reconciled</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <a  class="btn bg-maroon dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-th-large" ></i>
            Group by
        </a>
        <ul class="dropdown-menu">
          <li><a href="finance/payment/groupby/partner">Partner</a></li>
        </ul>
    </div>
    <div class="pull-right" >
        <div class="box-tools" style="margin-top: 6px;">
            <form method="GET" action="finance/payment/search" >
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="val" class="form-control pull-right" placeholder="Search" value="{{isset($search_val) ? $search_val : ''}}" >
                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                  </div>
                </div>                    
            </form>
        </div>
    </div>
</div>