<div class="box-header with-border" >
            <a class="btn btn-primary" id="btn-add" href="delivery/create" ><i class="fa fa-plus-circle" ></i> Create</a>
            <div class="btn-group">
                <a  class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-filter" ></i>
                    Filter
                </a>
                <ul class="dropdown-menu">
                  <li><a href="delivery/filter/state/draft">State : Draft</a></li>
                  <li><a href="delivery/filter/state/open">State : Open</a></li>
                  <li><a href="delivery/filter/state/done">State : Done</a></li>
                  <li class="divider" ></li>
                  <li><a href="delivery/filter/invoice_state/open">Invoice State : Open</a></li>
                  <li><a href="delivery/filter/invoice_state/paid">Invoice State : Paid</a></li>
                </ul>
            </div>
            <div class="btn-group">
                <a  class="btn bg-maroon dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-th-large" ></i>
                    Group by
                </a>
                <ul class="dropdown-menu">
                  <li><a href="delivery/groupby/customer">Customer</a></li>
                  <li><a href="delivery/groupby/pekerjaan">Pekerjaan</a></li>
                </ul>
            </div>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>

            <div class="pull-right" >
                <div class="box-tools" style="margin-top: 6px;">
                    <form method="GET" action="delivery/search" >
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