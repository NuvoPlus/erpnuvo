<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span> </li>
                <li><span class="text-muted">Empresa</span></li>
            </ol>
        </div>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Datos de la Empresa</h3>
            </div>
            <div class="card-body">
                <form autocomplete="off" @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group" :class="{'has-danger': errors.trade_name}">
                                    <label class="control-label">Nombre comercial <span class="text-danger">*</span></label>
                                    <el-input :disabled="true" v-model="form.name"></el-input>
                                    <small class="form-control-feedback" v-if="errors.trade_name" v-text="errors.trade_name[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Logo</label>
                                    <el-input v-model="form.logo" :readonly="true">
                                        <el-upload slot="append"
                                                   :headers="headers"
                                                   :data="{'type': 'logo'}"
                                                   action="/companies/uploads"
                                                   :show-file-list="false"
                                                   :on-success="successUpload">
                                            <el-button type="primary" icon="el-icon-upload"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda resoluciones 700x300</small></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">JPG firma facturas</label>
                                    <el-input v-model="form.jpg_firma_facturas" :readonly="true">
                                        <el-upload slot="append"
                                                   :headers="headers"
                                                   :data="{'type': 'jpg_firma_facturas'}"
                                                   action="/companies/uploads"
                                                   :show-file-list="false"
                                                   :on-success="successUpload">
                                            <el-button type="primary" icon="el-icon-upload"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Debe ser un archivo JPG</small></div>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Logo Tienda Virtual</label>
                                    <el-input v-model="form.logo_store" :readonly="true">
                                        <el-upload slot="append"
                                                   :headers="headers"
                                                   :data="{'type': 'logo_store'}"
                                                   action="/companies/uploads"
                                                   :show-file-list="false"
                                                   :on-success="successUpload">
                                            <el-button type="primary" icon="el-icon-upload"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda resoluciones 700x300</small></div>
                                </div>
                            </div> -->
                           <!-- <div class="col-md-6">
                                <div class="form-group" :class="{'has-danger': errors.detraction_account}">
                                    <label class="control-label">N° Cuenta de detracción</label>
                                    <el-input v-model="form.detraction_account"></el-input>
                                    <small class="form-control-feedback" v-if="errors.detraction_account" v-text="errors.detraction_account[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4" v-show="false">
                                <div class="form-group" :class="{'has-danger': errors.operation_amazonia}">
                                    <el-checkbox v-model="form.operation_amazonia">¿Emite en la Amazonía?</el-checkbox>
                                </div>
                            </div>-->
                        </div>

                        <!--<div class="row">
                            <div class="col-md-12 mt-2">
                                <h4 class="border-bottom">Entorno del sistema</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" :class="{'has-danger': errors.soap_type_id}">
                                    <label class="control-label">SOAP Tipo</label>
                                    <el-select :disabled="!form.config_system_env" v-model="form.soap_type_id">
                                        <el-option v-for="option in soap_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>

                                    <el-checkbox
                                           v-if="form.soap_send_id == '02' && form.soap_type_id == '01'"
                                           v-model="toggle"
                                           label="Ingresar Usuario">
                                    </el-checkbox>
                                    <small class="form-control-feedback" v-if="errors.soap_type_id" v-text="errors.soap_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-6" v-if="form.soap_type_id != '03'">
                                <div class="form-group" :class="{'has-danger': errors.soap_send_id}">
                                    <label class="control-label">SOAP Envio</label>
                                    <el-select :disabled="!form.config_system_env" v-model="form.soap_send_id" >
                                        <el-option v-for="(option, index) in soap_sends" :key="index" :value="index" :label="option"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors.soap_send_id" v-text="errors.soap_send_id[0]"></small>
                                </div>
                            </div>
                        </div>
                        <template v-if="form.soap_type_id == '02' || toggle == true ">
                            <div class="row" >
                                <div class="col-md-12 mt-2">
                                    <h4 class="border-bottom">Usuario Secundario Sunat</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-danger': errors.soap_username}">
                                        <label class="control-label">SOAP Usuario <span class="text-danger">*</span></label>
                                        <el-input :disabled="!form.config_system_env" v-model="form.soap_username"></el-input>
                                        <div class="sub-title text-muted"><small>RUC + Usuario. Ejemplo: 01234567890ELUSUARIO</small></div>
                                        <small class="form-control-feedback" v-if="errors.soap_username" v-text="errors.soap_username[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" :class="{'has-danger': errors.soap_password}">
                                        <label class="control-label">SOAP Password <span class="text-danger">*</span></label>
                                        <el-input :disabled="!form.config_system_env" v-model="form.soap_password"></el-input>
                                        <small class="form-control-feedback" v-if="errors.soap_password" v-text="errors.soap_password[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="row" v-if="form.soap_send_id == '02'">
                            <div class="col-md-12">
                                <div class="form-group" :class="{'has-danger': errors.soap_url}">
                                    <label class="control-label">SOAP Url</label>
                                    <el-input v-model="form.soap_url"></el-input>
                                    <small class="form-control-feedback" v-if="errors.soap_url" v-text="errors.soap_url[0]"></small>
                                </div>
                            </div>
                        </div> -->
                    </div>
                   <!-- <div class="form-actions text-right pt-2">
                        <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
                    </div>-->
                </form>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                loading_submit: false,
                headers: headers_token,
                resource: 'companies',
                errors: {},
                form: {},
                soap_sends: [],
                soap_types: [],
                toggle: false, //Creando el objeto a retornar con v-model
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.soap_sends = response.data.soap_sends
                    this.soap_types = response.data.soap_types
                })
            await this.$http.get(`/${this.resource}/record`)
                .then(response => {
                    if (response.data !== '') {
                        this.form = response.data.data
                    }
                })
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    identity_document_type_id: '06000006',
                    number: null,
                    name: null,
                    trade_name: null,
                    soap_send_id: '01',
                    soap_type_id: '01',
                    soap_username: null,
                    soap_password: null,
                    soap_url: null,
                    certificate: null,
                    logo: null,
                    logo_store: null,
                    jpg_firma_facturas: null,
                    detraction_account: null,
                    operation_amazonia: false,
                    toggle: false,
                    config_system_env: false
                }
            },

            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },

            successUpload(response, file, fileList) {
                // console.log(response)
                if (response.success) {
                    this.$message.success(response.message)
                    this.form[response.type] = response.name
                } else {
                    // this.$message({message:'Error al subir el archivo', type: 'error'})
                    this.$message({message: response.message, type: 'error'})
                }
            },
        }
    }
</script>
