<template>
        <div class="row" id="div-log">

            <div class="col-md-10 col-md-offset-1">
                <div class=" panel panel-primary">
                    <div class="panel-heading">Log</div>
                    <div id="log-body" class="panel-body">
                        <p v-for="l in log">{{l.content}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button class="btn btn-primary" @click="getLog"><i class="fa fa-refresh" aria-hidden="true" ></i><span> Refresh Log</span></button>
            </div>


        </div>


</template>


<script>
 export default{
        data: function(){
            return {
                log: [],
                reverseLog: []
            }
        },
     created(){
         this.getLog();
     },

    methods: {

        getLog: function(){
            $.getJSON("/reload-log", function(log){
                this.log = log.reverse();
            }.bind(this));

        },

    }

    }
</script>
<style>
    #div-log{
        margin-top: 30px;
    }
    #log-body{
        overflow-y: scroll;
        height: 200px;
    }
</style>


