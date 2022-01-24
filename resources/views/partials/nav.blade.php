<!-- HEADER -->
<div style="position: relative;">
  <div class="header">
    <div class="menu_all" id="myHeader">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-4 col-md-4 col-lg-4">
            <div class="logo">
              <a href="{{ route('home') }}">
                <div class="logo_img">
                  {{-- <img src="img/logo.png" alt="image"> --}}
                  <span style="font-size: 24px">LOGO</span>
                </div>
              </a>
            </div>
          </div>
          <div class="col-8 col-md-8 col-lg-8">
            <div class="menu_right d-flex">
              <div class="menu_right_list">
                <ul class="menu_right_ul d-flex">
                  <li class="dis_fx_cntr">
                    <a href="{{ route('home') }}">HOME</a>
                  </li>
                  @if (count($categories) > 0)
                  @foreach ($categories as $category)
                  <li>
                    <a href="/category/{{ $category->categoryName }}/{{ $category->id }}">{{ $category->categoryName
                      }}</a>
                  </li>
                  @endforeach
                  @endif
                  <li>
                    <a href="#">about</a>
                  </li>

                  <li>
                    <a href="#">CONTACT</a>
                  </li>
                </ul>
              </div>
              <search-page />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- HEADER -->