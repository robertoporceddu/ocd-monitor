<script type="text/javascript">
    resize();

    moment.locale('{{ app()->getLocale()}}');

    $(window).resize(function () {
       resize()
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (!Notification) {
            alert('Desktop notifications not available in your browser. Try Chromium.');
            return;
        }

        if (Notification.permission !== "granted")
            Notification.requestPermission();
    });

    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

    $(function(){
        $('a[title]').tooltip();

        $('.date').each(function (index, domElem) {
            $(domElem).find('input').daterangepicker({
                applyLabel: 'Applica',
                cancelLabel: 'Annulla',
                autoApply: true,
                singleDatePicker: true,
                timePicker24Hour: true,
                showDropdowns: true,
                timePicker: true,
                timePickerIncrement: 15,
                locale: {
                    format: 'DD/MM/YYYY HH:mm:ss'
                }
            });
        })
    });



    //showNotification('{{ action('NotificationController@toRead') }}', 60000);
    window.setTimeout(function(){ $('.alert-dismissible').addClass('animated fadeOutRight'); },15000);

    $('.scrollable').slimScroll({
        position: 'left',
        height: ($(window).height()-150) + 'px',
        railVisible: false,
        alwaysVisible: false
    });

    function resize() {
        if($(window).innerHeight() > $('.content-wrapper').height()) {
            $(".content-wrapper").css('height', $(window).innerHeight()-100);
        }
        else if($('.sidebar').height() > $('.content-wrapper').height()){
            $(".content-wrapper").css('height', $('.sidebar').height());
        }
    }

    function checkIfExistUrlParameter(name) {
        return (new RegExp('[\?&]' + name + '=').exec(window.location.href)) ? true : false;
    }
</script>

@yield('js-scripts')
