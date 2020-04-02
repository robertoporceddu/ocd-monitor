<!-- Content Header (Page header) -->
<section class="content-header">
    @if(session('flash_message'))
    <div class="alert alert-success alert-dismissible" style="position: absolute; right: 1em; z-index:100; width: 30em; margin-top: -13px">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4 style="margin: 0">{!! session('flash_message') !!}</h4>
    </div>
    @endif

    @if(session('warning_message'))
    <div class="alert alert-warning alert-dismissible" style="position: absolute; right: 1em; z-index:100; width: 30em; margin-top: -13px">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h3 style="margin-top: 0"><i class="fa fa-warning"></i> {!! trans('messages.warning') !!}</h3>
        <h4 style="margin: 0">{!! session('warning_message') !!}</h4>
    </div>
    @endif

    <h1>
        @yield('contentheader_title', '')
        <small>@yield('contentheader_description')</small>
    </h1>

    <ol class="breadcrumb">
        <li><i class="fa fa-fw fa-dashboard"></i> <a href="/">Home</a></li>
        @if(isset($breadcrumbs))
            @foreach($breadcrumbs as $breadcrumb)
                @if(!is_null($breadcrumb))
                    @if(isset($breadcrumb['active']) and $breadcrumb)
                        <li class="active">{!! $breadcrumb['title'] !!}</li>
                    @else
                        <li><a href="{{ $breadcrumb['link'] }}">{!! $breadcrumb['title'] !!}</a></li>
                    @endif
                @endif
            @endforeach
        @endif
    </ol>
</section>

