<!DOCTYPE html>
<html lang="en">

    @include('layouts.partials.head')

    <body class="skin-blue sidebar-mini fix-sidebar">
        <div class="wrapper">

            @include('layouts.partials.mainheader')

            @include('layouts.partials.sidebar')

            <div class="content-wrapper">

                @include('layouts.partials.contentheader')

                <section class="content">
                    @yield('main-content')
                </section>

            </div>

            @include('layouts.partials.footer')

            @include('layouts.partials.controlsidebar')

        </div>
        @include('layouts.modal.confirm_delete')
    </body>

    @include('layouts.partials.scripts')
</html>
