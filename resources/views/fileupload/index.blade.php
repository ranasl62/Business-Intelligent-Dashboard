@extends('layouts.app')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Files Upload
            </h1>
        </section>            
        <!-- Main content -->
        <section class="content">
          <div class="row">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h4 class="box-title">Filters</h4>
                    </div>
                       <div class="box-body">                      
                        <form class="form-horizontal" style="width:100%" role="form" id ="fileForm" enctype="multipart/form-data" files="true">
                            {!! csrf_field() !!}
                            <div class="form-group row">
                                <label class="col-md-2 control-label">Select Files: </label>
                                <div class="col-md-10">
                                    <div class="form-group row">
                                        <div class="col-md-8">
                                            <input class="form-control" type="file" name="fileUpload[]" id="fileUpload" multiple > 
                                        </div>
                                        <!-- <div class="col-md-2">
                                            <input class="btn btn-primary form-control" type="button" name="submit" value="Upload" onclick="upload()">
                                        </div> -->
                                        <div class="col-md-3 dropdown btn-group-vertical">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"style="width:100%;">
                                        Submit<span class="caret"></span>
                                            </button>
                                              <ul class="dropdown-menu" style="width:100%;">
                                                <li><button name="submit" class="btn btn-primary" onclick="uploadExcel()" style="Width:100%;">EBL Excel</button></li>

                                                <li class="divider" style="Width:100%;"></li>

                                               <li><button name="submit" class="btn btn-primary" onclick="uploadWord()" style="Width:100%;">CITY Word</button></li>
                                              </ul>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div> <!-- row close -->
                 <!-- row start -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <!-- <h3 class="box-title">Source Data Table</h3> -->
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive" style="width: 100%; margin: 0; padding: 1%;">
                        <table id='myTable' class='display table table-striped' style='width:100%'>
                            <tr class="row">
                                <th>Serial #</th>
                                <th>Inserted Row</th>
                                <th>File Name</th>
                                <th>Status</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- row close -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('fileupload.script.index')
@endsection
