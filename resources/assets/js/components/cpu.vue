<template>
            <div class="col-md-8 col-md-offset-2">
                <div class="row text-center">
                    <div class="col-md-4">
                        <radial-progress-bar :diameter="250"
                                             :completed-steps="cpu"
                                             :total-steps="100"
                                             :innerStrokeColor="background"
                                             :startColor="color"
                                             :strokeWidth="15"
                                             :stopColor="color"

                        >
                            <div>
                                <h2>CPU</h2>
                                <p>{{cpu}}%</p>
                            </div>

                        </radial-progress-bar>

                    </div>
                    <div class="col-md-4">
                        <radial-progress-bar :diameter="250"
                                             :completed-steps="ram"
                                             :total-steps="100"
                                             :innerStrokeColor="background"
                                             :startColor="color"
                                             :stopColor="color"
                                             :strokeWidth="15"

                        >
                            <div>
                                <h2>RAM</h2>
                                <p>{{ram}}%</p>
                            </div>

                        </radial-progress-bar>

                    </div>
                    <div class="col-md-4">

                        <radial-progress-bar :diameter="250"
                                             :completed-steps="swap"
                                             :total-steps="100"
                                             :innerStrokeColor="background"
                                             :startColor="color"
                                             :stopColor="color"
                                             :strokeWidth="15"

                        >
                            <div>
                                <h2>SWAP</h2>
                                <p>{{swap}}%</p>
                            </div>

                        </radial-progress-bar>
                    </div>
                </div>
            </div>


</template>
<script>

    import RadialProgressBar from 'vue-radial-progress/src/RadialProgressBar.vue'
    export default{
        data: function(){
            return {
                cpu: 0,
                ram:0,
                swap:0,
                background: '#57A3BC',
                color:'#2976bc'
            }
        },
        components: {
            RadialProgressBar,

        },
        mounted () {
            setInterval(() => {
                this.getCpu();
                this.getRam();
                this.getSwap();
            }, 3000)
        },
        created: function(){
          this.getCpu();
          this.getRam();
          this.getSwap();
        },

        methods: {
            getCpu: function(){
                $.getJSON("/c", function(cpu){
                    this.cpu = cpu.toFixed(2);;
                }.bind(this));
            },

            getRam: function(){
                $.getJSON("/ram", function(ram){
                    this.ram = ram.toFixed(2);;
                }.bind(this));
            },
            getSwap: function(){
                $.getJSON("/swap", function(swap){
                    this.swap = swap.toFixed(2);;
                }.bind(this));
            }
        }


    }
</script>
<style>
    radial-progress-bar{
        font-size: 20px;
        color: #00a7d0;
        text-align: center;
        margin: 20px;
    }

</style>
