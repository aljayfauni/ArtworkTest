@extends('adminlte::page')

@section('title','Reports | Historical Data')

@section('plugins.Datatables',true)
@section('plugins.DatatablesPlugins',true)
@section('plugins.Sweetalert2',true)

@section('content_header')
    {{-- <h1>Users</h1> --}}
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-spray-can"> </i> Historical Data Report</h3>
            </div>
              <!-- /.card-header -->
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fa-solid fa-triangle-exclamation fa-5x text-warning"></i>
                </div>
                <h4 class="mb-3">Under maintenance</h4>
                <p class="text-muted mb-0">This report section is not finished yet. Layout, filters, and data rendering are still pending implementation.</p>
            </div>
        </div>           
    </div>
</div> 
@stop

@section('js')
<script>
var deleteFile;
var bulkPrint;
var search;

$(document).ready(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
        });

    var ReportTable = $('#ReportTable').DataTable({
        dom: 'lBfrtip',
        "responsive":true,
        "lengthChange": true,
        //"lengthMenu":[[10,25,50,-2],[10,25,50,'All']],
        "autoWidth": false,
        ajax: {
            url: '', // Your API endpoint
            method: 'GET', // Use POST method
            data: function(d) {
                // Add any additional data to the request
                d.as_of_date = $('#as_of_date').val(); // Example of adding a custom parameter
            }
        },
        serverSide: true,
        processing: true,
        //"bFilter": false,
        
        "columns": [
        // { data: 'id',
        //     visible: false,
        //     searchable: false
        // } ,
        { data: 'type'},
        { data: 'transaction_number'},
        { data: 'trans_date'},
        // { data: 'ext_ref_number'},
        { data: 'm_o_number'},
        { data: 'job'},
        { data: 'ink_master'},
        { data: 'total_quantity'},
        { data: 'remarks' },
        // { data: 'action' },
        ],
        order: [],
        createdRow: function(row, data, dataIndex) {
            $('td:eq(6)', row).addClass('text-right'); // Change the index to your amount column
        },
        columnDefs: [
            { targets: 6, width: '100px' } // Adjust the target index for your amount column
        ],
        "initComplete": function() {
            ReportTable.buttons().container().appendTo('#ReportTable_wrapper .col-md-6:eq(0)');
            $("#ReportTable").show();
        },
        "buttons": {
            buttons: [
                { extend: "excel", className: 'btn btn-warning', text: '<i class="fas fa-file-excel"></i>',titleAttr: 'Export to Excel',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6]
                    }
                },
            ]
        
        }
        
    });

    search = function() {
        ReportTable.ajax.reload(null,false)
    }

    @if (\Session::has('success'))
        Toast.fire({
            icon:'success',
            title:"{{ \Session::get('success') }}"
        });
    @endif

    @if(\Session::has("Export")) 
        Toast.fire({
            icon: 'success',
            title: '{{ \Session::get("Export") }}'
        })
        downloadURI('/users.xlsx', 'User Profiles.xlsx');
    @endif
   

});


</script>
@stop

