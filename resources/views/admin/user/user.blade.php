@php $no = 1; @endphp
@extends('admin.layouts.app')
@push('css')
  <!-- Page plugins -->
  <link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
@endpush

@push('btn-control')
  <div class="col-lg-6 col-5 text-right">
    @if (Request::segment('3')==="trash")
      <a href="{{ route('admin.user.restore') }}" class="btn btn-sm btn-warning"><i class="far fa-clone"></i> Restore Semua User</a>
      <a href="{{ route('admin.user') }}" class="btn btn-sm btn-neutral"><i class="fas fa-angle-double-left"></i> Kembali</a>
    @else
      <a href="#" id="btn-tambah" data-toggle="modal" data-target="#Muser" class="btn btn-sm btn-neutral"><i class="fas fa-plus-circle" aria-hidden></i> Tambah User</a>
      <a href="{{ route('admin.user.trash') }}" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> User Terhapus</a>
    @endif
  </div>
@endpush

@section('content')
  <!-- Table -->
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header">
          <h3 class="mb-0">{{ $title }}</h3>
          <p class="text-sm mb-0">
            {{ $description }}
          </p>
        </div>
        <div class="table-responsive py-4">
          <table class="table table-flush text-center" id="datatable-basic">
            <thead class="thead-light">
              <tr>
                <th>Nomor</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Status</th>
                <th>Level</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>

              @forelse ($data as $key => $value)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ $value->name }}</td>
                  <td>{{ $value->email }}</td>
                  <td>
                    @if (Request::segment(3)!='trash')
                      <a title="Nonaktifkan" {!! $value->status=="active" ? 'class="btn btn-danger btn-sm " href="'.route('admin.user.nonactive',$value->id).'"' : 'class="btn btn-danger btn-sm disabled" href= "#" disabled' !!}><i class="fa fa-clock"></i></a>
                      <a title="Aktifkan" {!! $value->status=="nonactive" ? 'class="btn btn-success btn-sm " href="'.route('admin.user.active',$value->id).'"' : 'class="btn btn-success btn-sm disabled" href= "#" disabled' !!}><i class="fa fa-check"></i></a>
                    @else
                      <span>{{ $value->status }}</span>
                    @endif
                  </td>
                  <td>{{ $value->level->level }}</td>
                  <td>
                    @if (Request::segment('3')=="trash")
                      <a class="btn btn-primary btn-sm" href="{{ route('admin.user.restore.id',$value->id) }}"><i class="fas fa-clone"></i> Restore</a>
                      <a class="btn btn-danger btn-sm btn-delete" id="btn-delete" data-id="{{ $value->id }}" href="javascript:modalDelete({{ $value->id }})"><i class="fa fa-trash"></i> Hapus Permanen</a>
                    @else
                      <a class="btn btn-warning btn-sm btn-edit" id="btn-edit" data-id="{{ $value->id }}" href="javascript:modalEdit({{ $value->id }})"><i class="fa fa-edit"></i></a>
                      <a class="btn btn-danger btn-sm btn-delete" id="btn-delete" data-id="{{ $value->id }}" href="javascript:modalDelete({{ $value->id }})"><i class="fa fa-trash"></i></a>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td>Data Not found</td>
                  <td>Data Not found</td>
                  <td>Data Not found</td>
                  <td>Data Not found</td>
                  <td>Data Not found</td>
                  <td>Data Not found</td>
                </tr>
              @endforelse

            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>


  <div id="Muser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModaluser" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title">Tambah User</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="form-user" method="post" action="{{ route('admin.user.store') }}">
          @csrf
          <div id="method"></div>
          <div class="modal-body">

            <div class="form-group mb-3">
              <div class="input-group input-group-merge input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input class="form-control" id="name" name="name" placeholder="Nama User" type="text" value="{{ old('name') }}" autofocus>
              </div>
            </div>

            <div class="form-group mb-3">
              <div class="input-group input-group-merge input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                <input class="form-control" id="email" name="email" placeholder="Email User" value="{{ old('email') }}" type="email">
              </div>
            </div>

            <div class="form-group mb-3">
              <div class="input-group input-group-merge input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input class="form-control" id="password" name="password" placeholder="Password User" type="password">
              </div>
            </div>

            <div class="form-group mb-3">
              <div class="input-group input-group-merge input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input class="form-control" id="password-confirm" name="password_confirmation" placeholder="Konfirmasi Password" type="password">
              </div>
            </div>

            <div id="info-update"></div>
            <div class="mb-3">
              <p>Level User : </p>
            </div>
            <div class="form-group mb-3">
              <div class="input-group input-group-merge input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-level-down-alt"></i></span>
                </div>
                <select class="form-control" name="level" id="level">
                  @foreach ($level as $key => $value)
                    <option id="option{{ $value->id }}" value="{{ $value->id }}">{{ $value->level }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('script')
  <!-- Optional JS -->
  <script src="/assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#btn-tambah').on('click', function() {
        $('#form-user').attr('action','{{ route('admin.user.store') }}');
        $('#method').html('@method('POST')')
        $('#name').val("")
        $('#email').val("")
        $('#password').val("")
        $('#password-confirm').val("")
        $('#info-update').html("")
        $('#password').attr("placeholder","Password User")
      })
    })

      function modalEdit(id) {
        // id = $(this).data('id')
        $.ajax({
          url: "/api/user/"+id,
          Type: "GET",
          headers : {
            'token': '{{ Session::get('api_token') }}'
          },
          success: function(res) {
            let obj = res.data
            let url = "{{ route('admin.user.update', ':id') }}";
            url = url.replace(':id', id);
            $('#form-user').attr('action',url)
            $('#method').html('@method('PUT')')
            $('#name').val(obj.name)
            $('#email').val(obj.email)
            $('#password').attr("placeholder","Password Baru")
            $('#option'+obj.level_id).attr('selected',true)
            $('#info-update').html('<div class="mb-5 mt-3"><p>Note : Kosongkan password jika tidak ingin ganti Password</p></div>')
            $('#Muser').modal('show')
          }
        })
      }

      function modalDelete(id) {
        // let id = $(this).data('id')
        let url = "{{ route(Request::segment('3')==="trash" ? 'admin.user.delete.permanent':'admin.user.delete', ':id') }}";
        url = url.replace(':id', id);
        swal({
          title: 'Anda Yakin?',
          text: '{{ Request::segment('3')==="trash" ? "Data akan dihapus secara permanen dan tidak bisa dikembalikan lagi" : "Data ini akan dihapus dan masuk ke dalam trash. Anda bisa me restore nya kembali nanti." }}',
          icon: 'warning',
          type: "error",
          buttons: ["Batal", "Lanjutkan!"],
        }).then(function(value) {
          if (value) {
            window.location.href = url
          }
        })
      }

      @error('name')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror
      @error('email')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror
      @error('password')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror
      @error('level')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror

  </script>
@endpush
