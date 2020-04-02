<!DOCTYPE html>
<html lang="en">

    @include('layouts.partials.head')

    <body class="skin-blue sidebar-mini fix-sidebar">
        <div class="wrapper">

            @include('layouts.partials.mainheader')

            @include('layouts.partials.sidebar')

            <div class="content-wrapper" style="overflow: auto;">
                @include('layouts.partials.contentheader')
                @yield('main-content')
                
                <section class="content text-center">
                    @yield('content-octopus')
                </section>

            </div>

            @include('layouts.partials.controlsidebar')

            @include('layouts.partials.footer')



        </div>
        @include('layouts.modal.confirm_delete')




    </body>
    @include('layouts.partials.scripts_js')
    @include('layouts.partials.scripts')
    @yield('js')
</html>
