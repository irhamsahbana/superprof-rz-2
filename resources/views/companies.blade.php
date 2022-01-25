<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <title>Crud Ajax</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link  href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.css" integrity="sha512-7uSoC3grlnRktCWoO4LjHMjotq8gf9XDFQerPuaph+cqR7JC9XKGdvN+UwZMC14aAaBDItdRj3DcSDs4kMWUgg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.js" integrity="sha512-9e9rr82F9BPzG81+6UrwWLFj8ZLf59jnuIA/tIf8dEGoQVu7l5qvr02G/BiAabsFOYrIUTMslVN+iDYuszftVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Rizki Company Server 2</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </header>
      <div class="container-fluid">
        <div class="row">
          <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link active btn btn-primary" aria-current="page" href="/">
                    <span data-feather="home"></span>
                    Menu
                  </a>
                </li>
              </ul>
            </div>
          </nav>
      
          <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
              <h1 class="h2">Dashboard</h1>
            </div>
            <div class="pull-right mb-2">
                <a class="btn btn-success" onClick="add()" href="javascript:void(0)">Create Company</a>
                <a class="btn btn-primary" onClick="importcompanies()" href="javascript:void(0)">Import</a>
                <a class="btn btn-primary" href="{{ url('companies/export/') }}">Export</a>
            </div>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif
            <div class="table-responsive">
              <table class="table table-bordered" id="crud">
                  <thead>
                      <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Address</th>
                        <th scope="col">Created at</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
              </table>
            </div>
          </main>
        </div>
      </div>
    <!-- boostrap company model -->
    <div class="modal fade" id="company-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="CompanyModal"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="CompanyForm" name="CompanyForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Nama</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama anda.." maxlength="50" required="">
                            </div>
                        </div>  
                        <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-12">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email anda.." maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">Phone</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan HP anda.." maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="address" class="col-sm-2 control-label">Alamat</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="address" name="address" placeholder="Masukkan alamat anda.." required="">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->
    <!-- import model -->
    <div class="modal fade" id="import-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" enctype="multipart/form-data" class="dropzone" id="dropzone">
                    {{ csrf_field() }}
                    <div class="dz-default dz-message"><h4>Drop files here or click to upload</h4></div>
                    </form>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- import model -->
    </body>
    <script type="text/javascript">

        $(document).ready( function () {
            $.ajaxSetup({
            headers: {
            'Access-Control-Allow-Origin': '*',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }

            Dropzone.options.dropzone =
            {
                maxFiles: 5, 
                maxFilesize: 4,
                //~ renameFile: function(file) {
                    //~ var dt = new Date();
                    //~ var time = dt.getTime();
                //~ return time+"-"+file.name;    // to rename file name but i didn't use it. i renamed file with php in controller.
                //~ },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                timeout: 50000,

                init:function() {

                    // Get images
                    var myDropzone = this;
                    $.ajax({
                        url: gallery,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data){
                        //console.log(data);
                        $.each(data, function (key, value) {

                            var file = {name: value.name, size: value.size};
                            myDropzone.options.addedfile.call(myDropzone, file);
                            myDropzone.options.thumbnail.call(myDropzone, file, value.path);
                            myDropzone.emit("complete", file);
                        });
                        }
                    });
                },

                removedfile: function(file) 
                {
                    if (this.options.dictRemoveFile) {
                    return Dropzone.confirm("Are You Sure to "+this.options.dictRemoveFile, function() {
                        if(file.previewElement.id != ""){
                            var name = file.previewElement.id;
                        }else{
                            var name = file.name;
                        }
                        //console.log(name);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                            type: 'POST',
                            url: delete_url,
                            data: {filename: name},
                            success: function (data){
                                alert(data.success +" File has been successfully removed!");
                            },
                            error: function(e) {
                                console.log(e);
                            }});
                            var fileRef;
                            return (fileRef = file.previewElement) != null ? 
                            fileRef.parentNode.removeChild(file.previewElement) : void 0;
                        });
                    }		
                },
        
                success: function(file, response) 
                {
                    file.previewElement.id = response.success;
                    //console.log(file); 
                    // set new images names in dropzone’s preview box.
                    var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
                    file.previewElement.querySelector("img").alt = response.success;
                    olddatadzname.innerHTML = response.success;
                },

                error: function(file, response)
                {
                    if($.type(response) === "string")
                        var message = response; //dropzone sends it's own error messages in string
                    else
                        var message = response.message;
                    file.previewElement.classList.add("dz-error");
                    _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                    _results = [];
                    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                        node = _ref[_i];
                        _results.push(node.textContent = message);
                    }
                    return _results;
                }
            };
        });

        $('#crud').DataTable({
            processing: false,
            serverSide: true,
            ajax: "{{ url('list') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'created_at', name: 'created_at' },
                {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'desc']]
            });
        });
        
        function add() {
            $('#CompanyForm').trigger("reset");
            $('#CompanyModal').html("Add Company");
            $('#company-modal').modal('show');
            $('#id').val('');
        }  

        function importcompanies() {
            $('#import-modal').modal('show');
        }

        function editFunc(id){
            $.ajax({
                type:"POST",
                url: "{{ url('edit-company') }}",
                data: { id: id },
                dataType: 'json',
                success: function(res) {
                    $('#CompanyModal').html("Edit Company");
                    $('#company-modal').modal('show');
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#email').val(res.email);
                    $('#phone').val(res.phone);
                    $('#address').val(res.address);
                },
                error: function(data){
                    console.log(data);
                }
            });
        }     

        function deleteFunc(id){
            if (confirm(`Delete Record with id ${id}?`) == true) {
                // ajax
                $.ajax({
                    type:"POST",
                    url: "{{ url('delete-company') }}",
                    data: { id: id },
                    cache:false,
                    dataType: 'json',
                    success: function(res){
                        var oTable = $('#crud').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            }
        }

        $('#CompanyForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type:'POST',
                url: "{{ url('store-company')}}",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#company-modal").modal('hide');
                    var oTable = $('#crud').dataTable();
                    oTable.fnDraw(false);
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                },
                error: function(data){
                    console.log(data);
                }
            });
        });
    </script>
</html>