<template>
    <div>
        <div class="row ">

            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div class="row" v-if="applyFilter">
                    <div class="col-lg-4 col-md-4 col-sm-12 pb-2">
                        <div class="d-flex">
                            <div style="width:100px">
                                Filtrar por:
                            </div>
                            <el-select v-model="search.column"  placeholder="Select" @change="changeClearInput">
                                <el-option v-for="(label, key) in columns" :key="key" :value="key" :label="label"></el-option>
                            </el-select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
                        <template v-if="search.column=='date_of_issue' || search.column=='date_of_due' || search.column=='date_of_payment'">
                            <el-date-picker
                                v-model="search.value"
                                type="date"
                                style="width: 100%;"
                                placeholder="Buscar"
                                value-format="yyyy-MM-dd"
                                @change="getRecords">
                            </el-date-picker>
                        </template>
                        <template v-else>
                            <el-input placeholder="Buscar"
                                v-model="search.value"
                                style="width: 100%;"
                                prefix-icon="el-icon-search"
                                @input="getRecords">
                            </el-input>
                        </template>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-12 pb-2">

                        <el-popover
                            placement="right"
                            width="450"
                            height="300"
                            trigger="click">
                            <el-date-picker
                                style="width:100%"
                                value-format="yyyy-MM-dd"
                                v-model="rangePicker"
                                type="daterange"
                                range-separator="-"
                                start-placeholder="Desde"
                                end-placeholder="Hasta">
                            </el-date-picker>
                            <el-button style="float:right;margin-top:4px;" size="mini" type="success"  @click="clickDownload()" >Aceptar</el-button>
                            <el-button  slot="reference" class="submit" type="success">
                                <i class="fa fa-file-excel"></i> Descargar Informe
                            </el-button>
                        </el-popover>
                    </div>
                </div>

            </div>


            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <slot name="heading"></slot>
                        </thead>
                        <tbody>
                        <slot v-for="(row, index) in records" :row="row" :index="customIndex(index)"></slot>
                        </tbody>
                    </table>

                    <!-- <div class="row mb-5">
                        <div class="col-md-4 text-center">Total notas de venta en soles S/. {{totals.total_pen}}</div>
                        <div class="col-md-4 text-center">Total pagado en soles S/. {{totals.total_paid_pen}}</div>
                        <div class="col-md-4 text-center">Total por cobrar en soles S/. {{totals.total_pending_paid_pen}}</div>
                    </div> -->

                    <div>
                        <el-pagination
                                @current-change="getRecords"
                                layout="total, prev, pager, next"
                                :total="pagination.total"
                                :current-page.sync="pagination.current_page"
                                :page-size="pagination.per_page">
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>

    import moment from 'moment'
    import queryString from 'query-string'

    export default {
        props: {
            resource: String,
            applyFilter:{
                type: Boolean,
                default: true,
                required: false
            }
        },
        data () {
            return {
                search: {
                    column: null,
                    value: null,
                    series: null
                },
                totals: {
                    total_pen: 0,
                    total_paid_pen: 0,
                    total_pending_paid_pen: 0
                },
                columns: [],
                records: [],
                pagination: {},
                series: [],
                rangePicker: ''
            }
        },
        computed: {
        },
        created() {
            this.$eventHub.$on('reloadData', () => {
                this.getRecords()
                this.getTotals()
            })
        },
        async mounted () {
            let column_resource = _.split(this.resource, '/')
           // console.log(column_resource)
            await this.$http.get(`/${_.head(column_resource)}/columns`).then((response) => {
                this.columns = response.data
                this.search.column = _.head(Object.keys(this.columns))
            });

            /*await this.$http.get(`/${_.head(column_resource)}/columns2`).then((response) => {
                this.series = response.data.series
            });*/


            await this.getRecords()
            await this.getTotals()
        },
        methods: {
            getTotals(){

                // this.$http.get(`/${this.resource}/totals`)
                //     .then((response) => {
                //         // console.log(response)
                //         this.totals = response.data
                //     });

            },
            customIndex(index) {
                return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
            },
            getRecords() {
                console.log(`/${this.resource}/records`)
                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {
                    this.records = response.data.data
                    this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)
                });
            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    limit: this.limit,
                    ...this.search
                })
            },
            changeClearInput(){
                this.search.value = ''
                this.getRecords()
            },
            clickDownload(type) {
                if(!this.rangePicker)
                {
                    return
                }

                const params = queryString.stringify({
                    date_start: this.rangePicker[0],
                    date_end: this.rangePicker[1],

                })
                window.open(`/reports/report-taxes/pdf/?${params}`, '_blank');
            },
        }
    }
</script>
