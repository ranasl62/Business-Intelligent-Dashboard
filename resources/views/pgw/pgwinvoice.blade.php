@extends('layouts.app')
@permission('PGW-Invoice')
@section('content')
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                PGW Invoice
            </h1>
        </section>
            
        <!-- Main content -->
        <section class="content">
          <div class="row" id="">
                <div class="col-md-12">
                  <div class="box box-primary">
                    <div class="box-header">
                      <h4 class="box-title">Filters</h4>
                    </div>
                    <div class="box-body">    
    
                    </div><!-- /.box-body -->
                    <div class="box-body">
                    
                        <button  class="btn btn-success" onclick="dataFilter()">Generate Table</button>
                    </div>
                  </div><!-- /.box -->
                </div>  
          </div> <!-- row close -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@include('pgw.script.pgwinvoicejs')

@endsection
@endpermission