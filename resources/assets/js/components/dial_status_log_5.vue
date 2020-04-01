<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <h2>Campagna: <span class="firstLetter">{{camp}}</span></h2>
                <hr>
                <div class="col-md-6 col-md-offset-3">
                    <h4>Scegli la campagna:</h4>
                    <select id="search-campaign" v-model="camp">
                        <option  :value="'generale'">Tutte le campagne</option>
                        <option  class="firstLetter" v-for="campaign in campaignsList" :value="campaign">{{campaign}}</option>
                    </select>
                </div>
                <hr>
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary" @click="refresh"><i class="fa fa-refresh" aria-hidden="true" ></i><span> Refresh</span></button>
                </div>
            </div>
        </div>

    <div class="row">



        <div class="col-md-12">


                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Schema</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Busy</th>
                        <th>Failed</th>
                        <th>No Answer</th>
                        <th>Chanunavail</th>
                        <th>Answer</th>
                        <th>Drop</th>
                        <th>Undefined</th>
                        <th>Total</th>
                        <th>Subscriber Absent</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="campaign in campaignsFilter">
                        <td>{{ campaign.schema }}</td>
                        <td>{{ campaign.from }}</td>
                        <td>{{ campaign.to }}</td>
                        <td>{{campaign.busy}}</td>
                        <td>{{campaign.failed}}</td>
                        <td>{{campaign.noanswer}}</td>
                        <td>{{campaign.chanunavail}}</td>
                        <td>{{campaign.answer}}</td>
                        <td>{{campaign.drop}}</td>
                        <td>{{campaign.undefined}}</td>
                        <td>{{campaign.total}}</td>
                        <td>{{campaign.subscriber_absent}}</td>

                    </tr>

                    </tbody>
                </table>
        </div>
    </div>
    </div>
</template>

<script>

    export default{
        data: function(){
            return {
                campaignsList: [],
                campaigns: [],
                camp: 'generale'
            }
        },


        created: function(){
            this.getCampaignsList();
            this.getCampaigns();
        },

        computed: {
            campaignsFilter(){
                if (this.camp == 'generale'){
                    return this.campaigns;
                } else {
                    return this.campaigns.filter(campaign => campaign.schema == this.camp);
                }

            },
        },

        methods: {
            refresh: function(){
                this.getCampaignsList();
                this.getCampaigns();
            },

            getCampaignsList: function(){
                $.getJSON("/dial_status_log_5", function(campaignsList){

                    this.campaignsList = Object.keys(campaignsList);


                }.bind(this));
            },

            getCampaigns: function(){
                $.getJSON("/campaigns", function(campaigns){
                    this.campaigns = campaigns;
                }.bind(this));

            },

            getCamp: function(camp){
                this.camp = camp;
            }
        }

    }
</script>

<style>
    #search-campaign{
        margin-bottom: 15px;
        width:100%;
    }

    .firstLetter:first-letter, td:first-letter{
        text-transform:capitalize;
    }

</style>