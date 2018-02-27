@extends('layouts.master')

@section('styles')
<!--Bootsrap Data Table-->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">

<style>
    #table-data > tbody > tr{
        cursor:pointer;
    }
</style>

@append

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        User Manager
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active" ><a href="#tab_2" data-toggle="tab">User Manager</a></li>
          <li  ><a href="#tab_3" data-toggle="tab">Role Manager</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_2">
               <div class="box box-solid">
                    <div class="box-header with-border">
                        <a class="btn btn-primary" id="btn-add" href="setting/user/create" ><i class="fa fa-plus-circle" ></i> Create</a>
                        <a class="btn btn-danger hide" id="btn-delete" href="#" ><i class="fa fa-trash" ></i> Delete</a>
                    </div>
                    <div class="box-body">
                        <?php $rownum=1; ?>
                        <table class="table table-bordered table-condensed table-striped " id="table-data" >
                            <thead>
                                <tr>
                                    <th style="width:25px;" class="text-center">
                                        <input type="checkbox" name="ck_all" style=""  >
                                    </th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th class="col-sm-1 col-md-1 col-lg-1" ></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $dt)
                                <tr data-rowid="{{$rownum}}" data-id="{{$dt->id}}">
                                    <td class="text-center" >
                                        <input type="checkbox" class="ck_row" >
                                    </td>
                                    <td class="" >
                                        {{$dt->username}}
                                    </td>
                                    <td class="" >
                                        {{$dt->role_desc}}
                                    </td>
                                    <td class="text-center" >
                                        <a class="btn btn-primary btn-xs" href="setting/user/edit/{{$dt->id}}" ><i class="fa fa-edit" ></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer" >
                        
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab_3">
               <form enctype="multipart/form-data" method="POST" action="setting/user/insert" >
                   <div class="form-group">
                        <label>Roles</label>
                        {!! Form::select('role',$roles,null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <div class="row" >
                        @foreach($permissions as $dt)
                            <div class="col-xs-3" >
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ck_roles" value="{{$dt->id}}" id="{{$dt->name}}" >
                                        {{$dt->desc}}
                                    </label>
                                </div>
                            </div>        
                        @endforeach
                        </div>
                    </div>
                        
                    <a class="btn btn-primary" id="btn-save-role" ><i class="fa fa-save" ></i> Save</a>
                </form>
            </div>
        </div>
    </div>

    <div id="form-submit" class="hide" ></div>

    <!-- Default box -->
    <!-- /.box -->

</section><!-- /.content -->

@stop

@section('scripts')
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="plugins/jqueryform/jquery.form.min.js" type="text/javascript"></script>

<script type="text/javascript">
(function ($) {

    // check all checkbox
    $('input[name=ck_all]').change(function(){
        if($(this).prop('checked')){
            $('input.ck_row').prop('checked',true);
        }else{
            $('input.ck_row').prop('checked',false);

        };
        showOptionButton();
    });

    // tampilkan btn delete
    $(document).on('change','.ck_row',function(){
        showOptionButton();
    });

    function showOptionButton(){
        var checkedCk = $('input.ck_row:checked');

        if(checkedCk.length > 0){
            // tampilkan option button
            $('#btn-delete').removeClass('hide');
        }else{
            $('#btn-delete').addClass('hide');
        }
    }

    // Row Clicked
    // $(document).on('click','.',function(){
    //     var row = $(this).parent();
    //     var data_id = row.data('id');
    //     location.href = 'setting/user/edit/' + data_id ;
    // });

    // Delete Data Lokasi
    $('#btn-delete').click(function(e){
        if(confirm('Anda akan menhapus data ini?')){
            var dataid = [];
            $('input.ck_row:checked').each(function(i){
                var data_id = $(this).parent().parent().data('id');
                // alert(data_id);
                var newdata = {"id":data_id}
                dataid.push(newdata);
            });

            var deleteForm = $('<form>').attr('method','POST').attr('action','setting/user/delete');
            deleteForm.append($('<input>').attr('type','hidden').attr('name','dataid').attr('value',JSON.stringify(dataid)));
            $('body').append(deleteForm);
deleteForm.submit();
        }

        e.preventDefault();
        return false;
    });

    // $('select[name=role]').val([]);
    $('select[name=role]').change(function(){
        // get permissions
        url = 'setting/user/get-role-permissions/'+$(this).val();
        // clear check
        $('input[type=checkbox]').prop('checked',false);

        // set cjheck
        $.get(url ,function(res){
            jsonData = JSON.parse(res);
            $.each(jsonData,function(i,data){
                $('#'+data.name).prop('checked',true);
            });
        });
    });
    $('select[name=role]').trigger('change');

    $('#btn-save-role').click(function(){
        role_id = $('select[name=role]').val();
        roles = [];
        $('input[name=ck_roles]:checked').each(function(){
            item = {};
            item['id'] = $(this).val();
            item['access_name'] = $(this).attr('id');
            roles.push(item);
        });

        form = $('<form>').attr('method','POST').attr('action','setting/user/update-role-permissions');
        form.append($('<input>').attr('text','hidden').attr('name','role_id').attr('value',role_id));
        form.append($('<input>').attr('text','hidden').attr('name','permissions').attr('value',JSON.stringify(roles)));
        $('#form-submit').append(form);
        form.submit();
    });


})(jQuery);
</script>
@append
