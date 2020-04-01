<template>
        <div>
            <div class="row">
                <div class="col-md-4">
                    <select class="dv-header-select" v-model="query.search_column">
                        <option v-for="column in columns" :value="column">{{column}}</option>
                    </select>
                </div>
                <!--<div class="col-md-1">
                    <select class="dv-header-select" v-model="query.search_operator">
                        <option v-for="(value, key) in operators" :value="key">{{value}}</option>
                    </select>
                </div>-->
                <div class="col-md-6">
                    <input placeholder="Cerca nella tabella" v-model="search"/>


                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" @click="refresh"><i class="fa fa-refresh" aria-hidden="true" ></i><span> Refresh</span></button>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Extension</th>
                            <th>Schema</th>
                            <th>Buyer</th>
                            <th>Created At</th>
                            <th>Queued At</th>
                            <th>Served At</th>
                            <th>Customer Data Id</th>
                            <th>Polled At</th>
                            <th>Username</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in filteredUser">
                            <td>{{ user.available_extension_id }}</td>
                            <td>{{ user.extension }}</td>
                            <td>{{ user.schema }}</td>
                            <td>{{user.buyer}}</td>
                            <td>{{user.created_at}}</td>
                            <td>{{user.queued_at}}</td>
                            <td>{{user.served_at}}</td>
                            <td>{{user.served_with_system_customer_data_id}}</td>
                            <td>{{user.polled_at}}</td>
                            <td>{{user.username}}</td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>


        </div>
</template>
<script>
    import axios from 'axios';
    import Vue from 'vue';

    export default{
        data:function(){
            return{
                chiave: [],
                chiave1: '',
                chiave2: null,
                available: [],
                search:'',
                query: {
                    page: 1,
                    column: 'id',
                    direction: 'desc',
                    per_page: 15,
                    search_column: 'generale',
                    search_operator: 'equal',
                    search_input: ''


                },
                columns: {
                    generale: 'generale',
                    id: 'id',
                    extension: 'extension',
                    schema:'schema',
                    buyer : 'buyer',
                    created_at: 'created_at',
                    queued_at:'queued_at',
                    served_at:'served_at',
                    customer_data_id: 'served_at',
                    polled_at: 'served_with_system_customer_data_id',
                    username:'username'

                },
                /*operators: {
                    equal: '=',
                    not_equal: '<>',
                    less_than: '<',
                    greater_than: '>',
                    less_than_or_equal_to: '<=',
                    greater_than_or_equal_to: '>=',
                    in: 'IN',
                    like: 'LIKE'
                }*/
            }
        },

        created: function(){
            this.getAvailable();
        },
        computed:{
            filteredUser: function () {
                let self=this;

                if(this.search){
                    let array_camp = this.available,
                        searchUser = this.search;
                    searchUser = searchUser.trim().toLowerCase();

                    array_camp = array_camp.filter(function(row){
                            return Object.keys(row).some(function(key){
                                if(self.query.search_column == 'generale'){
                                    return (String(row[key]).toLowerCase().indexOf(searchUser) > -1)

                                } else{
                                    return (String(row[self.query.search_column]).toLowerCase().indexOf(searchUser) > -1)

                                }

                            })
                        }
                    );

                    return array_camp;

                }


                return this.available;
            }
        },

        methods: {
            refresh: function(){
                this.getAvailable();
            },
            getAvailable: function(){
                $.getJSON("/available", function(available){
                    this.available = available;
                }.bind(this));
            },

        },

    }
</script>

<style>
    input{
        width: 100%;
    }
    td:first-letter{
        text-transform:capitalize;
    }
</style>

