@extends('Supplier/Layouts/supplierlayout')

@section('title', 'Control Panel') {{--TITLE GOES HERE--}}

@section('pageheadcontent')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-body">
                    @if(isset($allSections))
                        @foreach($allSections as $section)
                            <div class="col-md-4">
                                <a href="/supplier/manage-settings/{{$section->name}}" class="btn btn-lg ">
                                    <h3>{{str_replace('_',' ',$section->name)}}</h3></a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('pagejavascripts')

@endsection
