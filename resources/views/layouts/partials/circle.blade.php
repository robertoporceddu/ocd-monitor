<script>
    $(document).ready(function() {
        var cpu = $('.circle-cpu').ClassyLoader({
            width: 200, // width of the loader in pixels
            height: 200, // height of the loader in pixels
            animate: true, // whether to animate the loader or not
            displayOnLoad: true,
            percentage: "{{$cpu}}", // percent of the value, between 0 and 100
            speed:50, // miliseconds between animation cycles, lower value is faster
            roundedLine: false, // whether the line is rounded, in pixels
            showRemaining: true, // how the remaining percentage (100% - percentage)
            fontFamily: 'Helvetica', // name of the font for the percentage
            fontSize: '45px', // size of the percentage font, in pixels
            showText: true, // whether to display the percentage text
            diameter: 80, // diameter of the circle, in pixels
            fontColor: '#57A3BC', // color of the font in the center of the loader, any CSS color would work, hex, rgb, rgba, hsl, hsla
            lineColor: '#2976bc', // line color of the main circle
            remainingLineColor: '#57A3BC', // line color of the remaining percentage (if showRemaining is true)
            lineWidth: 10 // the width of the circle line in pixels
        });



        var ram = $('.circle-ram').ClassyLoader({
            width: 200, // width of the loader in pixels
            height: 200, // height of the loader in pixels
            animate: true, // whether to animate the loader or not
            displayOnLoad: true,
            percentage: "{{$ram}}", // percent of the value, between 0 and 100
            speed: 50, // miliseconds between animation cycles, lower value is faster
            roundedLine: false, // whether the line is rounded, in pixels
            showRemaining: true, // how the remaining percentage (100% - percentage)
            fontFamily: 'Helvetica', // name of the font for the percentage
            fontSize: '45px', // size of the percentage font, in pixels
            showText: true, // whether to display the percentage text
            diameter: 80, // diameter of the circle, in pixels
            fontColor: '#57A3BC', // color of the font in the center of the loader, any CSS color would work, hex, rgb, rgba, hsl, hsla
            lineColor: '#2976bc', // line color of the main circle
            remainingLineColor: '#57A3BC', // line color of the remaining percentage (if showRemaining is true)
            lineWidth: 10 // the width of the circle line in pixels
        });



        $('.circle-swap').ClassyLoader({
            width: 200, // width of the loader in pixels
            height: 200, // height of the loader in pixels
            animate: true, // whether to animate the loader or not
            displayOnLoad: true,
            percentage: "{{$swap}}", // percent of the value, between 0 and 100
            speed: 50, // miliseconds between animation cycles, lower value is faster
            roundedLine: false, // whether the line is rounded, in pixels
            showRemaining: true, // how the remaining percentage (100% - percentage)
            fontFamily: 'Helvetica', // name of the font for the percentage
            fontSize: '45px', // size of the percentage font, in pixels
            showText: true, // whether to display the percentage text
            diameter: 80, // diameter of the circle, in pixels
            fontColor: '#57A3BC', // color of the font in the center of the loader, any CSS color would work, hex, rgb, rgba, hsl, hsla
            lineColor: '#2976bc', // line color of the main circle
            remainingLineColor: '#57A3BC', // line color of the remaining percentage (if showRemaining is true)
            lineWidth: 10 // the width of the circle line in pixels
        });

    });


</script>
