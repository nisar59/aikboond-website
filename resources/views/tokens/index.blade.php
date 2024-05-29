@extends('layouts.template')
@section('title')
Tokens
@endsection
@section('content')
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="col-md-6">Tokens</h4>
                    <div class="col-md-6 text-right">
                    <a href="{{url('/tokens/create')}}" class="btn btn-success">+</a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-striped table-hover" id="roles" style="width:100%;">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Token</th>
                            <th>Status</th>
                            <th>Expiry</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody> 
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
@endsection
@section('js')
<script type="text/javascript">
    //Roles table
    $(document).ready( function(){
  var data_table;
  function DataTableInit(data={}) {
    var i=1;
  data_table = $('#roles').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url:"{{url('/tokens')}}",
        data:data,
        },
      buttons:[],
      buttons:[],
              columns: [ 
                  {
                  "render": function(data, type, full, meta) {
                    return i++;
                  }
                },               
                {data: 'token', name: 'token',orderable: false, searchable: false},
                {data: 'status', name: 'status',class:'text-center', orderable: false, searchable: false},
                {data: 'expiry', name: 'expiry',orderable: false, searchable: false},                
                {data: 'action', name: 'action',class:'text-center',orderable: false, searchable: false},
            ]
  });
}

DataTableInit();
      });
</script>
@endsection
