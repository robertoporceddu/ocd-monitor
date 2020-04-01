<div id="cpu" onload="myFunction()" class="row">
    <div class="col-md-12">
        <div id="cpu" style="margin:0; ">
            <div id="p" class="panel panel-primary" style="margin:0" >
                <div class="panel-heading"><h3>Carico CPU</h3></div>

                <div  class="panel-body" id="cpu">
                    <div class="row">
                        <div class="col-md-4">
                            <h2>CPU</h2>
                            <canvas class="circle-cpu"></canvas>
                        </div>
                        <div class="col-md-4">
                            <h2>RAM</h2>
                            <canvas class="circle-ram"></canvas>
                        </div>
                        <div class="col-md-4">
                            <h2>SWAP</h2>
                            <canvas class="circle-swap"></canvas>
                        </div>
                    </div>
                    <hr>
                        <button type="submit" onclick="myFunction()" class="btn btn-primary">Refresh</button>



                </div>

            </div>


        </div>



    </div>
</div>

@include('layouts.partials.circle')