<template>
  <div v-if="typeUser == 'admin'">
    <header
      class="page-header"
      style="display: flex; justify-content: space-between; align-items: center"
    >
      <div>
        <h2>Inicio</h2>
      </div>
    </header>
    <div class="row">
      <div class="col-12" v-if="resolutions.length > 0">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <p class="mb-0"><strong>Las siguientes Resoluciones estan por vencer:</strong></p>
          <p class="mb-0" v-for="resolution in resolutions" :key="resolution.id">{{ resolution.prefix }} - {{ resolution.resolution_number }} - Fecha: {{ resolution.resolution_date_end }}</p>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div class="col-xl-12">
        <section class="card card-featured-left card-featured-secondary">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Establecimiento</label>
                  <el-select v-model="form.establishment_id" @change="loadAll">
                    <el-option
                      v-for="option in establishments"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name"
                    ></el-option>
                  </el-select>
                </div>
              </div>
              <div class="col-md-6">
                <label class="control-label">Periodo</label>
                <el-select v-model="form.period" @change="changePeriod">
                  <el-option key="all" value="all" label="Todos"></el-option>
                  <el-option key="month" value="month" label="Por mes"></el-option>
                  <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                  <el-option key="date" value="date" label="Por fecha"></el-option>
                  <el-option key="between_dates" value="between_dates" label="Entre fechas"></el-option>
                </el-select>
              </div>
              <template v-if="form.period === 'month' || form.period === 'between_months'">
                <div class="col-md-6">
                  <label class="control-label">Mes de</label>
                  <el-date-picker
                    v-model="form.month_start"
                    type="month"
                    @change="changeDisabledMonths"
                    value-format="yyyy-MM"
                    format="MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
                </div>
              </template>
              <template v-if="form.period === 'between_months'">
                <div class="col-md-6">
                  <label class="control-label">Mes al</label>
                  <el-date-picker
                    v-model="form.month_end"
                    type="month"
                    :picker-options="pickerOptionsMonths"
                    @change="loadAll"
                    value-format="yyyy-MM"
                    format="MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
                </div>
              </template>
              <template v-if="form.period === 'date' || form.period === 'between_dates'">
                <div class="col-md-6">
                  <label class="control-label">Fecha del</label>
                  <el-date-picker
                    v-model="form.date_start"
                    type="date"
                    @change="changeDisabledDates"
                    value-format="yyyy-MM-dd"
                    format="dd/MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
                </div>
              </template>
              <template v-if="form.period === 'between_dates'">
                <div class="col-md-6">
                  <label class="control-label">Fecha al</label>
                  <el-date-picker
                    v-model="form.date_end"
                    type="date"
                    :picker-options="pickerOptionsDates"
                    @change="loadAll"
                    value-format="yyyy-MM-dd"
                    format="dd/MM/yyyy"
                    :clearable="false"
                  ></el-date-picker>
                </div>
              </template>
              <div class="col-md-6">
                <label class="control-label">Moneda
                    <el-tooltip class="item" effect="dark" content="Filtra por moneda del documento emitido" placement="top-start">
                        <i class="fa fa-info-circle"></i>
                    </el-tooltip>
                </label>
                <el-select v-model="form.currency_id" filterable @change="loadAll">
                    <el-option v-for="option in currencies" :key="option.id" :value="option.id" :label="option.name"></el-option>
                </el-select>
              </div>
            </div>
          </div>
        </section>
      </div>
      <!-- <div class="col-xl-6" v-if="!disc.error">
        <section class="card card-featured-left card-featured-secondary">
          <div class="card-body">
            <div class="widget-summary">
              <div class="widget-summary-col">
                <div class="row no-gutters">
                  <div class="col-md-12 m-b-10">
                    <h4 class="card-title">Disco Duro <small>Porcentaje de uso</small></h4>
                  </div>
                  <div class="col-lg-12 py-2">
                    <div class="summary">
                      <el-progress :percentage="disc.pcent"></el-progress>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="summary">
                      <h4 class="title">
                        Disponible
                      </h4>
                      <el-progress :percentage="disc.avail"></el-progress>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="summary">
                      <h4 class="title">
                        Uso
                      </h4>
                      <el-progress :percentage="disc.used"></el-progress>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div> -->
    </div>
    <div class="row">
      <div class="col-xl-12">
        <div class="row">
          <div class="col-xl-4">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="row no-gutters">
                      <div class="col-md-12 m-b-10">
                        <h2 class="card-title">Facturas Electrónicas</h2>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-info">
                            Total
                            <br />Pagado
                          </h4>
                          <div class="info">
                            <strong class="amount text-info">{{ sale_note.totals.total_payment }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-danger">
                            Total
                            <br />por Pagar
                          </h4>
                          <div class="info">
                            <strong
                              class="amount text-danger"
                            >{{ sale_note.totals.total_to_pay }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title">
                            Total
                            <br />&nbsp;
                          </h4>
                          <div class="info">
                            <strong class="amount">{{ sale_note.totals.total }}</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row m-t-20">
                      <div class="col-md-12">
                        <x-graph type="doughnut" :all-data="sale_note.graph"></x-graph>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <div class="col-xl-4">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="row no-gutters">
                      <div class="col-md-12 m-b-10">
                        <h2 class="card-title">Remisiones</h2>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-info">
                            Total
                            <br />Pagado
                          </h4>
                          <div class="info">
                            <strong class="amount text-info">{{ document.totals.total_payment }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-danger">
                            Total
                            <br />por Pagar
                          </h4>
                          <div class="info">
                            <strong class="amount text-danger">{{ document.totals.total_to_pay }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title">
                            Total
                            <br />&nbsp;
                          </h4>
                          <div class="info">
                            <strong class="amount">{{ document.totals.total }}</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row m-t-20">
                      <div class="col-md-12">
                        <x-graph type="doughnut" :all-data="document.graph"></x-graph>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <div class="col-xl-4">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="row no-gutters">
                      <div class="col-md-12 m-b-10">
                        <h2 class="card-title">Ventas POS</h2>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-info">
                            Total
                            <br />Pagado
                          </h4>
                          <div class="info">
                            <strong class="amount text-info">{{ document_pos.totals.total_payment }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-danger">
                            Total
                            <br />por Pagar
                          </h4>
                          <div class="info">
                            <strong class="amount text-danger">{{ document_pos.totals.total_to_pay }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title">
                            Total
                            <br />&nbsp;
                          </h4>
                          <div class="info">
                            <strong class="amount">{{ document_pos.totals.total }}</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row m-t-20">
                      <div class="col-md-12">
                        <x-graph type="doughnut" :all-data="document_pos.graph"></x-graph>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <!-- <div class="col-xl-6 col-md-6">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body" v-if="general">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="summary">
                      <div class="row no-gutters">
                        <div class="col-md-12 m-b-10">
                          <h2 class="card-title">Totales</h2>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-danger">
                              Total
                              <br />notas de venta
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-danger"
                              >{{ general.totals.total_sale_notes }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-info">
                              Total
                              <br />comprobantes
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-info"
                              >{{ general.totals.total_documents }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title">
                              Total
                              <br />&nbsp;
                            </h4>
                            <div class="info">
                              <strong class="amount">{{ general.totals.total }}</strong>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row m-t-20">
                        <div class="col-md-12">
                          <x-graph-line :all-data="general.graph"></x-graph-line>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>


          <div class="col-xl-3 col-md-3">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body" v-if="document">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="row no-gutters">
                      <div class="col-md-12 m-b-10 mb-4">
                        <h2 class="card-title">Balance Ventas - Compras - Gastos</h2>
                      </div>
                      <div class="col-lg-6">
                        <div class="summary">
                          <h4 class="title text-info">
                            Totales
                            <el-popover placement="right" width="100%" trigger="hover">
                              <p><span class="custom-badge">T. Ventas - T. Compras/Gastos</span></p>
                              <p>Total comprobantes:<span class="custom-badge pull-right">{{ balance.totals.total_document }}</span></p>
                              <p>Total notas de venta:<span class="custom-badge pull-right">{{ balance.totals.total_sale_note }}</span></p>
                              <p>Total compras:<span class="custom-badge pull-right">- {{ balance.totals.total_purchase }}</span></p>
                              <p>Total gastos:<span class="custom-badge pull-right">- {{ balance.totals.total_expense }}</span></p>
                              <el-button icon="el-icon-view" type="primary" size="mini" slot="reference" circle></el-button>
                            </el-popover>
                            <br />
                          </h4>
                          <div class="info">
                            <strong class="amount text-info">{{ balance.totals.all_totals }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="summary">
                          <h4 class="title text-danger">
                            Total Pagos
                            <el-popover placement="right" width="100%" trigger="hover">
                              <p><span class="custom-badge">T. Pagos Ventas - T. Pagos Compras/Gastos</span></p>
                              <p>Total pagos comprobantes:<span class="custom-badge pull-right">{{ balance.totals.total_payment_document }}</span></p>
                              <p>Total pagos notas de venta:<span class="custom-badge pull-right">{{ balance.totals.total_payment_sale_note }}</span></p>
                              <p>Total pagos compras:<span class="custom-badge pull-right">- {{ balance.totals.total_payment_purchase }}</span></p>
                              <p>Total pagos gastos:<span class="custom-badge pull-right">- {{ balance.totals.total_payment_expense }}</span></p>
                              <el-button icon="el-icon-view" type="danger" size="mini" slot="reference" circle></el-button>
                            </el-popover>
                            <br />
                          </h4>
                          <div class="info">
                            <strong class="amount text-danger">{{ balance.totals.all_totals_payment }}</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row m-t-20">
                      <div class="col-md-12">
                        <x-graph type="doughnut" :all-data="balance.graph"></x-graph>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>


          <div class="col-xl-3 col-md-3">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body" v-if="utilities">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="row no-gutters">
                      <div class="col-md-12 m-b-10">
                        <h2 class="card-title">Utilidades/Ganancias</h2>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-info">
                            Ingreso
                          </h4>
                          <div class="info">
                            <strong class="amount text-info">{{ utilities.totals.total_income }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title text-danger">
                            Egreso
                          </h4>
                          <div class="info">
                            <strong class="amount text-danger">{{ utilities.totals.total_egress }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="summary">
                          <h4 class="title">
                            Utilidad
                            <br />&nbsp;
                          </h4>
                          <div class="info">
                            <strong class="amount">{{ utilities.totals.utility }}</strong>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12 ">
                        <div class="summary">
                          <h4 class="title">
                            <br>
                            <el-checkbox  v-model="form.enabled_expense" @change="loadDataUtilities">Considerar gastos</el-checkbox><br>
                          </h4>
                        </div>
                      </div>
                      <div class="col-lg-12 ">
                        <div class="summary">
                          <h4 class="title">
                            <el-checkbox  v-model="filter_item" @change="changeFilterItem">Filtrar por producto</el-checkbox><br>
                          </h4>
                        </div>
                      </div>
                      <div class="col-lg-12 " v-if="filter_item">
                        <div class="summary">
                          <h4 class="title">
                            <div class="form-group">
                                <el-select v-model="form.item_id" filterable remote  popper-class="el-select-customers"  clearable
                                    placeholder="Buscar producto"
                                    :remote-method="searchRemoteItems"
                                    :loading="loading_search"
                                    @change="loadDataUtilities">
                                    <el-option v-for="option in items" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                            </div>
                          </h4>
                        </div>
                      </div>
                    </div>
                    <div class="row m-t-20">
                      <div class="col-md-12">
                        <x-graph type="doughnut" :all-data="utilities.graph"></x-graph>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div> -->


          <!-- <div class="col-xl-6 col-md-6">
            <section class="card card-featured-left card-featured-secondary">
              <div class="card-body" v-if="general">
                <div class="widget-summary">
                  <div class="widget-summary-col">
                    <div class="summary">
                      <div class="row no-gutters">
                        <div class="col-md-12 m-b-10">
                          <h2 class="card-title">
                            Compras
                            <el-tooltip
                              class="item"
                              effect="dark"
                              content="Aplica filtro por establecimiento"
                              placement="top-start"
                            >
                              <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                          </h2>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-danger">
                              Total
                              <br />percepciones
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-danger"
                              >{{ purchase.totals.purchases_total_perception }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title text-info">
                              Total
                              <br />compras
                            </h4>
                            <div class="info">
                              <strong
                                class="amount text-info"
                              >{{ purchase.totals.purchases_total }}</strong>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="summary">
                            <h4 class="title">
                              Total
                              <br />&nbsp;
                            </h4>
                            <div class="info">
                              <strong class="amount">{{ purchase.totals.total }}</strong>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row m-t-20">
                        <div class="col-md-12">
                          <x-graph-line :all-data="purchase.graph"></x-graph-line>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div> -->

          <div class="col-xl-3 col-md-6">
            <section class="card">
              <div class="card-body">
                <h2 class="card-title">Ventas por producto</h2>
                <div class="mt-3">
                  <el-checkbox  v-model="form.enabled_move_item" @change="loadDataAditional">Ordenar por movimientos</el-checkbox><br>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th class="text-right">
                          Mov.
                            <el-tooltip
                              class="item"
                              effect="dark"
                              content="Movimientos (Cantidad de veces vendido)"
                              placement="top-start"
                            >
                              <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </th>
                        <th class="text-right">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template v-for="(row, index) in items_by_sales">
                        <tr :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>{{ row.internal_id }}</td>
                          <td>{{ row.description }}</td>
                          <td class="text-right">{{ row.move_quantity }}</td>
                          <td class="text-right">{{ row.total }}</td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
              </div>
            </section>
          </div>
          <div class="col-xl-3 col-md-6">
            <section class="card">
              <div class="card-body">
                <h2 class="card-title">Top clientes</h2>
                <div class="mt-3">
                  <el-checkbox  v-model="form.enabled_transaction_customer" @change="loadDataAditional">Ordenar por transacciones</el-checkbox><br>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th class="text-right">
                          Trans.
                            <el-tooltip
                              class="item"
                              effect="dark"
                              content="Transacciones (Cantidad de ventas realizadas)"
                              placement="top-start"
                            >
                              <i class="fa fa-info-circle"></i>
                            </el-tooltip>
                        </th>
                        <th class="text-right">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template v-for="(row, index) in top_customers">
                        <tr :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>
                            {{ row.name }}
                            <br />
                            <small v-text="row.number"></small>
                          </td>
                          <td class="text-right">{{ row.transaction_quantity }}</td>
                          <td class="text-right">{{ row.total }}</td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
              </div>
            </section>
          </div>

          <div class="col-xl-6 col-md-12 col-lg-12">
            <dashboard-stock></dashboard-stock>
          </div>

        </div>
      </div>

      <div class="col-xl-4"></div>
    </div>
  </div>
</template>
<style>
.widget-summary .summary {
  min-height: inherit;
}

.custom-badge {
  font-size: 15px;
  font-weight: bold;
}
</style>
<script>
// import DocumentPayments from "../../../../../../resources/js/views/tenant/documents/partials/payments.vue";
// import SaleNotePayments from "../../../../../../resources/js/views/tenant/sale_notes/partials/payments.vue";
import DashboardStock from "./partials/dashboard_stock.vue";
import queryString from "query-string";

export default {
  props: ["typeUser", "soapCompany"],
  components: { DashboardStock },
  data() {
    return {
      loading_search:false,
      records_base: [],
      selected_customer: null,
      customers: [],
      resource: "dashboard",
      establishments: [],
      balance: {
        totals: {},
        graph: {}
      },
      document: {
        totals: {},
        graph: {}
      },
      sale_note: {
        totals: {},
        graph: {}
      },
      document_pos: {
        totals: {},
        graph: {}
      },
      general: {
        totals: {},
        graph: {}
      },
      purchase: {
        totals: {},
        graph: {}
      },
      utilities: {
        totals: {},
        graph: {}
      },
      disc: [],
      form: {},
      pickerOptionsDates: {
        disabledDate: time => {
          time = moment(time).format("YYYY-MM-DD");
          return this.form.date_start > time;
        }
      },
      pickerOptionsMonths: {
        disabledDate: time => {
          time = moment(time).format("YYYY-MM");
          return this.form.month_start > time;
        }
      },
      records: [],
      items_by_sales: [],
      top_customers: [],
      recordId: null,
      showDialogDocumentPayments: false,
      showDialogSaleNotePayments: false,
      filter_item:false,
      all_items: [],
      items:[],
      currencies:[],
      resolutions: [],
    };
  },
  async created() {
    this.initForm();
    await this.$http.get(`/${this.resource}/filter`).then(response => {
      this.establishments = response.data.establishments;
      this.currencies = response.data.currencies;
      this.form.establishment_id =
        this.establishments.length > 0 ? this.establishments[0].id : null;
    });
    await this.loadAll();
    await this.filterItems();
    await this.getResolutions();

    // this.$eventHub.$on("reloadDataUnpaid", () => {
    //   this.loadAll();
    // });
  },

  methods: {
    changeFilterItem(){
      this.form.item_id = null
      this.loadDataUtilities()
    },
    searchRemoteItems(input) {

        if (input.length > 1) {

            this.loading_search = true
            let parameters = `input=${input}`


            this.$http.get(`/reports/data-table/items/?${parameters}`)
                    .then(response => {
                        this.items = response.data.items
                        this.loading_search = false

                        if(this.items.length == 0){
                            this.filterItems()
                        }
                    })
        } else {
            this.filterItems()
        }

    },
    filterItems() {
        this.items = this.all_items
    },
    calculateTotalCurrency(currency_type_id, exchange_rate_sale,  total )
    {
        if(currency_type_id == 'USD')
        {
            return parseFloat(total) * exchange_rate_sale;
        }
        else{
            return parseFloat(total);
        }
    },
    clickDownloadDispatch(download) {
      window.open(download, "_blank");
    },
    clickDownload(type) {
      let query = queryString.stringify({
        ...this.form
      });
      window.open(`/reports/no_paid/${type}/?${query}`, "_blank");
    },
    initForm() {
      this.form = {
        currency_id: 170,
        item_id: null,
        establishment_id: null,
        enabled_expense: null,
        enabled_move_item:false,
        enabled_transaction_customer:false,
        period: "month",
        date_start: moment().format("YYYY-MM-DD"),
        date_end: moment().format("YYYY-MM-DD"),
        month_start: moment().format("YYYY-MM"),
        month_end: moment().format("YYYY-MM"),
        customer_id: null
      };
    },
    changeDisabledDates() {
      if (this.form.date_end < this.form.date_start) {
        this.form.date_end = this.form.date_start;
      }
      this.loadAll();
    },
    changeDisabledMonths() {
      if (this.form.month_end < this.form.month_start) {
        this.form.month_end = this.form.month_start;
      }
      this.loadAll();
    },
    changePeriod() {
      if (this.form.period === "month") {
        this.form.month_start = moment().format("YYYY-MM");
        this.form.month_end = moment().format("YYYY-MM");
      }
      if (this.form.period === "between_months") {
        this.form.month_start = moment()
          .startOf("year")
          .format("YYYY-MM"); //'2019-01';
        this.form.month_end = moment()
          .endOf("year")
          .format("YYYY-MM");
      }
      if (this.form.period === "date") {
        this.form.date_start = moment().format("YYYY-MM-DD");
        this.form.date_end = moment().format("YYYY-MM-DD");
      }
      if (this.form.period === "between_dates") {
        this.form.date_start = moment()
          .startOf("month")
          .format("YYYY-MM-DD");
        this.form.date_end = moment()
          .endOf("month")
          .format("YYYY-MM-DD");
      }
      this.loadAll();
    },
    loadAll() {
      this.loadData();
     // this.loadUnpaid();
      this.loadDataAditional();
      // this.loadDataUtilities();
      //this.loadCustomer();
    },
    loadData() {
      this.$http.post(`/${this.resource}/data`, this.form).then(response => {
        this.document = response.data.data.document; // remisiones - remission
        this.document_pos = response.data.data.document_pos;
        this.balance = response.data.data.balance;
        this.sale_note = response.data.data.sale_note;
        this.general = response.data.data.general;
        this.customers = response.data.data.customers;
        this.items = response.data.data.items;
      });
      this.$http.get(`/command/df`).then(response => {
        if (response.data[0] != 'error'){
          this.disc.used = Number(response.data[0].replace(/[^0-9\.]+/g,""));
          this.disc.avail = Number(response.data[1].match(/\d/g).join(""));
          this.disc.pcent = Number(response.data[2].match(/\d/g).join(""));
        } else {
          this.disc.error = true;
        }
      });
    },
    loadDataAditional() {
      this.$http
        .post(`/${this.resource}/data_aditional`, this.form)
        .then(response => {
          this.purchase = response.data.data.purchase;
          this.items_by_sales = response.data.data.items_by_sales;
          this.top_customers = response.data.data.top_customers;
        });
    },
    loadDataUtilities() {
      this.$http
        .post(`/${this.resource}/utilities`, this.form)
        .then(response => {
          this.utilities = response.data.data.utilities;
        });
    },
    getResolutions() {
      axios.get(`/co-configuration-all`).then(response => {
        this.resolutions = this.expiringResolutions(response.data.typeDocuments);
      }).catch(error => {
        console.error(error)
      }).then(() => {});
    },
    expiringResolutions(resolutions) {
      const today = new Date();
      const limitDate = new Date();
      limitDate.setDate(today.getDate() + 15);

      return resolutions.filter((resolution) => {
        if (resolution.resolution_date_end) {
          const resolutionEndDate = new Date(resolution.resolution_date_end);
          return resolutionEndDate >= today && resolutionEndDate <= limitDate;
        }
        return false;
      });
    }
  }
};
</script>
