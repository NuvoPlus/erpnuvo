<template>
    <el-dialog width="60%" :title="titleDialog" :visible="showDialog" @close="close" @open="create" @opened="opened" :close-on-click-modal="false" append-to-body>
      <form autocomplete="off" @submit.prevent="submit">
        <div class="form-body">
          <!-- FILA: Tipo de Documento,  Número de Identificación, DV y Nombre -->
          <div class="row">
            <div class="col-md-3">
              <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                <label class="control-label">Tipo de documento</label>
                <el-select v-model="form.identity_document_type_id" filterable>
                  <el-option
                    v-for="option in identity_document_types"
                    :key="option.id"
                    :value="option.id"
                    :label="option.name">
                  </el-option>
                </el-select>
                <small class="form-control-feedback" v-if="errors.identity_document_type_id" v-text="errors.identity_document_type_id[0]"></small>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group" :class="{'has-danger': errors.number}">
                <label class="control-label">N° Identificación</label>
                <el-input
                v-model="form.number"
                :maxlength="maxLength"
                dusk="number"
                @keydown.enter.native.stop.prevent="changeNumberIdentification">
                <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="changeNumberIdentification">
                </el-button>
                </el-input>
                <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group" :class="{'has-danger': errors.dv}">
                <label class="control-label">Dv</label>
                <el-input v-model="form.dv"></el-input>
                <small class="form-control-feedback" v-if="errors.dv" v-text="errors.dv[0]"></small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group" :class="{'has-danger': errors.name}">
                <label class="control-label">Nombre</label>
                <el-input v-model="form.name"></el-input>
                <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
              </div>
            </div>
          </div>
          <!-- FIN FILA Número, DV y Nombre -->

          <!-- FILA: Correo electrónico y Checkbox para campos adicionales -->
          <div class="row align-items-center">
            <div class="col-md-8">
              <div class="form-group" :class="{'has-danger': errors.email}">
                <label class="control-label">Correo electrónico</label>
                <el-input v-model="form.email" dusk="email"></el-input>
                <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
              </div>
            </div>
            <div class="col-md-4 d-flex justify-content-center align-items-center aling-div">
              <div class="form-group text-center">
                <el-checkbox v-model="showAdditionalFields" size="large">
                  <span class="custom-checkbox-label">Llenar información adicional</span>
                </el-checkbox>
              </div>
            </div>
          </div>
          <!-- FIN FILA -->

          <!-- Campos adicionales (se muestran si showAdditionalFields es verdadero) -->
          <div v-if="showAdditionalFields">
            <div class="row">
              <!-- Tipo de persona -->
              <div class="col-md-3">
                <div class="form-group" :class="{'has-danger': errors.type_person_id}">
                  <label class="control-label">Tipo de persona</label>
                  <el-select v-model="form.type_person_id" filterable>
                    <el-option
                      v-for="option in type_persons"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name">
                    </el-option>
                  </el-select>
                  <small class="form-control-feedback" v-if="errors.type_person_id" v-text="errors.type_person_id[0]"></small>
                </div>
              </div>
              <!-- Otros campos adicionales -->
              <div class="col-md-3">
                <div class="form-group" :class="{'has-danger': errors.type_regime_id}">
                  <label class="control-label">Tipo de régimen</label>
                  <el-select v-model="form.type_regime_id" filterable>
                    <el-option
                      v-for="option in type_regimes"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name">
                    </el-option>
                  </el-select>
                  <small class="form-control-feedback" v-if="errors.type_regime_id" v-text="errors.type_regime_id[0]"></small>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group" :class="{'has-danger': errors.type_obligation_id}">
                  <label class="control-label">Tipo de obligación</label>
                  <el-select v-model="form.type_obligation_id" filterable>
                    <el-option
                      v-for="option in type_obligations"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name">
                    </el-option>
                  </el-select>
                  <small class="form-control-feedback" v-if="errors.type_obligation_id" v-text="errors.type_obligation_id[0]"></small>
                </div>
              </div>
              <!-- Bloque para País, Departamento y Ciudad -->
              <div class="col-md-4">
                <div class="form-group" :class="{'has-danger': errors.country_id}">
                  <label class="control-label">País</label>
                  <el-select v-model="form.country_id" filterable @change="departmentss()">
                    <el-option
                      v-for="option in countries"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name">
                    </el-option>
                  </el-select>
                  <small class="form-control-feedback" v-if="errors.country_id" v-text="errors.country_id[0]"></small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group" :class="{'has-danger': errors.department_id}">
                  <label class="control-label">Departamento</label>
                  <el-select v-model="form.department_id" filterable @change="citiess()">
                    <el-option
                      v-for="option in departments"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name">
                    </el-option>
                  </el-select>
                  <small class="form-control-feedback" v-if="errors.department_id" v-text="errors.department_id[0]"></small>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group" :class="{'has-danger': errors.city_id}">
                  <label class="control-label">Ciudad</label>
                  <el-select v-model="form.city_id" filterable>
                    <el-option
                      v-for="option in cities"
                      :key="option.id"
                      :value="option.id"
                      :label="option.name">
                    </el-option>
                  </el-select>
                  <small class="form-control-feedback" v-if="errors.city_id" v-text="errors.city_id[0]"></small>
                </div>
              </div>
            </div>
            <!-- Fila adicional: Teléfono, Dirección y Código interno -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group" :class="{'has-danger': errors.telephone}">
                  <label class="control-label">Teléfono</label>
                  <el-input type="tel" maxlength="10" v-model="form.telephone"
                            onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106) || (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )">
                  </el-input>
                  <small class="form-control-feedback" v-if="errors.telephone" v-text="errors.telephone[0]"></small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" :class="{'has-danger': errors.address}">
                  <label class="control-label">Dirección</label>
                  <el-input v-model="form.address" dusk="address"></el-input>
                  <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" :class="{'has-danger': errors.code}">
                  <label class="control-label">Código interno</label>
                  <el-input v-model="form.code"></el-input>
                  <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                </div>
              </div>
            </div>
            <!-- Bloque de Contacto -->
            <div class="row border-top mt-2">
              <div class="col-12">
                <h4>Contacto</h4>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Nombre y Apellido</label>
                  <el-input v-model="form.contact_name"></el-input>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">Teléfono</label>
                  <el-input v-model="form.contact_phone"></el-input>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin de campos adicionales -->
        </div>
        <div class="form-actions text-right mt-4">
          <el-button @click.prevent="close()">Cancelar</el-button>
          <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
        </div>
      </form>
    </el-dialog>
  </template>

  <style>
  .aling-div {
    margin-top: 25px;
  }
  .custom-checkbox-label {
    font-size: 15pt; /* Ajusta el tamaño de la letra */
  }
  .el-checkbox .el-checkbox__input {
    transform: scale(1.3); /* Ajusta el tamaño del checkbox */
  }
  </style>

  <script>
  import { calcularDv } from '../../../functions/Nit';

  export default {
    props: ['showDialog', 'type', 'recordId', 'external', 'document_type_id', 'input_person'],
    data() {
      return {
        loading_submit: false,
        titleDialog: null,
        resource: 'persons',
        errors: {},
        api_service_token: false,
        form: {},
        countries: [],
        departments: [],
        cities: [],
        identity_document_types: [],
        person_types: [],
        type_regimes: [],
        type_obligations: [],
        type_persons: [],
        loading_search: false,
        showAdditionalFields: false // Estado del checkbox para mostrar campos adicionales
      }
    },

    async created() {
      await this.initForm();
      await this.$http.get(`/${this.resource}/tables`)
        .then(response => {
          this.api_service_token = response.data.api_service_token;
          this.countries = response.data.countries;
          this.identity_document_types = response.data.identity_document_types;
          this.person_types = response.data.person_types;
          this.type_persons = response.data.typePeople;
          this.type_regimes = response.data.typeRegimes;
          this.type_obligations = response.data.typeObligations;
          // Se sobrescribe identity_document_types con la información de documentos desde 'tables'
          this.identity_document_types = response.data.typeIdentityDocuments;
        });
    },

    computed: {
      maxLength() {
        // Mapeo: clave numérica => cantidad máxima de dígitos permitidos.
        const mapping = {
        1: 10,    // Registro civil
        2: 10,   // Tarjeta de identidad
        3: 10,   // Cédula de ciudadanía
        4: 10,   // Tarjeta de extranjería
        5: 10,   // Cédula de extranjería
        6: 11,   // NIT
        7: 10,   // Pasaporte
        8: 22,   // Documento de identificación extranjero
        9: 22,   // NIT de otro país
        10: 10,  // NUIP *
        };
        // Se fuerza la conversión a Number para que la llave coincida correctamente en el mapeo.
        return mapping[Number(this.form.identity_document_type_id)] || 10;
      }
    },

    watch: {
      // Al cambiar el tipo de documento, se asigna automáticamente el tipo de persona:
      // Si es NIT (id == 6) se selecciona "Persona Jurídica" (se asume id 1),
      // de lo contrario se asigna "Persona Natural" (se asume id 2).
      'form.identity_document_type_id'(newVal) {
        if(newVal == 6) {
          this.form.type_person_id = 1;  // Persona Jurídica
        } else {
          this.form.type_person_id = 2;  // Persona Natural
        }
      }
    },

    methods: {
      /**
       * Calcula el dígito verificador y consulta el API DIAN a través del backend.
       * Los datos devueltos se mapean a los campos del formulario.
       */
      async changeNumberIdentification() {
        if (this.form.number) {
          this.loading_search = true;
          // Calcula el DV
          this.form.dv = await calcularDv(this.form.number);

          try {
            // Llama al endpoint que consulta DIAN
            const response = await this.$http.post('/persons/query-dian', {
              identification_number: this.form.number,
              type_document_id: this.form.identity_document_type_id
            });
//console.log(response.data);
            if (response.data.success) {
              let data = response.data.data;
              // Mapea la respuesta DIAN al formulario
              this.form.name = data.business_name;
              this.form.dv = data.dv;
              this.form.email = data.email;
              // Se pueden mapear otros campos según la respuesta
            } else {
              this.$message.error(response.data.message || "No se encontró información en DIAN");
            }
          } catch (error) {
            console.error("Error al consultar DIAN:", error);
            this.$message.error("Error al consultar DIAN");
          } finally {
            this.loading_search = false;
          }
        }
      },

      // Método original que consultaba el nombre del cliente (si se requiere conservar)
      async searchNameClient() {
        if (this.form.number.length < 8) return;
        await this.$http.get(`/${this.resource}/searchName/${this.form.number}`)
          .then(response => {
            if (response.data.data) {
              this.form.name = response.data.data;
            }
          })
          .catch(error => {})
          .then(() => {});
      },

      getDepartment(val) {
        return axios.post(`/departments/${val}`)
          .then(response => response.data)
          .catch(error => console.log(error));
      },

      getCities(val) {
        return axios.post(`/cities/${val}`)
          .then(response => response.data)
          .catch(error => console.log(error));
      },

      initForm() {
        this.errors = {};
        this.$http.get('/companies/record').then(response => {
          this.idIdentification = response.data.data.logo;
          let address = null;
          let telephone = null;

          if (this.idIdentification === 'logo_7715537.jpg') {
            address = 'CR 14 15 70 BRR CENTRO';
            telephone = '3203468640';
          }

          if (!this.recordId) {
            this.form = {
              id: null,
              type: this.type,
              number: '',
              name: null,
              trade_name: null,
              country_id: 47,
              department_id: null,
              address: address,
              telephone: telephone,
              email: null,
              perception_agent: false,
              percentage_perception: 0,
              person_type_id: 2, // Valor por defecto: Persona Natural
              comment: null,
              type_person_id: 2,
              type_regime_id: 2,
              identity_document_type_id: 3, // Valor inicial, puede modificarse mediante el select
              type_obligation_id: 117,
              addresses: [],
              city_id: null,
              code: null,
              dv: null,
              contact_phone: null,
              contact_name: null,
              postal_code: null,
            };
          }
        }).catch(error => {
          console.error('Error al obtener los datos de la compañía:', error);
        });
        this.departmentss();
        this.citiess();
      },

      departmentss(edit = false) {
        if (!edit) {
          this.form.department_id = null;
          this.form.city_id = null;
          this.departments = [];
          this.cities = [];
        }

        this.$http.get('/companies/record').then(response => {
          this.idIdentification = response.data.data.logo;

          if (this.form.country_id != null) {
            this.getDepartment(this.form.country_id).then(departmentRows => {
              this.departments = departmentRows;

              if (!edit) {
                if (this.idIdentification === 'logo_7715537.jpg') {
                  let valorPorDefecto = 779;
                  if (this.departments.some(dept => dept.id === valorPorDefecto)) {
                    this.form.department_id = valorPorDefecto;
                  }
                } else {
                  this.form.department_id = null;
                }
              }

              this.citiess(edit);
            });
          }
        }).catch(error => {
          console.error('Error al obtener información de la compañía:', error);
        });
      },

      citiess(edit = false) {
        if (!edit) {
          this.form.city_id = null;
          this.cities = [];
        }

        if (this.form.department_id != null) {
          this.getCities(this.form.department_id).then(cityRows => {
            this.cities = cityRows;

            if (!edit) {
              if (this.idIdentification === 'logo_7715537.jpg') {
                let valorPorDefectoCiudad = 12688;
                if (this.cities.some(city => city.id === valorPorDefectoCiudad)) {
                  this.form.city_id = valorPorDefectoCiudad;
                }
              } else {
                this.form.city_id = null;
              }
            }
          }).catch(error => {
            console.error('Error al cargar ciudades:', error);
            this.cities = [];
          });
        }
      },

      async opened() {
        if (this.external && this.input_person) {
          if (this.form.number.length === 8 || this.form.number.length === 11) {
            if (this.api_service_token != false) {
              await this.$eventHub.$emit('enableClickSearch');
            } else {
              this.searchCustomer();
            }
          }
        }
      },

      create() {
        if (this.external) {
          if (this.document_type_id === '01') {
            this.form.identity_document_type_id = '6';
          }
          if (this.document_type_id === '03') {
            this.form.identity_document_type_id = '1';
          }

          if (this.input_person) {
            this.form.identity_document_type_id = (this.input_person.identity_document_type_id)
              ? this.input_person.identity_document_type_id
              : this.form.identity_document_type_id;
            this.form.number = (this.input_person.number) ? this.input_person.number : '';
          }
        }
        if (this.type === 'customers') {
          this.titleDialog = (this.recordId) ? 'Editar Cliente' : 'Nuevo Cliente';
        }
        if (this.type === 'suppliers') {
          this.titleDialog = (this.recordId) ? 'Editar Proveedor' : 'Nuevo Proveedor';
        }
        if (this.recordId) {
          this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
            this.form = response.data.data;
            this.departmentss(true);
            this.citiess(true);
          });
        } else {
          this.initForm();
        }
      },

      clickAddAddress() {
        this.form.addresses.push({
          'id': null,
          'country_id': 'PE',
          'location_id': [],
          'address': null,
          'email': null,
          'phone': null,
          'main': false,
        });
      },

      submit() {
            // Si no se muestran campos adicionales se asignan valores predeterminados.
            if (!this.showAdditionalFields) {
                this.form.country_id = 47;
                this.form.department_id = 779;
                this.form.city_id = 12688;
                this.form.telephone = '9999999999';
                this.form.address = 'CR 00 00 00 BRR N/A';
                this.form.code = this.form.number;
                this.form.contact_phone = null;
                this.form.contact_name = null;
            }

            // Agregamos el flag que indica que la request proviene de la factura
            this.form.from_invoice = true;

            this.loading_submit = true;
            this.$http.post(`/${this.resource}`, this.form)
            .then(response => {
                if (response.data.success) {
                    // La respuesta exitosa contendrá el ID del cliente ya existente o el creado
                    this.$message.success(response.data.message);
                    // Emitir el evento para que el componente de factura cargue el cliente
                    if (this.external) {
                        this.$eventHub.$emit('reloadDataPersons', response.data.id);
                    } else {
                        this.$eventHub.$emit('reloadData');
                    }
                    this.close();
                } else {
                    this.$message.error(response.data.message);
                }
            })
            .catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data;
                } else {
                    console.log(error);
                }
            })
            .then(() => {
                this.loading_submit = false;
            });
      },

      changeIdentityDocType() {
        (this.recordId == null) ? this.setDataDefaultCustomer() : null;
      },

      setDataDefaultCustomer() {
        if (this.form.identity_document_type_id == '0') {
          this.form.number = '99999999';
          this.form.name = "Clientes - Varios";
        } else {
          this.form.number = '';
          this.form.name = null;
        }
      },

      close() {
        this.$eventHub.$emit('initInputPerson');
        this.$emit('update:showDialog', false);
      },

      searchCustomer() {
        this.searchServiceNumberByType();
      },

      searchNumber(data) {
        this.form.name = (this.form.identity_document_type_id === '1') ? data.nombre_completo : data.nombre_o_razon_social;
        this.form.trade_name = (this.form.identity_document_type_id === '6') ? data.nombre_o_razon_social : '';
        this.form.location_id = data.ubigeo;
        this.form.address = data.direccion;
        this.form.department_id = (data.ubigeo) ? data.ubigeo[0] : null;
        this.form.province_id = (data.ubigeo) ? data.ubigeo[1] : null;
        this.form.district_id = (data.ubigeo) ? data.ubigeo[2] : null;
        this.form.condition = data.condicion;
        this.form.state = data.estado;

        this.filterProvinces();
        this.filterDistricts();
      },

      clickRemoveAddress(index) {
        this.form.addresses.splice(index, 1);
      }
    }
  }
  </script>
