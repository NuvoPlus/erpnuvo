<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label class="control-label">Correo Electrónico</label>
                            <el-input v-model="form.email" :disabled="form.id!=null"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.establishment_id}">
                            <label class="control-label">Establecimiento</label>
                            <el-select v-model="form.establishment_id" filterable>
                                <el-option v-for="option in establishments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.establishment_id" v-text="errors.establishment_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-12" v-show="form.id">
                        <div class="form-group" :class="{'has-danger': errors.api_token}">
                            <label class="control-label">Api Token</label>
                            <el-input v-model="form.api_token" :readonly="form.id!=null"></el-input>
                            <small class="form-control-feedback" v-if="errors.api_token" v-text="errors.api_token[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.password}">
                            <label class="control-label">Contraseña</label>
                            <el-input v-model="form.password"></el-input>
                            <small class="form-control-feedback" v-if="errors.password" v-text="errors.password[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.password_confirmation}">
                            <label class="control-label">Confirmar Contraseña</label>
                            <el-input v-model="form.password_confirmation"></el-input>
                            <small class="form-control-feedback" v-if="errors.password_confirmation" v-text="errors.password_confirmation[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="typeUser != 'integrator'">
                        <div class="form-group" :class="{'has-danger': errors.type}">
                            <label class="control-label">Perfil</label>
                            <el-select v-model="form.type" :disabled="form.id===1">
                                <el-option v-for="option in types" :key="option.type" :value="option.type" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type" v-text="errors.type[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="typeUser != 'integrator' && form.modules.some(m => m.description === 'Ventas' && m.checked)">
                        <div class="form-group" :class="{'has-danger': errors.fe_resolution_id}">
                            <label class="control-label">Resolucion FE</label>
                            <el-select v-model="form.fe_resolution_id" clearable>
                                <el-option v-for="option in fe_resolutions" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.fe_resolution_id" v-text="errors.fe_resolution_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="typeUser != 'integrator' && form.modules.some(m => m.description === 'Ventas' && m.checked)">
                        <div class="form-group" :class="{'has-danger': errors.nc_resolution_id}">
                            <label class="control-label">Resolucion NC</label>
                            <el-select v-model="form.nc_resolution_id" clearable>
                                <el-option v-for="option in nc_resolutions" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.nc_resolution_id" v-text="errors.nc_resolution_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="typeUser != 'integrator' && form.modules.some(m => m.description === 'Ventas' && m.checked)">
                        <div class="form-group" :class="{'has-danger': errors.nd_resolution_id}">
                            <label class="control-label">Resolucion ND</label>
                            <el-select v-model="form.nd_resolution_id" clearable>
                                <el-option v-for="option in nd_resolutions" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.nd_resolution_id" v-text="errors.nd_resolution_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="typeUser != 'integrator' && form.modules.some(m => m.description === 'Nóminas' && m.checked)">
                        <div class="form-group" :class="{'has-danger': errors.ni_resolution_id}">
                            <label class="control-label">Resolucion Nomina</label>
                            <el-select v-model="form.ni_resolution_id" clearable>
                                <el-option v-for="option in ni_resolutions" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.ni_resolution_id" v-text="errors.ni_resolution_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="typeUser != 'integrator'">
                        <div class="form-group">
                            <label class="control-label">Módulos</label>
                            <div class="row">
                                <div class="col-4" v-for="module in form.modules">
                                    <el-checkbox v-model="module.checked" :disabled="form.locked" @change="changeModule(module.id, module.checked)">{{ module.description }}</el-checkbox>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3" v-if="typeUser != 'integrator' && show_levels" >
                        <div class="form-group">
                            <label class="control-label">Nivel de acceso del módulo ventas</label>
                            <div class="row">
                                <div class="col-4" v-for="level in form.levels">
                                    <el-checkbox v-model="level.checked" :disabled="form.locked" >{{ level.description }}</el-checkbox>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    import {EventBus} from '../../../helpers/bus'

    export default {
        props: ['showDialog', 'recordId','typeUser'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'users',
                errors: {},
                form: {
                    modules: [],
                },
                modules: [],
                fe_resolutions: [],
                nc_resolutions: [],
                nd_resolutions: [],
                ni_resolutions: [],
                establishments: [],
                types: [],
                show_levels:false
            }
        },

        async created() {
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.modules = response.data.modules
                    this.establishments = response.data.establishments
                    this.types = response.data.types
                    this.fe_resolutions = response.data.fe_resolutions
                    this.nc_resolutions = response.data.nc_resolutions
                    this.nd_resolutions = response.data.nd_resolutions
                    this.ni_resolutions = response.data.ni_resolutions
                })
            await this.initForm()
        },

        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    name: null,
                    email: null,
                    api_token: null,
                    establishment_id: null,
                    password: null,
                    password_confirmation: null,
                    locked:false,
                    type:null,
                    fe_resolution_id: null,
                    nc_resolution_id: null,
                    nd_resolution_id: null,
                    ni_resolution_id: null,
                    modules: [],
                    levels: [],
                }

                this.modules.forEach(module => {
                    this.form.modules.push({
                        id: module.id,
                        description: module.description,
                        checked: false
                    })
                })
                this.show_levels = false
                // console.log(this.form.levels)
            },

            create() {
                this.titleDialog = (this.recordId) ? 'Editar Usuario' : 'Nuevo Usuario'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                            this.show_levels = (this.form.levels.length > 0) ? true : false
                        })
                }
            },

            async changeModule(module_id, checked){
                if(module_id == 1){
                    if(checked){
                        // console.log(mdl)
                        if(this.form.levels.length == 0 ){
                            let mdl = await _.find(this.modules, {'id':module_id})
                            mdl.levels.forEach(level => {
                                this.form.levels.push({
                                    id: level.id,
                                    level_id: level.id,
                                    module_id: level.module_id,
                                    description: level.description,
                                    checked: false
                                })
                            })
                            this.show_levels = true
                        }
                    }else{
                        this.form.levels = []
                        this.show_levels = false
                    }
                }
            },

            submit() {
                // console.log(this.form)
                this.loading_submit = true
                // Buscar la resolución fe seleccionada
                const selectedResolutionfe = this.fe_resolutions.find(
                    r => r.id === this.form.fe_resolution_id
                );
                // Validar si resolucion fe si está vencida
                if (selectedResolutionfe && selectedResolutionfe.vencida) {
                    this.$message.warning('No puede seleccionar una resolución FE vencida.');
                    this.loading_submit = false;
                    return;
                }
                // Buscar la resolución nc seleccionada
                const selectedResolutionnc = this.nc_resolutions.find(
                    r => r.id === this.form.nc_resolution_id
                );
                // Validar si resolucion nc si está vencida
                if (selectedResolutionnc && selectedResolutionnc.vencida) {
                    this.$message.warning('No puede seleccionar una resolución NC vencida.');
                    this.loading_submit = false;
                    return;
                }
                // Buscar la resolución nc seleccionada
                const selectedResolutionnd = this.nd_resolutions.find(
                    r => r.id === this.form.nd_resolution_id
                );
                // Validar si resolucion nc si está vencida
                if (selectedResolutionnd && selectedResolutionnd.vencida) {
                    this.$message.warning('No puede seleccionar una resolución ND vencida.');
                    this.loading_submit = false;
                    return;
                }
                // Buscar la resolución ni seleccionada
                const selectedResolutionni = this.ni_resolutions.find(
                    r => r.id === this.form.ni_resolution_id
                );
                // Validar si resolucion ni si está vencida
                if (selectedResolutionni && selectedResolutionni.vencida) {
                    this.$message.warning('No puede seleccionar una resolución Nomina vencida.');
                    this.loading_submit = false;
                    return;
                }
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.form.password = null
                            this.form.password_confirmation = null
                            this.form.fe_resolution_id = null
                            this.form.nc_resolution_id = null
                            this.form.nd_resolution_id = null
                            this.form.ni_resolution_id = null
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            this.$message.error(error.response.data.message);
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },

            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>
