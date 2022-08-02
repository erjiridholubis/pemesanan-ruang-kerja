@php $no = 1; @endphp
@extends('admin.layouts.app')
@push('css')
  <!-- Page plugins -->
  <link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
@endpush

@push('btn-control')
  <div class="col-lg-6 col-5 text-right">
    @if (Request::segment(2)=='profile-web' && Request::segment(3)==null)
      <a class="btn btn-neutral btn-sm" href="{{ route('admin.profile.edit') }}" role="button"><i class="fa fa-edit"></i> Edit Profile</a>
    @endif
  </div>
@endpush

@section('content')
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-body">

        @if (Request::segment(2)=='profile-web' && Request::segment(3)=='edit')
          <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="row">
              <div class="col-md-3 text-center mt-4">
                <div class="input-group">
                  <label class="input-group-btn">
                    <span class="btn btn-outline-light">
                      <img src="{{ url($data->logo) }}" alt="" class="img-circle" style="height:120px;">
                      <input type="file" name="icon" id="icon" accept="image/*" style="display: none;" value="{{ old('gambar') }}">
                    </span>
                    <input type="text" class="form-control m-1 pt-1" readonly style="height:21px;">
                  </label>
                </div>
              </div>
              <div class="col-md-9">
                <fieldset class="form-group">
                  <label>Judul Web</label>
                  <input type="text" name="title" class="form-control" value="{{$errors->has('title') ? old('title') : $data->title }}" >
                  @if($errors->has('title'))
                    <div class="text-danger mt-2">
                      {{ $errors->first('title')}}
                    </div>
                  @endif
                </fieldset>
                <fieldset class="form-group">
                  <label>Versi Web</label>
                  <input type="text" name="version" class="form-control" value="{{$errors->has('version') ? old('version') : $data->version }}" >
                  @if($errors->has('version'))
                    <div class="text-danger mt-2">
                      {{ $errors->first('version')}}
                    </div>
                  @endif
                </fieldset>
              </div>

              <div class="col-md-12 mt-3 mb-3">
                <div class="row">
                  <div class="col-md-6">
                    <fieldset class="form-group">
                      <label>Telpon Web</label>
                      <input type="text" name="phone" class="form-control" value="{{$errors->has('phone') ? old('phone') : $data->phone }}" >
                      @if($errors->has('version'))
                        <div class="text-danger mt-2">
                          {{ $errors->first('phone')}}
                        </div>
                      @endif
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <fieldset class="form-group">
                      <label>Email Web</label>
                      <input type="email" name="email" class="form-control" value="{{$errors->has('email') ? old('email_verified_at') : $data->email }}" >
                      @if($errors->has('email'))
                        <div class="text-danger mt-2">
                          {{ $errors->first('email')}}
                        </div>
                      @endif
                    </fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <fieldset class="form-group">
                      <label>Instagram</label>
                      <input type="text" name="instagram" class="form-control" value="{{$errors->has('instagram') ? old('instagram') : $data->ig }}" >
                      @if($errors->has('instagram'))
                        <div class="text-danger mt-2">
                          {{ $errors->first('instagram')}}
                        </div>
                      @endif
                    </fieldset>
                  </div>
                  <div class="col-md-6">
                    <fieldset class="form-group">
                      <label>Line</label>
                      <input type="text" name="line" class="form-control" value="{{$errors->has('line') ? old('line') : $data->line }}" >
                      @if($errors->has('line'))
                        <div class="text-danger mt-2">
                          {{ $errors->first('line')}}
                        </div>
                      @endif
                    </fieldset>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <fieldset class="form-group">
                  <label>Slogan Web</label>
                  <textarea class="form-control" name="slogan" rows="2" style="resize:none;" >{{$errors->has('slogan') ? old('slogan') : $data->slogan }}</textarea>
                  @if($errors->has('slogan'))
                    <div class="text-danger mt-2">
                      {{ $errors->first('slogan')}}
                    </div>
                  @endif
                </fieldset>
                <fieldset class="form-group">
                  <label>Deskripsi Web</label>
                  <textarea class="form-control" name="description" rows="5" style="resize:none;" >{{ $errors->has('description') ? old('description') : $data->description }}</textarea>
                  @if($errors->has('description'))
                    <div class="text-danger mt-2">
                      {{ $errors->first('description')}}
                    </div>
                  @endif
                </fieldset>
              </div>
              <div class="col-md-12 mt-5">
                <label>Thumbnail Web</label>
                <div class="input-group">
                  <label class="input-group-btn">
                    <span class="btn btn-outline-light">
                      <img src="{{ url($data->thumbnail) }}" alt="" class="img-thumbnail center-block">
                      <input type="file" name="thumbnail" id="thumbnail" accept="image/*" style="display: none;" value="{{ old('gambar') }}">
                    </span>
                  </label>
                  <div class="col-md-7">
                    <input type="text" class="form-control m-1 pt-1" readonly style="height:21px;">
                  </div>
                </div>
              </div>
              <div class="col-md-12 mt-4">
                <div class="text-right">
                  <button type="submit" class="btn btn-primary">Update</button>
                  <a class="btn btn-secondary" href="{{ route('admin.profile') }}" role="button">Kembali</a>
                </div>
              </div>
            </div>
          </form>
        @else
          <div class="row">
            <div class="col-md-3 text-center mt-4">
              <img src="{{ url($data->logo) }}" alt="" class="img-circle" style="height:120px;">
            </div>
            <div class="col-md-9">
              <fieldset class="form-group">
                <label>Judul Web</label>
                <input type="text" class="form-control" value="{{ $data->title }}" disabled>
              </fieldset>
              <fieldset class="form-group">
                <label>Versi Web</label>
                <input type="text" class="form-control" value="{{ $data->version }}" disabled>
              </fieldset>
            </div>

            <div class="col-md-12 mt-3 mb-3">
              <div class="row">
                <div class="col-md-6">
                  <fieldset class="form-group">
                    <label>Telpon Web</label>
                    <input type="text" name="phone" class="form-control" value="{{$errors->has('phone') ? old('phone') : $data->phone }}" disabled>
                    @if($errors->has('version'))
                      <div class="text-danger mt-2">
                        {{ $errors->first('phone')}}
                      </div>
                    @endif
                  </fieldset>
                </div>
                <div class="col-md-6">
                  <fieldset class="form-group">
                    <label>Email Web</label>
                    <input type="email" name="email" class="form-control" value="{{$errors->has('email') ? old('email_verified_at') : $data->email }}" disabled>
                    @if($errors->has('email'))
                      <div class="text-danger mt-2">
                        {{ $errors->first('email')}}
                      </div>
                    @endif
                  </fieldset>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <fieldset class="form-group">
                    <label>Instagram</label>
                    <input type="text" name="instagram" class="form-control" value="{{$errors->has('instagram') ? old('instagram') : $data->ig }}" disabled>
                    @if($errors->has('instagram'))
                      <div class="text-danger mt-2">
                        {{ $errors->first('instagram')}}
                      </div>
                    @endif
                  </fieldset>
                </div>
                <div class="col-md-6">
                  <fieldset class="form-group">
                    <label>Line</label>
                    <input type="text" name="line" class="form-control" value="{{$errors->has('line') ? old('line') : $data->line }}" disabled>
                    @if($errors->has('line'))
                      <div class="text-danger mt-2">
                        {{ $errors->first('line')}}
                      </div>
                    @endif
                  </fieldset>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <fieldset class="form-group">
                <label>Slogan Web</label>
                <textarea class="form-control" name="name" rows="2" style="resize:none;" disabled>{{ $data->slogan }}</textarea>
              </fieldset>
              <fieldset class="form-group">
                <label>Deskripsi Web</label>
                <textarea class="form-control" name="name" rows="8" style="resize:none;" disabled>{{ $data->description }}</textarea>
              </fieldset>
            </div>
            <div class="col-md-12 mt-5">
              <label>Thumbnail Web</label>
              <br>
              <img src="{{ url($data->thumbnail) }}" alt="" class="img-thumbnail center-block">
            </div>
          </div>
        @endif

    </div>
  </div>
@endsection

@push('script')
  <script type="text/javascript">
    @if (Request::segment(2)=='profile-web' && Request::segment(3)=='edit')
      $(function(){$(document).on('change',':file',function(){var input=$(this),numFiles=input.get(0).files?input.get(0).files.length:1,label=input.val().replace(/\\/g,'/').replace(/.*\//,'');input.trigger('fileselect',[numFiles,label]);});$(document).ready(function(){$(':file').on('fileselect',function(event,numFiles,label){var input=$(this).parents('.input-group').find(':text'),log=numFiles>1?numFiles+' files selected':label;if(input.length){input.val(log);}else{if(log)alert(log);}});});});
    @endif
  </script>
@endpush
