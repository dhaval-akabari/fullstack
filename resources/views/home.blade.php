@extends('layout.app')

@section('title', 'Home')

@section('content')

<!-- BANNER -->
<section class="banner_sec">
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-10 col-lg-8 mx-auto">
        <div class="row">
          @if (count($categories) > 0)
          @foreach ($categories as $category)
          <div class="col-12 col-md-4 col-lg-4">
            <a href="/category/{{ $category->categoryName }}/{{ $category->id }}">
              <div class="banner_box">
                <i class="fab fa-laravel"></i>
                <h3 class="banner_box_h3">{{ $category->categoryName }}</h3>
              </div>
            </a>
          </div>
          @endforeach
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
<!-- BANNER -->

<!-- BODY -->
<div class="home_body">
  <div class="container">
    <div class="latest_post">
      <div class="latest_post_top">
        <h1 class="latest_post_h1 brdr_line">Latest Blog</h1>
      </div>
      <div class="row">
        @if (count($blogs) > 0)
        @foreach ($blogs as $blog)
        <div class="col-12 col-md-6 col-lg-4">
          <a href="/blog/{{ $blog->slug }}">
            <div class="home_card">
              <div class="home_card_top">
                <img src="img/card3.jpg" alt="image">
              </div>
              <div class="home_card_bottom">
                <div class="home_card_bottom_text">
                  @if (count($blog->category))
                  <ul class="home_card_bottom_text_ul">
                    @foreach ($blog->category as $cat)
                    <li class="mr-2">
                      <a href="/category/{{ $cat->categoryName }}/{{ $cat->id }}">{{ $cat->categoryName }}</a>
                    </li>
                    @endforeach
                  </ul>
                  @endif
                  <a href="/blog/{{ $blog->slug }}">
                    <h2 class="home_card_h2">{{ $blog->title }}</h2>
                  </a>
                  <p class="post_p">
                    {{ $blog->post_excerpt }}
                  </p>
                  <div class="home_card_bottom_tym">
                    <div class="home_card_btm_left">
                      <img src="{{ Gravatar::get($blog->user->email, 'small-secure') }}"
                        alt="{{ $blog->user->fullName }}">
                    </div>
                    <div class="home_card_btm_r8">
                      <a href="contact_me.html">
                        <p class="author_name">{{ $blog->user->fullName }}</p>
                      </a>
                      <ul class="home_card_btm_r8_ul">
                        <li>{{ \Carbon\Carbon::parse($blog->updated_at)->isoFormat('MMM D, YYYY')}}</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
        @endforeach
        @endif
      </div>
      <div class="text-center">
        <a href="{{ route('all.blogs') }}" class="btn btn-primary">View All Blogs</a>
      </div>
    </div>
  </div>
</div>
<!-- BODY -->
@endsection