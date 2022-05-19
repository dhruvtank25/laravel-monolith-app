@extends('layouts.main')

@section('styles')
@endsection

@section('content')
    <!-- begin table -->
    <div class="">
        <div class="banner_wrapper" style="background-image:url('{{ FileUploadHelper::getDocPath($category->banner, 'cat_banner') }}')">
            <div class="banner_body">
                <div class="category_icon">
                    {!! $category->icon !!}
                </div>
                <div class="category_title">
                    <h2>
                        {{$category->title}}
                    </h2>
                    <div class="text-center">
                        <a class="text-white" href="{{route('coach-search', ['url_slug'=>$category->url_slug])}}">{{route('coach-search', ['title'=>$category->url_slug])}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="category_shortdesc m-t-20">
          {{--   <h2>Short Description</h2> --}}
            {!! $category->short_description !!}
        </div>
        <div class="long_desc">
            {!! $category->description !!}
            <div class="clearfix"></div>
        </div>
    </div>
    
    <!-- end table -->
@endsection

@section('scripts')
@endsection
