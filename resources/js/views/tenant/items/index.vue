<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><span class="material-symbols-outlined">home</span></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Productos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <template v-if="typeUser === 'admin'">
                    <!-- <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImportListPrice()"><i class="fa fa-upload"></i> Importar L. Precios</button> -->
                    <button type="button" class="btn btn-danger btn-sm  mt-2 mr-2" @click.prevent="clickDeleteAll()"><i class="fa fa-trash"></i> Eliminar</button>
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickExport()"><i class="fa fa-upload"></i> Exportar</button>
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-download"></i> Importar</button>
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
                </template>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de productos</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading" width="100%">
                        <th>#</th>
                        <th>Cód. Interno</th>
                        <th>Unidad</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <!-- <th>Cód. SUNAT</th> -->
                        <th  class="text-left">Stock</th>
                        <th  class="text-right">P.Unitario (Venta)</th>
                        <th v-if="typeUser != 'seller'" class="text-right">P.Unitario (Compra)</th>
                        <!-- <th class="text-center">Tiene Igv</th> -->
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }" :class="{ disable_color : !row.active}">
                        <td>{{ index }}</td>
                        <td>{{ row.internal_id }}</td>
                        <td>{{ row.unit_type_id }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.description }}</td>
                        <!-- <td>{{ row.item_code }}</td> -->
                        <td>
                            <div v-if="config.product_only_location == true">
                                {{ row.stock }}
                            </div>
                            <div v-else>
                                <template v-if="typeUser=='seller' && row.unit_type_id !='ZZ'">{{ row.stock }}</template>
                                <template v-else-if="typeUser!='seller' && row.unit_type_id !='ZZ'">
                                    <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickWarehouseDetail(row.warehouses)"><i class="fa fa-search"></i></button>
                                </template>
                            </div>
                            <!-- <template v-for="item in row.warehouses">
                                <template>{{item.stock}} - {{item.warehouse_description}}</template><br>
                            </template> -->

                            <!-- <br/>Mín:{{ row.stock_min }} -->

                        </td>
                        <td class="text-right">{{ getFormatDecimal(row.sale_unit_price) }}</td>
                        <td v-if="typeUser != 'seller'" class="text-right">{{ getFormatDecimal(row.purchase_unit_price) }}</td>
                        <!-- <td class="text-center">{{ row.has_igv_description }}</td> -->
                        <td class="text-right">
                            <template v-if="typeUser === 'admin'">
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-warning" @click.prevent="duplicate(row.id)">Duplicar</button>

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDisable(row.id)" v-if="row.active">Inhabilitar</button>
                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickEnable(row.id)" v-else>Habilitar</button>

                                <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickBarcode(row)">Cod. Barras</button>

                            </template>
                        </td>
                    </tr>
                </data-table>
            </div>

            <items-form :showDialog.sync="showDialog"
                        :recordId="recordId"></items-form>

            <items-import :showDialog.sync="showImportDialog"></items-import>

            <warehouses-detail
                :showDialog.sync="showWarehousesDetail"
                :warehouses="warehousesDetail">
            </warehouses-detail>

            <items-import-list-price :showDialog.sync="showImportListPriceDialog"></items-import-list-price>

        </div>
    </div>
</template>
<script>

    import ItemsForm from './form.vue'
    import WarehousesDetail from './partials/warehouses.vue'
    import ItemsImport from './import.vue'
    import ItemsImportListPrice from './partials/import_list_price.vue'
    import DataTable from '../../../components/DataTable.vue'
    import {deletable} from '../../../mixins/deletable'
    import {functions} from '@mixins/functions'

    export default {
        props:['typeUser'],
        mixins: [deletable, functions],
        components: {ItemsForm, ItemsImport, DataTable, WarehousesDetail, ItemsImportListPrice},
        data() {
            return {
                showDialog: false,
                showImportDialog: false,
                showImportListPriceDialog: false,
                showWarehousesDetail: false,
                resource: 'items',
                recordId: null,
                warehousesDetail:[],
                config: {}
            }
        },
        created() {
            this.$http.get(`/configurations/record`) .then(response => {
                this.config = response.data.data
            })
        },
        methods: {
            duplicate(id)
            {
                this.$http.post(`${this.resource}/duplicate`, {id})
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('Se guardaron los cambios correctamente.')
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })
                this.$eventHub.$emit('reloadData')
            },
            clickWarehouseDetail(warehouses){
                this.warehousesDetail = warehouses
                this.showWarehousesDetail = true
            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickExport() {
                window.open(`/${this.resource}/co-export`, '_blank');
            },
            clickImport() {
                this.showImportDialog = true
            },
            clickImportListPrice() {
                this.showImportListPriceDialog = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickDisable(id)
            {
                this.disable(`/${this.resource}/disable/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickEnable(id){
                this.enable(`/${this.resource}/enable/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            },
            clickBarcode(row) {

                if(!row.internal_id){
                    return this.$message.error('Para generar el código de barras debe registrar el código interno.')
                }

                window.open(`/${this.resource}/barcode/${row.id}`)
            },
            clickDeleteAll(){

                this.destroyAll(`/${this.resource}/delete/all`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )

            },
        }
    }
</script>
