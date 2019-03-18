@extends('layouts.app')
@permission('Admin-Permission')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Roles
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
                    <div class="box-body  table-responsive">
                      <a class="btn btn-success" href="{{route('role.create')}}"> Create Role</a>
                      <table class="table table-striped">
                        <thead>
                          <tr scope='row'>
                              <th scope='col'>Name</th>
                              <th scope='col'>Display Name</th>
                              <th scope='col'>Description</th>
                              <th scope='col'>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                          @forelse($roles as $role)
                              <tr scope='row'>
                                  <td scope='col'>{{$role->name}}</td>
                                  <td scope='col'>{{$role->display_name}}</td>
                                  <td scope='col'>{{$role->description}}</td>
                                  <td>
                                    <div class="col-md-12">
                                      <div class="col-md-6 pull-right">
                                      <a id="edit" style='width:100%;' class="btn btn-info btn-sm" href="{{route('role.edit',$role->id)}}">Edit</a>
                                    </div>
                                      <form action="{{route('role.destroy',$role->id)}}"  method="POST">
                                         {{csrf_field()}}
                                         {{method_field('DELETE')}}
                                         <div class="col-md-6 pull-left">
                                         <input id = "delete" style='width:100%' class="btn btn-sm btn-danger" type="submit" value="Delete">
                                       </div>
                                       </form>
                                     </div>
                                  </td>
                              </tr>
                              @empty
                              <tr>
                                  <td>No roles</td>
                              </tr>
                              @endforelse
                            </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div><!-- /.box -->
        </div>  
      </div> <!-- row close -->
@endsection
@endpermission