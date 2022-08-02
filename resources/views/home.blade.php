@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="section-heading text-uppercase">Beasiswa</h1>
      </div>
    </div>
    </div>


    <div class="container text-center my-3">
      <div class="col-6 text-right ml-auto">
        <a class="btn btn-primary mb-3 mr-1" href="#recipeCarouselBeasiswa" role="button" data-slide="prev">
          <i class="fa fa-arrow-left"></i>
        </a>
        <a class="btn btn-primary mb-3 " href="#recipeCarouselBeasiswa" role="button" data-slide="next">
          <i class="fa fa-arrow-right"></i>
        </a>

      </div>

        <div class="row">

            <div id="recipeCarouselBeasiswa" class="carousel slide w-100" data-ride="carousel">
                <div class="carousel-inner w-100 pt-3" role="listbox">

                    @forelse ($scholarships as $key => $value)
                      <div class="carousel-item {{ $key == 0 ? "active":"" }}">
                          <div class="col-md-4">

                              <div class="card">
                                <!--Card image-->
                                <div class="view overlay">
                                  <img src="{{ asset('uploads/posts/'.$value->thumbnail) }}" class="card-img-top" alt="{{ $value->title }}">
                                  <a href="{{ route('post.show',$value->slug) }}" >
                                    <div class="mask rgba-white-slight"></div>
                                  </a>
                                </div>
                                <!--Card content-->
                                <div class="card-body">
                                  <!--Title-->
                                  <a href="{{ route('post.show',$value->slug) }}" >
                                    <h3 class="card-title" style="height:150px;">{{ $value->title }}</h3>
                                  </a>
                                  <!--Text-->
                                  <p class="text-center"><a href="#" class="btn btn-primary btn-md ">
                                    <i class="fas fa-play ml-2"></i> {{ $value->label->label }}
                                  </a></p>
                                  <p class="card-text">
                                    <img class="author-photo" height="32" src="{{ $logo }}" width="32">
                                    <a href="#" class="text-capitalize">{{ $value->user->name }}</a>
                                  </p>
                                </div>
                              </div>

                          </div>
                      </div>
                    @empty
                      <div class="col-lg-12 text-center">
                        <h2 class="text-center">Data Belum tersedia <i class="far fa-frown"></i></h2>
                      </div>
                    @endforelse

                </div>

            </div>
        </div>

    </div>

    <div class="row">
       <div class="col-lg-8 mx-auto text-center">
         <a href="{{ route('staff') }}" class="btn btn-outline-default btn-dash">
           Lihat Seluruh Beasiswa
         </a>
       </div>
     </div>

    <hr class="mb-5 mt-5">

  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="section-heading text-uppercase">Lomba</h1>
      </div>
    </div>
    </div>


    <div class="container text-center my-3">
      <div class="col-6 text-right ml-auto">
        <a class="btn btn-primary mb-3 mr-1" href="#recipeCarouselLomba" role="button" data-slide="prev">
          <i class="fa fa-arrow-left"></i>
        </a>
        <a class="btn btn-primary mb-3 " href="#recipeCarouselLomba" role="button" data-slide="next">
          <i class="fa fa-arrow-right"></i>
        </a>

      </div>

        <div class="row">

            <div id="recipeCarouselLomba" class="carousel slide w-100" data-ride="carousel">
                <div class="carousel-inner w-100 pt-3" role="listbox">

                  @forelse ($competitions as $key => $value)
                    <div class="carousel-item {{ $key == 0 ? "active":"" }}">
                        <div class="col-md-4">

                            <div class="card">
                              <!--Card image-->
                              <div class="view overlay">
                                <img src="{{ asset('uploads/posts/'.$value->thumbnail) }}" class="card-img-top" alt="{{ $value->title }}">
                                <a href="{{ route('post.show',$value->slug) }}" >
                                  <div class="mask rgba-white-slight"></div>
                                </a>
                              </div>
                              <!--Card content-->
                              <div class="card-body">
                                <!--Title-->
                                <a href="{{ route('post.show',$value->slug) }}" >
                                  <h3 class="card-title" style="height:150px;">{{ $value->title }}</h3>
                                </a>
                                <!--Text-->
                                <p class="text-center"><a href="#" class="btn btn-primary btn-md ">
                                  <i class="fas fa-play ml-2"></i> {{ $value->label->label }}
                                </a></p>
                                <p class="card-text">
                                  <img class="author-photo" height="32" src="{{ $logo }}" width="32">
                                  <a href="#" class="text-capitalize">{{ $value->user->name }}</a>
                                </p>
                              </div>
                            </div>

                        </div>
                    </div>
                  @empty
                    <div class="col-lg-12 text-center">
                      <h2 class="text-center">Data Belum tersedia <i class="far fa-frown"></i></h2>
                    </div>
                  @endforelse

                </div>

            </div>
        </div>

    </div>

    <div class="row">
       <div class="col-lg-8 mx-auto text-center">
         <a href="{{ route('staff') }}" class="btn btn-outline-default btn-dash">
           Lihat Seluruh Lomba
         </a>
       </div>
     </div>

    <hr class="mb-5 mt-5">

  <div class="container">
    <div class="row">
      <div class="col-lg-12 text-center">
        <h1 class="section-heading text-uppercase">Lowongan</h1>
      </div>
    </div>
    </div>


    <div class="container text-center my-3">
      <div class="col-6 text-right ml-auto">
        <a class="btn btn-primary mb-3 mr-1" href="#recipeCarouselLowongan" role="button" data-slide="prev">
          <i class="fa fa-arrow-left"></i>
        </a>
        <a class="btn btn-primary mb-3 " href="#recipeCarouselLowongan" role="button" data-slide="next">
          <i class="fa fa-arrow-right"></i>
        </a>

      </div>

        <div class="row">

            <div id="recipeCarouselLowongan" class="carousel slide w-100" data-ride="carousel">
                <div class="carousel-inner w-100 pt-3" role="listbox">

                  @forelse ($vacancies as $key => $value)
                    <div class="carousel-item {{ $key == 0 ? "active":"" }}">
                        <div class="col-md-4">

                            <div class="card">
                              <!--Card image-->
                              <div class="view overlay">
                                <img src="{{ asset('uploads/posts/'.$value->thumbnail) }}" class="card-img-top" alt="{{ $value->title }}">
                                <a href="{{ route('post.show',$value->slug) }}" >
                                  <div class="mask rgba-white-slight"></div>
                                </a>
                              </div>
                              <!--Card content-->
                              <div class="card-body">
                                <!--Title-->
                                <a href="{{ route('post.show',$value->slug) }}" >
                                  <h3 class="card-title" style="height:150px;">{{ $value->title }}</h3>
                                </a>
                                <!--Text-->
                                <p class="text-center"><a href="#" class="btn btn-primary btn-md ">
                                  <i class="fas fa-play ml-2"></i> {{ $value->label->label }}
                                </a></p>
                                <p class="card-text">
                                  <img class="author-photo" height="32" src="{{ $logo }}" width="32">
                                  <a href="#" class="text-capitalize">{{ $value->user->name }}</a>
                                </p>
                              </div>
                            </div>

                        </div>
                    </div>
                  @empty
                    <div class="col-lg-12 text-center">
                      <h2 class="text-center">Data Belum tersedia <i class="far fa-frown"></i></h2>
                    </div>
                  @endforelse

                </div>

            </div>
        </div>

    </div>

    <div class="row">
       <div class="col-lg-8 mx-auto text-center">
         <a href="{{ route('staff') }}" class="btn btn-outline-default btn-dash">
           Lihat Seluruh Lowongan
         </a>
       </div>
     </div>

    <hr class="mb-5 mt-5">

    <div class="container">
      <div class="row">


        <div class="col-md-6">
          <div class="container">
            <div class="row">
              <div class="col-lg-12 text-center">
                <h1 class="section-heading text-uppercase">Prestasi</h1>
              </div>
            </div>
            </div>


            <div class="container text-center my-3">
              <div class="col-6 text-right ml-auto">
                <a class="btn btn-primary mb-3 mr-1" href="#recipeCarouselPrestasi" role="button" data-slide="prev">
                  <i class="fa fa-arrow-left"></i>
                </a>
                <a class="btn btn-primary mb-3 " href="#recipeCarouselPrestasi" role="button" data-slide="next">
                  <i class="fa fa-arrow-right"></i>
                </a>

              </div>

                <div class="row">

                    <div id="recipeCarouselPrestasi" class="carousel slide w-100" data-ride="carousel">
                        <div class="carousel-inner w-100 pt-3" role="listbox">

                          @forelse ($prestations as $key => $value)
                            <div class="carousel-item {{ $key == 0 ? "active":"" }}">
                                <div class="col-md-6">

                                    <div class="card">
                                      <!--Card image-->
                                      <div class="view overlay">
                                        <img src="{{ asset('uploads/posts/'.$value->thumbnail) }}" class="card-img-top" alt="{{ $value->title }}">
                                        <a href="{{ route('post.show',$value->slug) }}" >
                                          <div class="mask rgba-white-slight"></div>
                                        </a>
                                      </div>
                                      <!--Card content-->
                                      <div class="card-body">
                                        <!--Title-->
                                        <a href="{{ route('post.show',$value->slug) }}" >
                                          <h3 class="card-title" style="height:150px;">{{ $value->title }}</h3>
                                        </a>
                                        <!--Text-->
                                        <p class="text-center"><a href="#" class="btn btn-primary btn-md ">
                                          <i class="fas fa-play ml-2"></i> {{ $value->label->label }}
                                        </a></p>
                                        <p class="card-text">
                                          <img class="author-photo" height="32" src="{{ $logo }}" width="32">
                                          <a href="#" class="text-capitalize">{{ $value->user->name }}</a>
                                        </p>
                                      </div>
                                    </div>

                                </div>
                            </div>
                          @empty
                            <div class="col-lg-12 text-center">
                              <h2 class="text-center">Data Belum tersedia <i class="far fa-frown"></i></h2>
                            </div>
                          @endforelse

                        </div>

                    </div>
                </div>

            </div>

            <div class="row">
               <div class="col-lg-8 mx-auto text-center">
                 <a href="{{ route('staff') }}" class="btn btn-outline-default btn-dash">
                   Lihat Seluruh Prestasi
                 </a>
               </div>
             </div>
        </div>

        <div class="col-md-6">
          <div class="container">
            <div class="row">
              <div class="col-lg-12 text-center">
                <h1 class="section-heading text-uppercase">Survei</h1>
              </div>
            </div>
            </div>


            <div class="container text-center my-3">
              <div class="col-6 text-right ml-auto">
                <a class="btn btn-primary mb-3 mr-1" href="#recipeCarouselSurvei" role="button" data-slide="prev">
                  <i class="fa fa-arrow-left"></i>
                </a>
                <a class="btn btn-primary mb-3 " href="#recipeCarouselSurvei" role="button" data-slide="next">
                  <i class="fa fa-arrow-right"></i>
                </a>

              </div>

                <div class="row">

                    <div id="recipeCarouselSurvei" class="carousel slide w-100" data-ride="carousel">
                        <div class="carousel-inner w-100 pt-3" role="listbox">

                          @forelse ($surveys as $key => $value)
                            <div class="carousel-item {{ $key == 0 ? "active":"" }}">
                                <div class="col-md-6">

                                    <div class="card">
                                      <!--Card image-->
                                      <div class="view overlay">
                                        <img src="{{ asset('uploads/posts/'.$value->thumbnail) }}" class="card-img-top" alt="{{ $value->title }}">
                                        <a href="{{ route('post.show',$value->slug) }}" >
                                          <div class="mask rgba-white-slight"></div>
                                        </a>
                                      </div>
                                      <!--Card content-->
                                      <div class="card-body">
                                        <!--Title-->
                                        <a href="{{ route('post.show',$value->slug) }}" >
                                          <h3 class="card-title" style="height:150px;">{{ $value->title }}</h3>
                                        </a>
                                        <!--Text-->
                                        <p class="text-center"><a href="#" class="btn btn-primary btn-md ">
                                          <i class="fas fa-play ml-2"></i> {{ $value->label->label }}
                                        </a></p>
                                        <p class="card-text">
                                          <img class="author-photo" height="32" src="{{ $logo }}" width="32">
                                          <a href="#" class="text-capitalize">{{ $value->user->name }}</a>
                                        </p>
                                      </div>
                                    </div>

                                </div>
                            </div>
                          @empty
                            <div class="col-lg-12 text-center">
                              <h2 class="text-center">Data Belum tersedia <i class="far fa-frown"></i></h2>
                            </div>
                          @endforelse

                        </div>

                    </div>
                </div>

            </div>

            <div class="row">
               <div class="col-lg-8 mx-auto text-center">
                 <a href="{{ route('staff') }}" class="btn btn-outline-default btn-dash">
                   Lihat Seluruh Survei
                 </a>
               </div>
             </div>
        </div>



      </div>
    </div>

    <hr class="mb-5 mt-5">

      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h1 class="section-heading text-uppercase">Staff BEM KM ILKOM UNSRI 2020</h1>
          </div>
        </div>
      </div>

      <!-- Video Slideshow -->
      <div class="py-5">
          <div class="container">
              <div class="row">

                @forelse ($coreStaff as $key => $value)
                <div class="col-md-3 mb-3">
                    <div class="container team-member">
                      <div class="col-md-10 mb-5">
                        <img class="mx-auto rounded-circle" style="height:200px;width:200px;" src="{{ asset('uploads/staff/'.$value->photo) }}" alt="">
                      </div>
                      <h4>{{ $value->full_name }}</h4>
                      <p class="text-muted">{{ $value->major." : ".$value->force }}</p>
                      <p class="text-muted">Jabatan : {{ $value->position->position }}</p>
                    </div>
                  </div>
                @empty
                  <div class="col-lg-12 text-center">
                    <h2 class="text-center">Data Belum tersedia <i class="far fa-frown"></i></h2>
                  </div>
                @endforelse


              </div>
          </div>
      </div>

      <div class="row">
         <div class="col-lg-8 mx-auto text-center">
           <a href="{{ route('staff') }}" class="btn btn-outline-default btn-dash">
             Lihat Seluruh Staff
           </a>
         </div>
       </div>
@endsection
