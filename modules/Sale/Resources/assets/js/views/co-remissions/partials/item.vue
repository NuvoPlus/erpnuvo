<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" top="7vh" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-7 col-lg-7 col-xl-7 col-sm-7">
                        <div class="form-group" id="custom-select" :class="{'has-danger': errors.item_id}">
                            <label class="control-label">
                                Producto/Servicio
                                <a v-if="typeUser != 'seller'" href="#" @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                            </label>

                            <template v-if="!search_item_by_barcode" id="select-append">
                                <el-input id="custom-input">
                                    <el-select :disabled="recordItem != null"
                                            v-model="form.item_id"
                                            @change="changeItem"
                                            filterable
                                            remote
                                            placeholder="Buscar"
                                            popper-class="el-select-items"
                                            @visible-change="focusTotalItem"
                                            slot="prepend"
                                            id="select-width"
                                            :remote-method="searchRemoteItems"
                                            :loading="loading_search">

                                        <el-tooltip v-for="option in items"  :key="option.id" placement="top">

                                            <div slot="content">
                                                Marca: {{option.brand}} <br>
                                                Categoria: {{option.category}} <br>
                                                Stock: {{option.stock}} <br>
                                                Precio: {{option.currency_type_symbol}} {{ getFormatDecimal(option.sale_unit_price) }} <br>
                                            </div>

                                            <el-option  :value="option.id" :label="option.full_description"></el-option>

                                        </el-tooltip>

                                    </el-select>
                                    <el-tooltip slot="append" class="item" effect="dark" content="Ver Stock del Producto" placement="bottom" :disabled="recordItem != null">
                                        <el-button :disabled="isEditItemNote"  @click.prevent="clickWarehouseDetail()"><i class="fa fa-search"></i></el-button>
                                    </el-tooltip>
                                </el-input>
                            </template>
                            <template v-else>
                                <el-input id="custom-input">
                                    <el-select :disabled="recordItem != null" v-model="form.item_id"
                                            @change="changeItem"
                                            filterable
                                            placeholder="Buscar"
                                            popper-class="el-select-items"
                                            dusk="item_id"
                                            @visible-change="focusTotalItem"
                                            slot="prepend"
                                            id="select-width"
                                            remote
                                            :remote-method="searchRemoteItems"
                                            :loading="loading_search">

                                          <el-tooltip v-for="option in items"  :key="option.id" placement="top">
                                            <div slot="content">
                                                Marca: {{option.brand}} <br>
                                                Categoria: {{option.category}} <br>
                                                Stock: {{option.stock}} <br>
                                                Precio: {{option.currency_type_symbol}} {{ getFormatDecimal(option.sale_unit_price)}} <br>
                                            </div>
                                            <el-option  :value="option.id" :label="option.full_description"></el-option>
                                        </el-tooltip>
                                    </el-select>
                                    <el-tooltip slot="append" class="item" effect="dark" content="Ver Stock del Producto" placement="bottom" :disabled="recordItem != null">
                                        <el-button :disabled="isEditItemNote"  @click.prevent="clickWarehouseDetail()"><i class="fa fa-search"></i></el-button>
                                    </el-tooltip>
                                </el-input>
                            </template>

                            <template v-if="!is_client">
                                <el-checkbox  v-model="search_item_by_barcode" :disabled="recordItem != null" >Buscar por código de barras</el-checkbox><br>
                            </template>
                            <small class="form-control-feedback" v-if="errors.item_id" v-text="errors.item_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group" :class="{'has-danger': errors.tax_id}">
                            <label class="control-label">Impuesto</label>
                            <el-select v-model="form.tax_id"  filterable>
                                <el-option v-for="option in itemTaxes" :key="option.id" :value="option.id" :label="option.name"></el-option>
                            </el-select>
                            <!-- <el-checkbox :disabled="recordItem != null" v-model="change_tax_id">Editar</el-checkbox> -->
                            <small class="form-control-feedback" v-if="errors.tax_id" v-text="errors.tax_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group" :class="{'has-danger': errors.quantity}">
                            <label class="control-label">Cantidad</label>
                            <el-input-number v-model="form.quantity" :min="0.01" :disabled="form.item.calculate_quantity"></el-input-number>
                            <small class="form-control-feedback" v-if="errors.quantity" v-text="errors.quantity[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group" :class="{'has-danger': errors.unit_price}">
                        <label class="control-label">Precio Unitario</label>
                            <el-input v-model="form.price" @input="calculateQuantity" :readonly="typeUser === ''">
                                <template slot="prepend" v-if="currencyTypeSymbolActive">{{ currencyTypeSymbolActive }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.unit_price" v-text="errors.unit_price[0]"></small>
                        </div>
                    </div>
                    <div style="padding-top: 1%;" class="col-md-2 col-sm-2" v-if="form.item_id && form.item.lots_enabled && form.lots_group.length > 0">
                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickLotGroup">[&#10004; Seleccionar lote]</a>
                    </div>

                    <div style="padding-top: 1%;" class="col-md-3 col-sm-3" v-if="form.item_id && form.item.series_enabled">
                        <!-- <el-button type="primary" native-type="submit" icon="el-icon-check">Elegir serie</el-button> -->
                        <a href="#"  class="text-center font-weight-bold text-info" @click.prevent="clickSelectLots">[&#10004; Seleccionar series]</a>
                    </div>


                    <div class="col-md-3 col-sm-6" v-show="form.item.calculate_quantity">
                        <div class="form-group"  :class="{'has-danger': errors.total_item}">
                            <label class="control-label">Total venta producto</label>
                            <el-input v-model="total_item" @input="calculateQuantity" :min="0.01" ref="total_item">
                                <template slot="prepend" v-if="currencyTypeSymbolActive">{{ currencyTypeSymbolActive }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.total_item" v-text="errors.total_item[0]"></small>
                        </div>
                    </div>


                    <div class="col-md-3 col-sm-6">
                        <div class="form-group"  :class="{'has-danger': errors.discount}">
                            <label class="control-label">Descuento</label>
                            <el-input v-model="form.discount" :min="0" >
                                <template slot="prepend" v-if="currencyTypeSymbolActive">{{ currencyTypeSymbolActive }}</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.discount" v-text="errors.discount[0]"></small>
                        </div>
                    </div>

                    <template v-if="!is_client">

                        <div class="col-md-12"  v-if="form.item_unit_types.length > 0">
                            <div style="margin:3px" class="table-responsive">
                                <h5 class="separator-title">
                                    Lista de Precios
                                    <el-tooltip class="item" effect="dark" content="Aplica para realizar venta en presentacion de diferentes precios y/o cantidades" placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </h5>
                                <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Unidad</th>
                                    <th class="text-center">Descripción</th>
                                    <th class="text-center">Factor</th>
                                    <th class="text-center">Precio 1</th>
                                    <th class="text-center">Precio 2</th>
                                    <th class="text-center">Precio 3</th>
                                    <th class="text-center">Precio Default</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.item_unit_types" :key="index">

                                        <td class="text-center">{{row.unit_type.name}}</td>
                                        <td class="text-center">{{row.description}}</td>
                                        <td class="text-center">{{row.quantity_unit}}</td>
                                        <td class="text-center">{{row.price1}}</td>
                                        <td class="text-center">{{row.price2}}</td>
                                        <td class="text-center">{{row.price3}}</td>
                                        <td class="text-center">Precio {{row.price_default}}</td>
                                        <td class="series-table-actions text-right">
                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="selectedPrice(row)">
                                                <i class="el-icon-check"></i>
                                            </button>
                                        </td>
                                </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>

                    </template>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button class="add" type="primary" native-type="submit" v-if="form.item_id">{{titleAction}}</el-button>
            </div>
        </form>

        <item-form :showDialog.sync="showDialogNewItem"
                   :external="true"></item-form>


        <!-- <warehouses-detail
                :showDialog.sync="showWarehousesDetail"
                :isUpdateWarehouseId="isUpdateWarehouseId"
                :warehouses="warehousesDetail">
            </warehouses-detail>

        <lots-group
            :quantity="form.quantity"
            :showDialog.sync="showDialogLots"
            :lots_group="form.lots_group"
            @addRowLotGroup="addRowLotGroup">
        </lots-group>

        <select-lots-form
            :showDialog.sync="showDialogSelectLots"
            :lots="lots"
            @addRowSelectLot="addRowSelectLot">
        </select-lots-form> -->


    </el-dialog>
</template>
<style>
.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 5% !important;
}
</style>

<script>

    import ItemForm from '@views/items/form.vue'
    // import LotsGroup from './inventory/lots_group.vue'
    // import WarehousesDetail from './inventory/select_warehouses.vue'
    // import SelectLotsForm from './inventory/lots.vue'


    export default {
        props: ['recordItem','showDialog', 'currencyTypeIdActive', 'currencyTypeSymbolActive', 'typeUser', 'isEditItemNote', 'configuration'],
        components: {ItemForm},
        data() {
            return {
                loading_search:false,
                titleAction: '',
                is_client:false,
                titleDialog: '',
                resource: 'co-remissions',
                showDialogNewItem: false,
                has_list_prices: false,
                errors: {},
                form: {},
                all_items: [],
                items: [],
                activePanel: 0,
                total_item: 0,
                item_unit_types: [],
                showWarehousesDetail: false,
                warehousesDetail:[],
                showListStock:false,
                search_item_by_barcode:false,
                isUpdateWarehouseId:null,
                showDialogLots: false,
                showDialogSelectLots: false,
                lots:[],
                all_taxes:[],
                taxes:[],
                items_aiu: []

            }
        },
        computed: {
            itemTaxes() {
                return this.taxes.filter(tax => !tax.is_retention);
            },
            retentiontaxes() {
                return this.taxes.filter(tax => tax.is_retention);
            },
            retentionSelected() {
            if (this.retention.retention_id == null) return { rate: 0 };

                return this.taxes.find(row => row.id == this.retention.retention_id);
            },
            showTotalAndSave() {
                return (
                    this.document.hasOwnProperty("items") && this.document.items.length > 0
                );
            },
        },
        created() {

            this.initForm()

            this.$http.get(`/${this.resource}/item/tables`).then(response => {
                this.taxes = response.data.taxes;
                this.all_items = response.data.items 
                this.filterItems()

            })

            this.$eventHub.$on('reloadDataItems', (item_id) => {
                this.reloadDataItems(item_id)
            })

        },
        methods: {
            async searchRemoteItems(input) {

                if (input.length > 2) {

                    this.loading_search = true
                    let parameters = `input=${input}`

                    await this.$http.get(`/main-items/search?${parameters}`)
                            .then(response => {
                                // console.log(response)
                                this.items = response.data.items
                                this.loading_search = false

                                this.enabledSearchItemsBarcode()

                                if(this.items.length == 0){
                                    this.filterItems()
                                }
                            })
                } else {
                    await this.filterItems()
                }

            },
            filterItems() {
                this.items = this.all_items
            },
            enabledSearchItemsBarcode(){

                if(this.search_item_by_barcode){

                    if (this.items.length == 1){

                        this.form.item_id = this.items[0].id
                        this.changeItem()
                    }
                }
            },
            initForm() {
                this.errors = {};

                this.form = {
                    id: null,
                    item_id: null,
                    item: {},
                    code: null,
                    discount: 0,
                    name: null,
                    price: null,
                    quantity: null,
                    subtotal: null,
                    tax: {},
                    tax_id: null,
                    total: 0,
                    total_tax: 0,
                    type_unit: {},
                    unit_type_id: null,
                    item_unit_types: [],
                    IdLoteSelected: null,
                };

                this.activePanel = 0;
                this.total_item = 0;
                this.item_unit_type = {};
                this.has_list_prices = false;
            },
            async create() {

                this.titleDialog = (this.recordItem) ? ' Editar Producto o Servicio' : ' Agregar Producto o Servicio';
                this.titleAction = (this.recordItem) ? ' Editar' : ' Agregar';

                if (this.recordItem) {

                    // console.log(this.recordItem)

                    this.form.item_id = await this.recordItem.item_id
                    await this.changeItem()

                    this.form.tax_id = this.recordItem.tax_id
                    this.form.quantity = this.recordItem.quantity
                    this.form.price = this.recordItem.price
                    this.form.discount = this.recordItem.discount

                    // this.form.warehouse_id = this.recordItem.warehouse_id
                    // this.isUpdateWarehouseId = this.recordItem.warehouse_id

                    if(this.isEditItemNote){
                        this.form.item.currency_type_id = this.currencyTypeIdActive
                        this.form.item.currency_type_symbol = this.currencyTypeSymbolActive
                    }

                    this.calculateQuantity()

                }else{
                    this.isUpdateWarehouseId = null
                }

            },
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            },
            async changeItem() {


                this.form.item = _.find(this.items, {'id': this.form.item_id});
                this.form.item_unit_types = _.find(this.items, {'id': this.form.item_id}).item_unit_types
                this.form.id = this.form.item_id
                this.form.unit_type_id = this.form.item.unit_type_id

                this.lots = this.form.item.lots

                this.form.tax_id = (this.taxes.length > 0) ? this.form.item.tax.id: null

                this.form.price = this.form.item.sale_unit_price;

                // this.form.has_igv = this.form.item.has_igv;
                // this.form.affectation_igv_type_id = this.form.item.sale_affectation_igv_type_id;

                this.form.quantity = 1;
                this.cleanTotalItem();
                this.showListStock = true
                this.form.lots_group = this.form.item.lots_group

            },
            focusTotalItem(change) {
                if(!change && this.form.item.calculate_quantity) {
                    this.$refs.total_item.$el.getElementsByTagName('input')[0].focus()
                    this.total_item = this.form.unit_price_value
                }
            },
            calculateQuantity() {
                if(this.form.item.calculate_quantity) {
                    this.form.quantity = _.round((this.total_item / this.form.price), 4)
                }
            },
            cleanTotalItem(){
                this.total_item = null
            },
            async clickAddItem() {
                
                // if(this.form.item.lots_enabled){
                //     if(!this.form.IdLoteSelected)
                //         return this.$message.error('Debe seleccionar un lote.');
                // }

                if (this.validateTotalItem().total_item) return;

                this.form.tax = _.find(this.taxes, {'id': this.form.tax_id})
                this.form.type_unit = this.form.item.type_unit
                this.form.item.presentation = this.item_unit_type;

                if (this.recordItem){
                    this.form.indexi = this.recordItem.indexi
                }

                // let IdLoteSelected = this.form.IdLoteSelected

                // let select_lots = await _.filter(this.form.item.lots, {'has_sale':true})
                // let un_select_lots = await _.filter(this.form.item.lots, {'has_sale':false})

                // if(this.form.item.series_enabled){
                //     if(select_lots.length != this.form.quantity)
                //         return this.$message.error('La cantidad de series seleccionadas son diferentes a la cantidad a vender');
                // }

                // this.form.IdLoteSelected = IdLoteSelected

                this.$emit('add', this.form);


                if (this.recordItem){
                    this.close()
                }

                this.initForm();

            },
            validateTotalItem(){

                this.errors = {}

                if(this.form.item.calculate_quantity){
                    if(this.total_item < 0.01)
                        this.$set(this.errors, 'total_item', ['total venta item debe ser mayor a 0.01']);
                }

                return this.errors
            },
            reloadDataItems(item_id) {

                this.$http.get(`/main-items/search-by-id/${item_id}`).then((response) => {

                    this.items = response.data.items
                    this.form.item_id = item_id
                    this.changeItem()

                })

            },
            selectedPrice(row)
            {

                // console.log(row)
                let valor = 0
                switch(row.price_default)
                {
                    case 1:
                        valor = row.price1
                        break
                    case 2:
                         valor = row.price2
                        break
                    case 3:
                         valor = row.price3
                        break

                }
                this.form.item_unit_type_id = row.id
                this.item_unit_type = row

                this.form.price = valor
                this.form.item.unit_type_id = row.unit_type_id
                this.calculateQuantity()
            },
            addRowLotGroup(id)
            {
                this.form.IdLoteSelected =  id
            },
            clickLotGroup()
            {
                this.showDialogLots = true
            },
            async clickSelectLots(){
                this.showDialogSelectLots = true
            },
            addRowSelectLot(lots){
                this.lots = lots
            },
            
        }
    }

</script>
