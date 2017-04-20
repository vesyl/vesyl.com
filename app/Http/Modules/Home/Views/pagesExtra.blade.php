{{--{{dd($returnData['pageDetail']->data->page_title)}}--}}
{{--        {{dd($file)}}--}}
@extends('Home/Layouts/home_layout')
@section('pageheadcontent')
    {{--OPTIONAL--}}
    <!--todo show based on code-->
    {{--<title>FlashSale | @yield($pageTitles->page_title)</title>--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
@endsection

@section('content')
    <div class="clearfix"></div>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <ul class="list-group">
                        @if($returnData['pageDetail']->code==200)
                            @foreach($returnData['pageTitles']->data as $Title)
                                <li class="list-group-item"><i class="fa fa-angle-right"></i>
                                    <a href="/pages/{{$Title->page_title}}" style="color: black;font-family:Arial Black ; "> {{$Title->page_title}}</a>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item"><i class="fa fa-angle-right"></i>
                                <a href="/" style="color: black;font-family:Arial Black ; "> </a></li>
                        @endif


                    </ul>
                </div>
                <div class="col-lg-9">
                    <h3>{{$returnData['pageDetail']->data->page_title}}</h3>
                    {!! str_replace('_', ' ', $file) !!}
                </div>

            </div>
        </div>
    </div>

@endsection