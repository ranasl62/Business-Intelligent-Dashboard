@extends('layouts.app')
@permission('Admin-Permission')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Update Role
            </h1>
        </section>
            
        <!-- Main content -->
        <section class="content">
          <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header">
                      <!-- <h4 class="box-title">Filters</h4> -->
                    </div>
                    <div class="box-body">
                          <form action="{{route('role.update',$role->id)}}" method="post" role="form">
                          {{method_field('PATCH')}}
                          {{csrf_field()}}

                            <div class="form-group">
                              <label for="name">Name of role</label>
                              <input type="text" class="form-control" name="name" id="" placeholder="Name of role" value="{{$role->name}}">
                            </div>
                              <div class="form-group">
                              <label for="display_name">Display name</label>
                              <input type="text" class="form-control" name="display_name" id="" value="{{$role->display_name}}" placeholder="Display name">
                            </div>
                              <div class="form-group">
                              <label for="description">Description</label>
                              <input type="text" class="form-control" name="description" id="" placeholder="Description" value="{{$role->description}}">
                            </div>

                              <div class="form-group text-left">
                                  <h3>Permissions</h3>
                                  @foreach($permissions as $permission)
                              <input type="checkbox" {{in_array($permission->id,$role_permissions)?"checked":""}}   name="permission[]" value="{{$permission->id}}" > {{$permission->name}} <br>
                                  @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>               
                    </div>
                  </div>
                </div>
              </section>
            </div><!-- /.box -->
          </div>  
        </div> <!-- row close -->
  @endsection
@endpermission