@php $no = 1; @endphp
@extends('admin.layouts.app')
@push('css')
  <!-- Page plugins -->
  <link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
@endpush

@push('btn-control')
  <div class="col-lg-6 col-5 text-right">
    @if (Request::segment('3')==="trash")
      <a href="{{ route('admin.facility.restore') }}" class="btn btn-sm btn-warning"><i class="far fa-clone"></i> Restore Semua Fasilitas</a>
      <a href="{{ route('admin.facility') }}" class="btn btn-sm btn-neutral"><i class="fas fa-angle-double-left"></i> Kembali</a>
    @else
      <a href="#" id="btn-tambah" data-toggle="modal" data-target="#Mfacility" class="btn btn-sm btn-neutral"><i class="fas fa-plus-circle" aria-hidden></i> Tambah Fasilitas</a>
      <a href="{{ route('admin.facility.trash') }}" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Fasilitas Terhapus</a>
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
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>

              @forelse ($data as $key => $value)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ $value->name }}</td>
                  <td>
                    @if (Request::segment('3')=="trash")
                      <a class="btn btn-primary btn-sm" href="{{ route('admin.facility.restore.id',$value->id) }}"><i class="fas fa-clone"></i> Restore</a>
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
                </tr>
              @endforelse

            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>


  <div id="Mfacility" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalfacility" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title">Tambah Fasilitas</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="form-facility" method="post" action="{{ route('admin.facility.store') }}">
          @csrf
          <div id="method"></div>
          <div class="modal-body">

            <div class="form-group mb-3">
              <div class="input-group input-group-merge input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-building"></i></span>
                </div>
                <input class="form-control" id="name" name="name" placeholder="Nama Fasilitas" type="text" value="{{ old('name') }}" autofocus>
              </div>
            </div>
            
            <div id="info-update"></div>
          
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
        $('#form-facility').attr('action','{{ route('admin.facility.store') }}');
        $('#method').html('@method('POST')')
        $('#name').val("")
        $('#info-update').html("")
      })
    })

      function modalEdit(id) {
        // id = $(this).data('id')
        $.ajax({
          url: "/api/facility/"+id,
          Type: "GET",
          headers : {
            'token': '{{ Session::get('api_token') }}'
          },
          success: function(res) {
            let obj = res.data
            let url = "{{ route('admin.facility.update', ':id') }}";
            url = url.replace(':id', id);
            $('#form-facility').attr('action',url)
            $('#method').html('@method('PUT')')
            $('#name').val(obj.name)
            $('#info-update').html('')
            $('#Mfacility').modal('show')
          }
        })
      }

      function modalDelete(id) {
        // let id = $(this).data('id')
        let url = "{{ route(Request::segment('3')==="trash" ? 'admin.facility.delete.permanent':'admin.facility.delete', ':id') }}";
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
      
  </script>
@endpush
