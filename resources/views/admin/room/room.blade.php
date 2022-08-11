@php $no = 1; @endphp
@extends('admin.layouts.app')
@push('css')
  <!-- Page plugins -->
  <link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ asset('/bs-taginput/tagsinput.css')}}">
@endpush

@push('btn-control')
  <div class="col-lg-6 col-5 text-right">
    @if (Request::segment('3')==="trash")
      <a href="{{ route('admin.room.restore') }}" class="btn btn-sm btn-warning"><i class="fas fa-trash-alt"></i>Restore Semua Ruangan</a>
      <a href="{{ route('admin.room') }}" class="btn btn-sm btn-neutral"><i class="fas fa-angle-double-left"></i> Kembali</a>
    @else
      <a href="#" id="btn-tambah" data-toggle="modal" data-target="#MRoom" class="btn btn-sm btn-neutral"><i class="fas fa-plus-circle" aria-hidden></i> Tambah Ruangan</a>
      <a href="{{ route('admin.room.trash') }}" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Ruangan Terhapus</a>
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
                <th>Nama Ruangan</th>
                <th>Tipe Ruangan</th>
                <th>Fasilitas</th>
                <th>Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>

              @forelse ($data as $key => $value)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ $value->name }}</td>
                  <td>{{ $value->type->name }}</td>
                  <td>
                    @foreach ($value->facility as $key => $facility)
                      <p class="badge badge-pill badge-primary">{{ $facility->name }} ({{ $facility->count() }})</p>
                      @endforeach
                  </td>
                  <td>{{ $value->type->price }}</td>
                  <td>
                    @if (Request::segment('3')=="trash")
                      <a class="btn btn-primary btn-sm" href="{{ route('admin.room.restore',$value->id) }}"><i class="fas fa-clone"></i> Restore</a>
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


  <div id="MRoom" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalRoom" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modal-title">Tambah Ruangan</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">x</span>
          </button>
        </div>
        <form id="form-room" method="post" action="{{ route('admin.room.store') }}" enctype="multipart/form-data">
          @csrf
          <div id="method"></div>
          <div class="modal-body">

            <div class="container-fluid">
              @csrf
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Nama Ruangan : </label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama Ruangan" value="{{ old('name') }}">
                  </div>
              
                  <label for="">Gambar Ruangan : </label>
                  <div class="form-group mt-2 mb-3">
                    <div class="input-group-prepend">
                      <label class="input-group-btn">
                        <span class="btn btn-primary">
                          Browse&hellip; <input type="file" name="image" id="image" accept="image/*" style="display: none;" value="{{ old('image') }}">
                        </span>
                      </label>
                      <input id="txt-image" type="text" class="form-control" readonly>
                    </div>
                    <div id="info-update"></div>
                  </div>
                  
                  <div class="form-group">
                      <label for="">Tipe Ruangan: </label>
                      <select class="form-control" id="type" name="type">
                          @forelse ($type as $key => $value)
                          <option {{ old('type') == $value->id ? "selected":"" }} id="tr{{ $value->id }}"  value="{{ $value->id }}">{{ $value->name }}</option>
                          @empty
                          <option value="0">Tidak ada tipe ruangan</option>
                          @endforelse
                        </select>
                    </div>
                  
                    <div class="form-group">
                      <label for="">Fasilitas: </label>
                      <select class="selectpicker form-control" multiple data-live-search="true" name="facilities[]" id="facilities">
                        @foreach ($facilities as $key => $v)
                          <option value="{{ $v->id }}" class="text-capitalize">{{ $v->name }}</option>
                        @endforeach
                      </select>          
                    </div>

                </div>
             
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" name="publish" class="btn btn-primary">
              <i class="fa fa-check"></i> publish
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <i class="fa fa-close"></i> Close
            </button>
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
  <script src="{{ asset('/bs-taginput/bt-tagsinput.min.js')}}"></script>
  <script src="{{ asset('/bs-taginput/typehead.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $(function(){$(document).on('change',':file',function(){var input=$(this),numFiles=input.get(0).files?input.get(0).files.length:1,label=input.val().replace(/\\/g,'/').replace(/.*\//,'');input.trigger('fileselect',[numFiles,label]);});$(document).ready(function(){$(':file').on('fileselect',function(event,numFiles,label){var input=$(this).parents('.input-group-prepend').find(':text'),log=numFiles>1?numFiles+' files selected':label;if(input.length){input.val(log);}else{if(log)alert(log);}});});});

      $('#btn-tambah').on('click', function() {
        $('#form-room').attr('action','{{ route('admin.payment.store') }}')
        $('#method').html('@method('POST')')
        $('#info-update').html('')
        $('#name').html('')
        $('#text-gambar').val('')
      })
    })

      function modalEdit(id) {
        // id = $(this).data('id')
        $.ajax({
          url: "/api/payment/"+id,
          Type: "GET",
          headers : {
            'token': '{{ Session::get('api_token') }}'
          },
          success: function(res) {
            let obj = res.data
            let url = "{{ route('admin.room.update', ':id') }}";
            url = url.replace(':id', id);
            $('#form-room').attr('action',url)
            $('#method').html('@method('PUT')')
            $('#info-update').html('')
            $('#txt-gambar').val(obj.proof)
            $('#name').val(obj.name)
            $('#tr'+obj.order_id).attr('selected',true)
            $('#s_'+obj.status).attr('selected',true)
            $('#labelDate').html('Tanggal Update :')
            $('#MPayment').modal('show')
          }
        })
      }

      function modalDelete(id) {
        // let id = $(this).data('id')
        let url = "{{ route(Request::segment('3')==="trash" ? 'admin.payment.delete.permanent':'admin.payment.delete', ':id') }}";
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

      @error('payment_date')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror
      @error('order')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror
      @error('proof')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror
      @error('status')
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
