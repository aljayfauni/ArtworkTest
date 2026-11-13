@extends('adminlte::page')

@section('title','Jobspecs | Roles')

@section('plugins.Datatables',true)
@section('plugins.DatatablesPlugins',true)
@section('plugins.Sweetalert2',true)

@section('content_header')
    {{-- <h1>Roles</h1> --}}
@stop

@section('content')
<div class="row mr-4" >
    <div class="col-12 mt-4 ">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-solid fa-users"> </i> List of Roles</h3>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
                
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table id="Table" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Access</th>
                                    <th>View All Requests</th>
                                    <th>ReadOnly</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                    <!--<th>Actions</th>-->
                                    
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
    var Table = $('#Table').DataTable({
        //dom: 'lrt',
        "responsive":true,
        "lengthChange": true,
        //"lengthMenu":[[10,25,50,-2],[10,25,50,'All']],
        "autoWidth": false,
        ajax: '{{ route('roles.showall') }}',
        serverSide: true,
        processing: true,
        //"bFilter": false,
        
        "columns": [
        { data: 'id',
            visible: false,
            searchable: false
        } ,
        { data: 'name' },
        { data: 'access' },
        { data: 'view_all_requests' },
        { data: 'read_only' },
        { data: 'status' },
        { data: 'action' },
        ],
        "initComplete": function() {
            Table.buttons().container().appendTo('#Table_wrapper .col-md-6:eq(0)');
            $("#Table").show();
        },
        "buttons": {
            buttons: [
        //        // { extend:"copy", className: 'btn btn-success'},
        //        // { extend:"csv", className:'btn btn-success' },
                // {
                //     className: 'btn btn-warning',
                //     text: '<i class="fas fa-plus"></i>',
                //     titleAttr: 'Create',
                //     action: function(e, dt, node, config){
                //         window.location = '{{route('users.create')}}';
                //     }
                // },
        //         { extend: "excel", className: 'btn btn-warning', text: '<i class="fas fa-file-excel"></i>',titleAttr: 'Export to Excel',
        //             action: function(e, dt, node, config){
        //                 window.location = '';
        //             }
        //             // exportOptions: {
        //             //     columns: [1,2,3,4,5,6]
        //             // }
        //         },
        //         { extend: "pdf", className: 'btn btn-warning', text: '<i class="fas fa-file-pdf"></i>',titleAttr: 'Export to PDF',
        //             orientation: 'landscape',
        //             download: 'open',
        //             exportOptions: {
        //                 columns: [1,2,3,4,7]
        //             },
        //             customize: function (doc) {
        //                 doc.content[1].table.widths = 
        //                     Array(doc.content[1].table.body[0].length + 1).join('*').split('');
        //             }
        //         },
        //         { extend: "print", className: 'btn btn-warning', text: '<i class="fas fa-print"></i>', titleAttr: 'Print',
        //             exportOptions: {
        //                 columns: [1,2,3,4,5,6]
        //             }
        //         },
        //         { extend: "colvis", className: 'btn btn-warning dropdown-toggle dropdown-icon', text:'', titleAttr: 'Column Visibility',columns: ':gt(0)'}
            ]
        }
        
    });


    search = function(){
        console.log($('#search').val());
        InvoicesTable.search($('#search').val()).draw();
        PODsTable.search($('#search').val()).draw();
    }

    deleteUser = function(index){
        Swal.fire({  
                title: 'Are you sure you want to delete the selected record?', 
                text: "You won't be able to revert this!",
                icon: 'warning', 
                showCancelButton: true,  
                confirmButtonText: `Yes`,  
                cancelButtonText: `No`,
                }).then((result) => {  
                    /* Read more about isConfirmed, isDenied below */ 
                     
                    if (result.value) {    
                        $.ajax({
                            url: '/users/' + index,
                            type: 'delete',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success:  function(response){
                                if (response.success){
                                    Toast.fire({
                                        icon:'success',
                                        title:response.success
                                    });
                                    $('#UsersTable').DataTable().ajax.reload(null,false);
                                }else if(response.error){
                                    Toast.fire({
                                        icon:'error',
                                        title:response.error
                                    });
                                }
                                
                            }
                        }); 
                    }
                });
       
    };

    function downloadURI(uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
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

