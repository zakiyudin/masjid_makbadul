@extends('layouts.global')

@section('judul')
    Manajemen User
@endsection

@section('extend_css')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('sukses'))
        <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
            {{ session('sukses') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="overview-wrap">
                    <h2 class="title-1 mb-1">Data User</h2>                
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <button class="btn btn-primary m-2" data-toggle="modal" data-target="#modal_tambah_edit" id="tambah_user"><i class="fas fa-plus-square"></i>&nbsp;Tambah</button>
                </div>
            </div>
            <br>
        </div>


       


          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered data" id="dataku" width="100%" cellspacing="0">
                  <thead class="thead-dark">
                    <tr>
                      {{-- <th width="30px">No</th> --}}
                      <th>No</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Tanggal Lahir</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                      
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->


        
        
<!-- Modal Tambah -->
<div class="modal fade" id="modal_tambah_edit" tabindex="-1" role="dialog" aria-labelledby="modal-judul" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-judul">Tambah User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" class="form-group" id="form-tambah-edit">
          @csrf
          <input type="hidden" name="id" id="id" class="form-control">
          <label for="">Nama User</label>
          <input type="text" name="name" id="name" class="form-control">
          <br>

          <label for="">Email</label>
          <input type="email" name="email" id="email" class="form-control">
          <br>

          <label for="">Peran</label>
          <select name="role" id="role" class="form-control">
            <option value="">.:: Pilih Satu ::.</option>
            <option value="admin">Admin</option>
            <option value="bendahara">Bendahara</option>
            <option value="sekretaris">Sekretaris</option>
          </select>
          <br>

          <label for="">Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control">
          <br>

          <label for="">Alamat</label>
          <textarea name="alamat_ktp" id="alamat_ktp" cols="30" rows="7" class="form-control"></textarea>
          <br>

          <label for="">Password</label>
          <input type="password" name="password" id="password" class="form-control">

          <label for="">Status Aktif</label>
          <input type="radio" name="status_aktif" id="status_aktif" value="1">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn_simpan" value="create">Simpan</button>
      </div>
    </div>
  </div>
</div>


{{-- modal konfirmasi hapus user --}}
<div class="modal fade" tabindex="-1" role="dialog" id="konfirmasi-modal" data-backdrop="false">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">PERHATIAN</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <p><b>Jika menghapus Pegawai maka</b></p>
              <p>*data pegawai tersebut hilang selamanya, apakah anda yakin?</p>
          </div>
          <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-danger" name="btn_hapus" id="btn_hapus">Hapus
                  Data</button>
          </div>
      </div>
  </div>
</div>


@endsection

@section('extend_js')

<script type="text/javascript">
  $(document).ready(function(){
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $("#dataku").DataTable({
      processing:true,
      serverSide:true,
      ajax:"{{ route('users.index') }}",
      columns:[
        {data:'id', name:'id'},
        {data:'name', name:'name'},
        {data:'email', name:'email'},
        {data:'role', name:'role'},
        {data:'tgl_lahir', name:'tgl_lahir'},
        {data:'action', name:'action'},
      ]
    });

    $("#tambah_user").click(function(){
      $("#btn_simpan").val('create-post');
      $("#id").val('');
      $("#form-tambah-edit").trigger('reset');
      $("#modal-judul").html('Tambah User Baru');
      $("#modal_tambah_edit").modal("show");
    });


    //SIMMPAN DAN EDIT DATA USER
    $("#btn_simpan").on('click', function(){
      var nama = $("#name").val();
      var email = $("#email").val();
      var role = $("#role").val();
      var tgl_lahir = $("#tgl_lahir").val();
      var alamat_ktp = $("#alamat_ktp").val();
      var password = $("#password").val();
      var status_aktif = $("#status_aktif").val();
      
      if(nama != "" && email != "" && role != "" && tgl_lahir != "" && alamat_ktp != ""){
        $("#btn_simpan").attr('disabled', 'disabled');
        $.ajax({
          url:"{{ route('users.store') }}",
          type: "post",
          dataType:'json',
          data:{
            name : nama,
            email : email,
            role : role,
            tgl_lahir : tgl_lahir,
            alamat_ktp : alamat_ktp,
            password : password,
            status_aktif : status_aktif
          },
          // cache: false,
          success:function(data){
            $("#form-tambah-edit").trigger('reset');
            $("#modal_tambah_edit").modal('hide');
            $("#btn_simpan").html('Simpan');

            swal("Good job!", "Data Berhasil Disimpan", "success");

          },
          error:function(data){
            console.log('error', data);
          }
        })
      }else{
        swal("Error !", "Data Harus Diisi", "warning");
      }
    });


    $('body').on('click', '.edit-post', function(){
      var id = $(this).data('id');
      $.ajax({
        type: "get",
        url: "users/"+id+"/edit",
        data: {
          id:id,
        },
        dataType: "json",
        success: function (data) {
          $("#modal-judul").html('Edit Users');
          $("#btn_simpan").val('edit-post');
          $("#modal_tambah_edit").modal('show');

          $("#name").val(data.name);
          $("#email").val(data.email);
          $("#role").val(data.role);
          $("#tgl_lahir").val(data.tgl_lahir);
          $("#alamat_ktp").val(data.alamat_ktp);
          $("#password").val(data.password);
          $("#status_aktif").val(data.status_aktif);
        }
      });
    })


    $('body').on('click', '.delete', function(){
      var id = $(this).data('id');
      var konfirmasi = confirm('apakah anda yakin menghapus ??');
      $.ajax({
        type: "delete",
        url: "/users/"+id,
        data: {
          id:id,
        },
        dataType: "json",
        success: function (response) {
          swal("Good job!", "Data Berhasil Dihapus", "success");
        }
      });
      // $("#konfirmasi-modal").modal('show');

    });

    // $("#btn_hapus").click(function(){
    //   $.ajax({
    //     type: "delete",
    //     url: "/users"+id,
    //     data: {
    //       id:id,
    //     },
    //     dataType: "json",
    //     success: function (response) {
    //       swal("Good job!", "Data Berhasil Dihapus", "success");
    //     }
    //   });
    // })
    




  });
</script>
    
@endsection