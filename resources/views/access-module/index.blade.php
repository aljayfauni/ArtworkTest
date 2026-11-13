@extends('adminlte::page')

@section('title','Jobspecs | Access Modules')

@section('plugins.Datatables',true)
@section('plugins.DatatablesPlugins',true)
@section('plugins.Sweetalert2',true)

@section('content_header')
    {{-- <h1>Access Modules</h1> --}}
    
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-solid fa-users"> </i> List of Access Modules</h3>
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
                                    <th>Description</th>
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
        ajax: '{{ route('access-modules.showall') }}',
        serverSide: true,
        processing: true,
        //"bFilter": false,
        
        "columns": [
        { data: 'id',
            visible: false,
            searchable: false
        } ,
        { data: 'name' },
        { data: 'description' },
        { data: 'action' },
        ],
        "initComplete": function() {
            Table.buttons().container().appendTo('#Table_wrapper .col-md-6:eq(0)');
            $("#Table").show();
        },
        "buttons": {
            buttons: [
                {
                    className: 'btn btn-warning',
                    text: '<i class="fas fa-plus"></i>',
                    titleAttr: 'Create',
                    action: function(e, dt, node, config){
                        window.location = '{{route('access-modules.create')}}';
                    }
                },
        
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

