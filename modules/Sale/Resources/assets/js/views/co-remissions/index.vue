<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><span class="material-symbols-outlined">home</span></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Listado de remisiones</span> </li>
                <!-- <li><span class="text-muted">Facturas - Notas <small>(crédito y débito)</small> - Boletas - Anulaciones</span></li> -->
            </ol>
            <div class="right-wrapper pull-right" >

                <a :href="`/${resource}/create`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
            </div>
            <div class="card-body ">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Documento</th>
                        <th>Comprobantes</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">T.Venta</th>
                        <th class="text-right">T.Descuentos</th>
                        <th class="text-right">T.Impuestos</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Total</th>
                        <th class="text-center"></th>
                        <th class="text-center">Descargas</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }" >
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.number_full }}<br/></td>
                        <td>
                            <span v-for="(doc, index) in row.documents" :key="index">
                                {{ doc.number_full }}<br/>
                            </span>
                        </td>
                        <td class="text-center">{{ row.currency_name }}</td>
                        <td class="text-right">{{ row.sale }}</td>
                        <td class="text-right">{{ row.total_discount }}</td>
                        <td class="text-right">{{ row.total_tax }}</td>
                        <td class="text-right">{{ row.subtotal }}</td>
                        <td class="text-right">{{ row.total }}</td>

                        <td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-success m-1__2"
                                    @click.prevent="clickPayment(row.id)">Pagos</button>
                        </td>

                        <td class="text-center" >
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)">PDF</button>
                        </td>
                        <td class="text-right" >
                            <el-tooltip class="item" effect="dark" content="Generar comprobante" placement="top-start">
                                <button v-if="enableToDocument(row)"
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDialogDocument(row)" >
                                    Comprobante
                                </button>
                            </el-tooltip>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickOptions(row.id)">Opciones</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>
        <remission-payments :showDialog.sync="showDialogPayments"
            :remissionId="recordId"></remission-payments>
        <remission-options :showDialog.sync="showDialogOptions"
            :showDownload="false"
            :recordId="recordId"
            :showClose="true"></remission-options>
        <generate-document :showDialog.sync="showDialogDocument"
            :record="recordToDocument"></generate-document>
    </div>
</template>

<script>
    import moment from 'moment';
    import DataTable from '@components/DataTable.vue'
    import RemissionOptions from './partials/options.vue'
    import RemissionPayments from './partials/payments.vue'
    import GenerateDocument from './partials/generateDocument.vue'

    export default {
        components: {
            DataTable,
            RemissionOptions,
            RemissionPayments,
            GenerateDocument
        },
        data() {
            return {
                showImportDialog: false,
                resource: 'co-remissions',
                showDialogPayments: false,
                recordId: null,
                showDialogOptions: false,
                showDialogDocument: false,
                recordToDocument: {}
            }
        },
        created() {
        },
        methods: {
            clickPayment(recordId) {
                this.recordId = recordId
                this.showDialogPayments = true
            },
            clickDownload(external_id) {
                window.open(`/${this.resource}/download/${external_id}`, '_blank');
            },
            clickOptions(recordId = null) {
                this.recordId = recordId
                this.showDialogOptions = true
            },
            enableToDocument(document) {
                let date_document = moment(document.date_of_issue , "YYYY-MM-DD");
                return date_document.isSame(moment(), 'day');
            },
            clickDialogDocument(document) {
                this.recordToDocument = document
                this.showDialogDocument = true
            },
        }
    }
</script>
