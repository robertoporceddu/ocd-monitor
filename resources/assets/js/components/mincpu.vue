<template>
    <strong>
        CPU: {{cpu}}% - RAM: {{ram}}% - SWAP: {{swap}}%
    </strong>


</template>
<script>

    export default{
        data: function(){
            return {
                cpu: 0,
                ram:0,
                swap:0,
            }
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
                    this.cpu = cpu.toFixed(2);
                }.bind(this));
            },

            getRam: function(){
                $.getJSON("/ram", function(ram){
                    this.ram = ram.toFixed(2);
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
    strong{
        text-align: center;
    }
</style>
