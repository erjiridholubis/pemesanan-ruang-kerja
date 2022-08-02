@php $totalSegsCount = count(Request::segments()); @endphp

<div class="row align-items-center py-4">
  <div class="col-lg-6 col-7">
    <h6 class="h2 text-white d-inline-block mb-0">{{ Request::segment('2')=="home" ? "":$title }}</h6>
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
      <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
        @for ($i=1; $i <= $totalSegsCount; $i++)<li class="breadcrumb-item {{ $i == $totalSegsCount ? "active":"" }}" {!! $i == $totalSegsCount ? "aria-current='page'":"" !!} ><a href="#">{{ str_replace("-"," ",ucfirst(Request::segment($i))) }}</a></li>@endfor
      </ol>
    </nav>
  </div>
  @stack('btn-control')
</div>
