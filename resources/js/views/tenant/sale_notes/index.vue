<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><span class="material-symbols-outlined">home</span></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Notas de Venta</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a href="#" @click.prevent="clickCreate()" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns" :key="index">
                            <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Nota de Venta</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right">Total</th>

                        <th class="text-center" v-if="columns.total_paid.visible">Pagado</th>
                        <th class="text-center" v-if="columns.total_pending_paid.visible">Por pagar</th>

                        <th class="text-center">Comprobantes</th>
                        <th class="text-center">Estado pago</th>

                        <th class="text-center">Pagos</th>
                        <th class="text-center">Descarga</th>
                        <th class="text-center">
                            Recurrencia
                        </th>
                         <th class="text-center" v-if="columns.type_period.visible" >
                            Tipo Periodo
                        </th>
                        <th class="text-center" v-if="columns.quantity_period.visible" >
                            Cantidad Periodo
                        </th>
                        <th class="text-center" v-if="columns.paid.visible">
                            Estado de Pago
                        </th>
                        <th class="text-center" v-if="columns.license_plate.visible">
                            Placa
                        </th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.full_number }}
                        </td>
                        <td>{{ row.state_type_description }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td>

                        <td class="text-right">{{ row.total }}</td>

                        <td class="text-center" v-if="columns.total_paid.visible">
                            {{row.total_paid}}
                        </td>
                        <td class="text-center" v-if="columns.total_pending_paid.visible">
                            {{row.total_pending_paid}}
                        </td>



                        <td>
                            <template v-for="(document,i) in row.documents">
                                <label :key="i" v-text="document.number_full" class="d-block"></label>
                            </template>
                        </td>
                        <td class="text-center">
                            <span class="badge text-white" :class="{'bg-success': (row.paid), 'bg-warning': (!row.paid)}">{{row.paid ? 'Pagado':'Pendiente'}}</span>
                        </td>

                        <td class="text-center">
                            <!-- <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickPayment(row.id)"  v-if="row.btn_payments">Pagos</button> -->

                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickPayment(row.id)" ><i class="fas fa-money-bill-alt"></i></button>
                        </td>

                        <td class="text-right">
                            <!-- <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)">PDF</button> -->
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)"><i class="fas fa-file-pdf"></i></button>
                        </td>
                        <td class="text-right">
                            <template v-if="row.type_period && row.quantity_period>0">
                                <el-switch :disabled="row.apply_concurrency" v-model="row.enabled_concurrency" active-text="Si" inactive-text="No" @change="changeConcurrency(row)"></el-switch>
                            </template>
                        </td>

                        <td class="text-right" v-if="columns.type_period.visible">
                            {{ row.type_period | period}}
                        </td>
                        <td class="text-right" v-if="columns.quantity_period.visible">
                            {{row.quantity_period}}
                        </td>

                        <td class="text-right" v-if="columns.paid.visible" >
                            {{row.paid ? 'Pagado' : 'Pendiente'}}
                        </td>

                        <td class="text-right" v-if="columns.license_plate.visible" >
                            {{row.license_plate}}
                        </td>

                        <td class="text-right">

                            <!-- <button v-if="row.state_type_id != '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger"  @click.prevent="clickVoided(row.id)">Anular</button> -->
                            <button data-toggle="tooltip" data-placement="top" title="Anular" v-if="row.state_type_id != '11'" type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                             @click.prevent="clickVoided(row.id)"><i class="fas fa-trash"></i></button>

                            <!-- <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickCreate(row.id)" v-if="row.btn_generate && row.state_type_id != '11'">Editar</button> -->

                            <button data-toggle="tooltip" data-placement="top" title="Editar" type="button" class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickCreate(row.id)" v-if="row.btn_generate && row.state_type_id != '11'"><i class="fas fa-file-signature"></i></button>

                            <button data-toggle="tooltip"
                                    data-placement="top"
                                    title="Generar comprobante"
                                    type="button"
                                    class="btn waves-effect waves-light btn-xs btn-success"
                                    @click.prevent="clickGenerate(row.id)"
                                    v-if="!row.changed && row.state_type_id != '11' && soapCompany != '03'">
                                <i class="fas fa-file-excel"></i>
                            </button>

                            <!-- <button  v-if="row.state_type_id != '11'"  type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptions(row.id)">Opciones</button> -->
                            <!-- <template v-for="(document,i) in row.documents" >
                                <a :href="`/dispatches/create/${document.id}`" class="btn waves-effect waves-light btn-xs btn-warning m-1__2"
                                    v-if="row.changed" :key="i"><i class="fas fa-file-alt"></i></a>
                            </template> -->

                            <!-- <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickGenerate(row.id)" v-if="!row.changed && row.state_type_id != '11' ">Generar comprobante</button> -->

                            <button  data-toggle="tooltip" data-placement="top" title="Imprimir" v-if="row.state_type_id != '11'"  type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickOptions(row.id)"><i class="fas fa-print"></i></button>
                        </td>


                    </tr>
                </data-table>
            </div>
        </div>

        <sale-note-payments :showDialog.sync="showDialogPayments"
                            :documentId="recordId"></sale-note-payments>

        <sale-notes-options :showDialog.sync="showDialogOptions"
                          :recordId="saleNotesNewId"
                          :showClose="true"></sale-notes-options>

        <sale-note-generate :showDialog.sync="showDialogGenerate"
                           :recordId="recordId"
                           :showGenerate="true"
                           :showClose="false"></sale-note-generate>

    </div>
</template>

<script>

    import DataTable from '../../../components/DataTableSaleNote.vue'
    import SaleNotePayments from './partials/payments.vue'
    import SaleNotesOptions from './partials/options.vue'
    import SaleNoteGenerate from './partials/option_documents'
    import {deletable} from '../../../mixins/deletable'

    export default {
        props: ['soapCompany'],
        mixins: [deletable],
        components: {DataTable, SaleNotePayments, SaleNotesOptions, SaleNoteGenerate},
        data() {
            return {
                resource: 'sale-notes',
                showDialogPayments: false,
                showDialogOptions: false,
                showDialogGenerate: false,
                saleNotesNewId: null,
                recordId: null,
                showDialogOptions: false,
                columns: {
                    paid: {
                        title: 'Estado de Pago',
                        visible: false
                    },
                    type_period: {
                        title: 'Tipo Periodo',
                        visible: false
                    },
                    quantity_period: {
                        title: 'Cantidad Periodo',
                        visible: false
                    },
                    license_plate:{
                        title: 'Placa',
                        visible: false
                    },
                    total_paid:{
                        title: 'Pagado',
                        visible: false
                    },
                    total_pending_paid:{
                        title: 'Por pagar',
                        visible: false
                    }

                }
            }
        },
        created() {
        },
        filters:{
            period(name)
            {
                let res = ''
                switch(name)
                {
                    case 'month':
                        res = 'Mensual'
                        break
                    case 'year':
                        res = 'Anual'
                        break
                    default:

                        break;
                }

                return res
            }
        },
        methods: {
            clickDownload(external_id) {
                window.open(`/sale-notes/downloadExternal/${external_id}`, '_blank');
            },
            clickOptions(recordId) {
                this.saleNotesNewId = recordId
                this.showDialogOptions = true
            },
            clickGenerate(recordId) {
                this.recordId = recordId
                this.showDialogGenerate = true
            },
            clickPayment(recordId) {
                this.recordId = recordId;
                this.showDialogPayments = true;
            },
            clickCreate(id = '') {
                location.href = `/${this.resource}/create/${id}`
            },

            changeConcurrency(row) {

                // console.log(row)
                this.$http.post(`/${this.resource}/enabled-concurrency`, row).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit('reloadData')
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                });
            },
            clickVoided(id) {
                 this.anular(`/${this.resource}/anulate/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },

        }
    }
</script>
