


<template>
<!--@start="isDragging=true" @end="isDragging=true"-->

    <div class="container-fluid drag">

        <div class="row">
            <div  class="col-md-6">
                <h3>Campagne</h3>
                <!--<draggable class="list-group" element="ul" v-model="list" :options="dragOptions" :move="onMove" >
                    <transition-group type="transition" :name="'flip-list'">
                        <li class="list-group-item" v-for="element in list" :key="element.name">
                            {{element.name}}
                        </li>
                    </transition-group>
                </draggable>-->
                <draggable class="list-group" element="span" v-model="list" :options="dragOptions" :move="onMove">
                    <transition-group name="no" class="list-group" tag="ul">
                        <li class="list-group-item" v-for="element in list" :key="element.name">
                            {{element.name}}
                        </li>
                    </transition-group>
                </draggable>
            </div>

            <div  class="col-md-6">
                <h3>Campagne da confrontare</h3>

                <draggable class="list-group" element="span" v-model="list2" :options="dragOptions" :move="onMove">
                    <transition-group name="no" class="list-group" tag="ul">
                        <li class="list-group-item" v-for="element in list2" :key="element.name">
                            {{element.name}}
                        </li>
                    </transition-group>
                </draggable>
            </div>
        </div>




        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <input id="search-campaign" placeholder="Cerca campagna" v-model="search"/>
            </div>
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
                <tr v-for="tcamp in filteredUser">
                    <td>{{ tcamp.schema}}</td>
                    <td>{{ tcamp.from }}</td>
                    <td>{{ tcamp.to }}</td>
                    <td>{{ tcamp.busy }}</td>
                    <td>{{ tcamp.failed }}</td>
                    <td>{{ tcamp.noanswer }}</td>
                    <td>{{ tcamp.chanunavail }}</td>
                    <td>{{ tcamp.answer }}</td>
                    <td>{{ tcamp.drop }}</td>
                    <td>{{ tcamp.undefined }}</td>
                    <td>{{ tcamp.total }}</td>
                    <td>{{ tcamp.subscriber_absent }}</td>


                </tr>

                </tbody>
            </table>



            <div class="Chart">
                <bar-chart
                    :chart-data="dati"
                :options="opt"
                :height="150">
                </bar-chart>
            </div>
        </div>
    </div>

</template>

<script>
import draggable from 'vuedraggable';
import BarChart from './BarCampaign';

$(document).ready(function(){
    $("#grafico").hide();
    $("#btn-grafico").click(function(){
        $("#tabella").hide();
        $("#grafico").show();
    });
    $("#btn-tabella").click(function(){
        $("#tabella").show();
        $("#grafico").hide();
    });
});

    export default {
        components: {
            draggable,
            BarChart
        },
        data () {
            return {
                list:[],
                list2:[],
                search:'',
                editable:true,
                isDragging: false,
                delayedDragging:false,
                dataPoints: null,
                dati: null,
                opt: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                            },
                            gridLines: {
                                display: true,
                            },
                        }],
                        xAxes: [ {
                            gridLines: {
                                display: true
                            },
                            categoryPercentage: 0.7,
                            barPercentage: 0.7
                        }]
                    }
                },
            }
        },
        mounted () {
            setInterval(() => {
                this.fillData()
            }, 1000)
        },
        created: function() {
            this.getCampaignsList();
        },
        methods:{

            onMove ({relatedContext, draggedContext}) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;

                return (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
            },

            getCampaignsList: function(){
                let campaign;

                $.getJSON("/dial_status_log_5", function(campaignsList){
                    for (campaign in campaignsList){
                        this.list.push({'name': campaign,
                            'schema': campaignsList[campaign][0].schema,
                            'from': campaignsList[campaign][0].from,
                            'to': campaignsList[campaign][0].to,
                            'busy': campaignsList[campaign][0].busy,
                            'failed': campaignsList[campaign][0].failed,
                            'noanswer': campaignsList[campaign][0].noanswer,
                            'chanunavail': campaignsList[campaign][0].chanunavail,
                            'answer': campaignsList[campaign][0].answer,
                            'drop': campaignsList[campaign][0].drop,
                            'undefined': campaignsList[campaign][0].undefined,
                            'total': campaignsList[campaign][0].total,
                            'subscriber_absent': campaignsList[campaign][0].subscriber_absent,
                            'fixed': false}) ;
                    }
                }.bind(this));
            },


            fillData () {
                this.dati = {

                    labels: ['Busy','Failed', 'NoAnswer', 'Chanunavail', 'Answer', 'Drop', 'Undefined', 'Total', 'Subscriber_Absent'],
                    datasets: [],
                };


                let self1 = this;
                let self = this.dati;
                this.list2.forEach(function(element){

                    self.datasets.push({
                        label: element.schema,
                        backgroundColor: self1.getColor(element.schema),
                        data:[
                            element.busy,
                            element.failed,
                            element.noanswer,
                            element.chanunavail,
                            element.answer,
                            element.drop,
                            element.undefined,
                            element.total,
                            element.subscriber_absent
                        ]
                    })

                });

            },

            getColor(nome){
                switch(nome){
                    case 'tim': return 'rgba(0,69,145,0.5)';
                    case 'tiscali' : return '#0893a7';
                    case 'iren': return '#d83832';
                    case 'wind': return '#f68c3e';
                    default : return '#f87979';

                }
            }



        },



        computed: {
            dragOptions() {
                return {
                    animation: 0,
                    group: 'description',
                    disabled: !this.editable,
                    ghostClass: 'ghost'
                };
            },
            listString() {
                return this.list;
            },
            list2String() {
                return this.list2;
            },
            filteredUser: function () {

                if (this.search) {
                    let array_camp = this.list2,
                        searchUser = this.search;
                    searchUser = searchUser.trim().toLowerCase();

                    array_camp = array_camp.filter(function (row) {
                        return Object.keys(row).some(function (key) {


                                return (String(row[key]).toLowerCase().indexOf(searchUser) > -1)

                            }
                        )


                    });
                    return array_camp;


                }

                return this.list2;

            },

        },

        watch: {
            isDragging (newValue) {
                if (newValue){
                    this.delayedDragging= true;
                    return
                }
                this.$nextTick( () =>{
                    this.delayedDragging =false;
                })
            },
            dataLabel: {
                handler: function (val) {
                    this._chart.update()
                },
                deep: true,

            }
        }


    }
</script>

<style>

    .flip-list-move {
        transition: transform 0.5s;
    }
    .no-move {
        transition: transform 0s;
    }
    .ghost {
        opacity: .5;
        background: #C8EBFB;
    }
    .list-group {
        min-height: 20px;
    }
    .list-group-item {
        cursor: move;
    }
    .list-group-item i{
        cursor: pointer;
    }

    #search-campaign{
        margin: 15px 0 15px;

    }
    .canvas{
        position: relative !important;
    }

</style>

