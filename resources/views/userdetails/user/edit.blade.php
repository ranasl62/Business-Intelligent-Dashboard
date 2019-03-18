@extends('layouts.app')
@section('content')

<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Edit
            </h1>
        </section>
            
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">

                    <div class="box-header">
                        <h4 class="box-title">Update For {{$user->name}}</h4>
                        @if(Session::get('error')!=null)
                            <script>swal("Bad Luck!", "{{Session::get('error')}}", "danger");</script>
                        <script>  </script>
                        @endif
                        @if(Session::get('info')!=null)
                            <script>swal("Good job!", "{{Session::get('info')}}", "success");</script>
                        @endif
                    </div>

                    <div class="box-body form-row">
                        <form action="{{route('user.update',$user->id)}}" method="post" role="form" id="role-form-{{$user->id}}">
                            {{csrf_field()}}
                            {{method_field('PATCH')}}

                             <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                             </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="roles" class="col-md-4 control-label">User Roles</label>
                                <div class="col-md-6">
                                <select name="roles[]" title="Role" class="selectpicker form-control" data-live-search="true" multiple>
                                    @forelse($allRoles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @empty
                                        <option value="0">NO Role</option>
                                    @endforelse
                                </select>
                                </div>
                            </div>                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                  </div>
                </div><!-- /.box -->
            </div>  
        </section>
</div> <!-- row close -->
@include('userdetails.user.userjs')
@endsection
