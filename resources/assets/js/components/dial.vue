<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h2>Campagna: <span>{{camp}}</span></h2>
                <div class="col-md-6 col-md-offset-3">
                    <select id="search-campaign" v-model="camp">
                        <option  :value="'generale'">Tutte le campagne</option>
                        <option  :value="campaign[0].schema" v-for="campaign in campaignsGroup">{{campaign[0].schema}}</option>

                    </select>
                    <input id="searchOperatore" placeholder="Cerca operatore" v-model="searchOperatore"/>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" @click="refresh"><i class="fa fa-refresh" aria-hidden="true" ></i><span> Refresh</span></button>
                    </div>
                </div>



            </div>
        </div>
        <hr>
        <div class="row">

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-4">
                        <div  class="panel panel-primary" >
                            <div class="panel-heading">
                                <h3>Operatori in Coda</h3>
                            </div>
                            <div  class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Username</th>
                                        <th>Campagna</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="campaign in filteredUser" v-if="campaign.action == 'on_queue'">
                                        <td>{{ campaign.username }}</td>
                                        <td>{{ campaign.schema}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div  class="panel panel-primary" >
                            <div class="panel-heading">
                                <h3>Operatori in Chiamata</h3>
                            </div>
                            <div  class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Username</th>
                                        <th>Campagna</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="campaign in filteredUser" v-if="campaign.action == 'on_work'">
                                        <td>{{ campaign.username }}</td>
                                        <td>{{ campaign.schema}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div  class="panel panel-primary" >
                            <div class="panel-heading">
                                <h3>Operatori in Pausa</h3>
                            </div>
                            <div  class="panel-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Username</th>
                                        <th>Campagna</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="campaign in filteredUser" v-if="campaign.action == 'on_pause' ">
                                        <td>{{ campaign.username }}</td>
                                        <td>{{ campaign.schema}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</template>

<script>
    export default{
        data: function(){
            return {
                campaigns:[],
                campaignsGroup:[],
                camp: 'generale',
                searchOperatore: ''
            }
        },


        created(){
            this.getCampaignGroup();
           this.getCampaign();

        },

        filter: {
            firstToUppercase(value) {
                return value.charAt(0).toUpperCase() + string.slice(1);
            }
        },

        computed: {
           filtroCampagna(){
               if(this.camp == 'generale'){
                   return this.campaigns;
               } else{
                   return this.campaigns.filter(c => c.schema == this.camp);
               }
           },
            filteredUser: function () {
               let self = this;

               if (this.searchOperatore){
                   return self.filtroCampagna.filter(row => row.username.toLowerCase().indexOf(this.searchOperatore.trim().toLowerCase()) > -1);
               }

               return self.filtroCampagna;

            },

        },

        methods: {
            refresh: function(){
                this.getCampaignGroup();
                this.getCampaign();
            },

            getCampaignGroup: function(){
                $.getJSON("/extensions_group", function(campaigns){
                    this.campaignsGroup = campaigns;
                }.bind(this));
            },
            getCampaign: function(){
                $.getJSON("/extensions", function(campaigns){
                    this.campaigns = Object.keys(campaigns).map(function (key) { return campaigns[key]; });

                }.bind(this));
            },



        },

    }
</script>


<style>

    td:first-letter{
        text-transform:capitalize;
    }


    #searchOperatore{
        margin: 15px 0 15px;
    }
</style>