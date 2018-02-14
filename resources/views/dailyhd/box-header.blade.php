<div class="box-header with-border" >
            <a class="btn btn-primary" id="btn-add" href="dailyhd/create" ><i class="fa fa-plus-circle" ></i> Create</a>
            <div class="btn-group">
                <a  class="btn bg-maroon dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-th-large" ></i>
                    Group by
                </a>
                <ul class="dropdown-menu">
                  <li><a href="dailyhd/group-by/alat_id">Alat</a></li>
                  <li><a href="dailyhd/group-by/lokasi_galian_id">Lokasi Galian</a></li>
                  <li><a href="dailyhd/group-by/pengawas_id">Pengawas</a></li>
                  <li><a href="dailyhd/group-by/operator_id">Operator</a></li>
                </ul>
            </div>
            <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>

            <div class="pull-right" >
                <div class="box-tools" style="margin-top: 6px;">
                    <form method="GET" action="dailyhd/search" >
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