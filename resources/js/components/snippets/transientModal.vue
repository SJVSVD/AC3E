<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-10">
                        <div class="card-header pb-0">
                            <h6>{{$title}}</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div style="text-align: center; margin: auto;">
                        <a class="btn btn-light btn-sm" style="width: 120px; height: 120px;">Sistema L</a>
                        <a class="btn btn-info btn-sm" style="width: 120px; height: 120px;">Sistema M</a>
                        <a class="btn btn-warning btn-sm" style="width: 120px; height: 120px;">Sistema N</a>
                        <a class="btn btn-close btn-sm" style="width: 120px; height: 120px;">Sistema Ñ</a>
                        <a class="btn btn-no-btn btn-sm" style="width: 120px; height: 120px;">Sistema O</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import {mixin} from '../../mixins.js'

export default {
    components: {  },
    mixins: [mixin],
    data(){
        return{
        }
    },
    props:{
        title: Object,
    },
    created(){
    },
    methods: {
    }
}
</script>