@php $no = 1; @endphp
@extends('admin.layouts.app')
@push('css')
  <!-- Page plugins -->
  <link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
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
                <th>Orderan</th>
                <th>Tanggal Bayar</th>
                <th>Bukti Pembayaran</th>
                <th>Tenggat Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>

              @forelse ($data as $key => $value)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>#{{ $value->order->id }}</td>
                  <td>{{ $value->payment_date }}</td>
                  <td>
                  <div class=" text-center">
                    <img src="/uploads/proof/{{ $value->proof }}" alt="orderan-{{ $value->order->id }}" class="img-rounded center-block" style="width:30%;">
                  </div>
                </td>
                  <td>{{ $value->payment_deadline }}</td>
                  <td>
                    @if (Request::segment(3)!='trash')
                      @if ($value->status=='pending')
                        <a title="Approve Pembayaran" {!! $value->status=="pending" ? 'class="btn btn-success btn-sm " href="'.route('admin.payment.approve',$value->id).'"' : 'class="btn btn-success btn-sm disabled" href= "#" disabled' !!}><i class="fa fa-check"></i></a>
                        <a title="Batalkan Pembayaran" {!! $value->status=="pending" ? 'class="btn btn-danger btn-sm " href="'.route('admin.payment.deceline',$value->id).'"' : 'class="btn btn-danger btn-sm disabled" href= "#" disabled' !!}><i class="fa fa-ban"></i></a>
                      @else
                        <span class="badge badge-pill badge-primary">{{ $value->status }}</span>
                      @endif
                    @else
                      <span>{{ $value->status }}</span>
                    @endif
                  </td>
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
                      {{-- <select class="form-control" id="type" name="type">
                          @forelse ($type as $key => $value)
                          <option {{ old('type') == $value->id ? "selected":"" }} id="tr{{ $value->id }}"  value="{{ $value->id }}">{{ $value->name }}</option>
                          @empty
                          <option value="0">Tidak ada tipe ruangan</option>
                          @endforelse --}}
                        </select>
                    </div>
                  
                    <div class="form-group">
                      <label for="">Fasilitas: </label>
                      <select class="selectpicker form-control" multiple data-live-search="true" name="facilities[]" id="facilities">
                        {{-- @foreach ($facilities as $key => $v)
                          <option value="{{ $v->id }}" class="text-capitalize">{{ $v->name }}</option>
                        @endforeach --}}
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
  {{-- <script src="/assets/vendor/tinymce/js/tinymce/tinymce.min.js"></script> --}}
  <script src="https://cdn.tiny.cloud/1/0gf848yxsaw9w14nleypbdrxzmrjhjoy01oy25afzac4az1f/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script type="text/javascript">
    tinymce.init({selector:'#mytextarea',plugins : 'autoresize',width: '100%',height: 450,autoresize_min_height: 450,autoresize_max_height: 900,plugins:'media image imagetools lists link advlist table textpattern anchor hr preview wordcount directionality code',imagetools_toolbar:"rotateleft rotateright | flipv fliph | editimage imageoptions",imagetools_cors_hosts:['portalcoding.com','portalcoding.id'],imagetools_proxy:'/proxy.php',toolbar:'fontselect formatselect link unlink blockquote image anchor preview code | alignleft aligncenter alignright alignjustify numlist bullist table ',toolbar_drawer:'sliding',tinycomments_mode:'embedded',tinycomments_author:'Portal Coding',media_live_embeds:true,image_caption:true,force_p_newlines:false,});

    $(document).ready(function() {
        $(function(){$(document).on('change',':file',function(){var input=$(this),numFiles=input.get(0).files?input.get(0).files.length:1,label=input.val().replace(/\\/g,'/').replace(/.*\//,'');input.trigger('fileselect',[numFiles,label]);});$(document).ready(function(){$(':file').on('fileselect',function(event,numFiles,label){var input=$(this).parents('.input-group-prepend').find(':text'),log=numFiles>1?numFiles+' files selected':label;if(input.length){input.val(log);}else{if(log)alert(log);}});});});
        $('select').selectpicker('deselectAll');

        $('#btn-tambah').on('click', function() {
            $('#form-room').attr('action','{{ route('admin.room.store') }}')
            $('#method').html('@method('POST')')
            $('#info-update').html('')
            $('#text-image').val('')
            $('#name').val('')
        })
    })


      function modalEdit(id) {
        $('#tags').selectpicker();
        $(".strings").val('');
        $.ajax({
            url: "api/room/"+id+"/json_tags"
            method: "GET",
            data :{package_id:id},
            cache:false,
            headers : {
              'token': '{{ Session::get('api_token') }}'
            },
            success : function(data){
                var item=data;
                var val1=item.replace("[","");
                var val2=val1.replace("]","");
                var values=val2;
                $.each(values.split(","), function(i,e){
                    $(".strings option[value='" + e + "']").prop("selected", true).trigger('change');
                    $(".strings").selectpicker('refresh');
                });
            }
        });
        
        // id = $(this).data('id')
        $.ajax({
          url: "/api/room/"+id,
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
            $('#txt-image').val(obj.image)
            $('#name').val(obj.name)
            $('#tr'+obj.type_id).attr('selected',true)
            $('#MRoom').modal('show')
          }
        })
      }

      function modalDelete(id) {
        // let id = $(this).data('id')
        let url = "{{ route(Request::segment('3')==="trash" ? 'admin.room.delete.permanent':'admin.room.delete', ':id') }}";
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
      @error('type')
        swal({
          title: 'Kesalahan !',
          text: '{{ $message }}',
          icon: 'error',
          type: "error",
          buttons: "Tutup",
        })
      @enderror
      @error('image')
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
