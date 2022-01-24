@extends('layout.app')

@section('title', $blog->title)

@section('content')

<!-- BANNER -->
<div class="blog_banner">

</div>
<!-- BANNER -->

<!-- BODY -->
<div class="blog_post_sec_all">
  <div class="container">
    <div class="row">
      <div class="cl-12 col-md-12 col-lg-9">
        <div class="blog_post_left">
          <div class="blog_post_sec">
            @if (count($blog->category) > 0)
            <div class="blog_post_top d-flex justify-content-between">
              <ul class="blog_post_top_ul">
                @foreach ($blog->category as $c)
                <li>
                  <a href="#">{{ $c->categoryName }}</a>
                </li>
                @endforeach
              </ul>
              <span>{{ \Carbon\Carbon::parse($blog->updated_at)->isoFormat('D MMM, YYYY')}}</span>
            </div>
            @endif
            <div class="blog_post">
              <h1 class="blog_post_h1">{{ $blog->title }}</h1>
              <div class="post_author_sec">
                <div class="post_author_left">
                  <div class="post_author_img">
                    <img src="{{ Gravatar::get($blog->user->email) }}" alt="{{ $blog->user->fullName }}">
                  </div>
                  <div class="post_author_info">
                    <a href="#">
                      <h4 class="post_author_title">{{ $blog->user->fullName }}</h4>
                    </a>
                    <p>Email: {{ $blog->user->email }}</p>
                  </div>
                </div>
                <div class="post_author_r8">
                  <i class="fas fa-share-alt"></i>
                </div>
              </div>

              {!!$blog->post!!}

              <div class="riview_post">
                <ul class="riview_post_ul">
                  <li><i class="far fa-thumbs-up"></i>122 Like</li>
                  <li><i class="far fa-comments"></i>10 Comment</li>
                  <li><i class="far fa-share-square"></i>4 Share</li>
                </ul>
              </div>

            </div>
          </div>

          <comment-section></comment-section>

          <!--WRITE COMMENT BOX -->
          <div class="cmnt_frm mar_t30">
            <h2 class="post_dtls_title2 pad_b20">Leave A Comment</h2>
            <div class="cmnt_frm_all">
              <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                  <div class="cmnt_input">
                    <p>NAME</p>
                    <input type="text" placeholder="Enter your name">
                  </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <div class="cmnt_input">
                    <p>E-MAIL</p>
                    <input type="text" placeholder="Enter your E-MAIL">
                  </div>
                </div>
                <div class="col-12 col-md-12 col-lg-12">
                  <div class="cmnt_input">
                    <p class="mar_b10">MESSAGE</p>
                    <textarea placeholder="Type your comment" name="message" required=""></textarea>
                  </div>
                </div>
                <div class="dtls_frm_btn mar_t20">
                  <button class="btn1">send message</button>
                </div>
              </div>
            </div>
          </div>
          <!--WRITE COMMENT BOX -->
        </div>
      </div>

      <div class="col-12 col-md-12 col-lg-3">
        <div class="blog_post_r8">
          <h4 class="trnd_artcl_h4">RELATED ARTICLES</h4>
          <div class="blog_post_r8_all">
            <!-- iteam -->
            @if (count($relatedBlogs) > 0)
            @foreach ($relatedBlogs as $item)
            <div class="blog_post_r8_item">
              <div class="blog_post_item_lft">
                <img src="img/man3.jpg" alt="{{ $item->title }}">
              </div>
              <div class="blog_post_item_r8">
                <a href="/blog/{{ $item->slug }}">
                  <h4 class="blog_post_item_r8_h4">
                    {{ $item->title }}
                  </h4>
                </a>
                <a href="">
                  <p class="author_name2">-{{ $item->user->fullName }}</p>
                </a>
              </div>
            </div>
            @endforeach
            @endif

            <!-- iteam -->
          </div>

          <!-- Share this post -->
          <div class="course_price mar_t60">
            <div class="course_price_top">
              <p>Share this post</p>
            </div>
            <div class="course_price_main" style="padding: 30px 0px 30px 17px">
              <ul class="share_social_ul dis_flx">
                <li>
                  <a class="fb" href="">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                </li>
                <li>
                  <a class="google" href="">
                    <i class="fab fa-google-plus-g"></i>
                  </a>
                </li>
                <li>
                  <a class="instgrm" href="">
                    <i class="fab fa-instagram"></i>
                  </a>
                </li>
                <li>
                  <a class="twtr" href="">
                    <i class="fab fa-twitter"></i>
                  </a>
                </li>
                <li>
                  <a class="skpye" href="">
                    <i class="fab fa-skype"></i>
                  </a>
                </li>
                <li>
                  <a class="utube" href="">
                    <i class="fab fa-youtube"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <!-- TAGS -->
          @if (count($blog->tag) > 0)
          <div class="post_tags">
            <h3 class="post_tags_h3">Popular Tags</h3>
            <ul class="post_tags_ul">
              @foreach ($blog->tag as $t)
              <li>
                <a href="/tag/{{ $t->tagName }}/{{ $t->id }}">{{ $t->tagName }}</a>
              </li>
              @endforeach
            </ul>
          </div>
          @endif
          <!-- END OF TAGS -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- BODY -->

@endsection