@extends('layouts.app')
@permission('Admin-Permission')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Users
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
                    <a class="btn btn-success" href="{{route('user.create')}}"> Create User</a>
                      <table class="table table-bordered">
                          <tr>
                              <th>Name of User</th>
                              <th>Roles</th>
                              <th>Action</th>
                          </tr>
                          @forelse($users as $user)
                              <tr>
                                  <td>{{$user->name}}</td>
                                  <td>
                                      @foreach($user->roles as $role)
                                          {{$role->name}}
                                      @endforeach

                                  </td>

                                  <td>

                                    <div class="col-md-12">
                                      <!-- Button trigger modal -->
                                      <div class="col-md-6 pull-left">
                                      <a href="user/{{$user->id}}/edit/" type="button" class="btn btn-primary btn-sm" style='width:100%' id="{{$user->id}}">
                                          Edit
                                      </a>
                                    </div>
                                      <form action="{{route('user.destroy',$user->id)}}"  method="POST">
                                         {{csrf_field()}}
                                         {{method_field('DELETE')}}
                                       <div class="col-md-6 pull-right">
                                         <input id = "delete" style=' width:100%;' class="btn btn-sm btn-danger" type="submit" value="Delete">
                                       </div>
                                       </form>
                                     </div>
                                  </td>
                              </tr>
                          @empty
                              <td>no users</td>
                          @endforelse
                      </table>
                  </div><!-- /.box -->
                </div>  
              </div>
          </div> <!-- row close -->
        </section>
        </div>
@endsection
@endpermission