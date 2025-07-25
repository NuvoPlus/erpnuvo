@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp
<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <a href="{{route('tenant.dashboard.index')}}"
           class="logo pt-2 pt-md-0">
            @if($vc_company->logo)
                <img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}"
                     alt="Logo"/>
            @else
                <img src="{{asset('logo/tulogo.png')}}"
                     alt="Logo"/>
            @endif
        </a>
        <div class="d-md-none toggle-sidebar-left"
            data-toggle-class="sidebar-left-opened"
            data-target="html"
            data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars"
               aria-label="Toggle sidebar"></i>
        </div>
    </div>
    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    @if(in_array('dashboard', $vc_modules))
                    <li class="{{ ($path[0] === 'dashboard')?'nav-active':'' }}">
                        <a class="nav-link" href="{{ route('tenant.dashboard.index') }}">
                            <span class="material-symbols-outlined">home</span>
                            <span>Inicio</span>
                        </a>
                    </li>
                    @endif

                    @if(in_array('documents', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'documents')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'contacts')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'summaries')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'voided')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'quotations')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'remissions')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'sale-notes')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'contingencies')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'person-types')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'incentives')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'order-notes')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'sale-opportunities')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'contracts')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'production-orders')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'technical-services')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'user-commissions')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'co-documents')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'co-clients')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'co-documents-aiu')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'co-documents-unreferenced-note')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'co-documents-health')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'co-remissions')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <span class="material-symbols-outlined">
                                request_quote
                            </span>
                            <span>Ventas</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            @if(auth()->user()->type != 'integrator' && $vc_company->soap_type_id != '03')
                                @if(in_array('documents', $vc_modules))
                                    @if(in_array('new_document', $vc_module_levels))
                                        <li class="{{ ($path[0] === 'co-documents'  && $path[1] === 'create')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.co-documents.create')}}">
                                                Factura Electronica
                                            </a>
                                        </li>
                                        {{-- <li class="{{ ($path[0] === 'co-documents-contingency-3'  && $path[1] === 'create')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.co-documents-contingency-3.create')}}">
                                                F.E. Contingencia Tipo 3
                                            </a>
                                        </li> --}}

                                        @if(in_array('invoicehealth', $vc_modules))
                                            {{-- <li class="{{ ($path[0] === 'co-documents-health'  && $path[1] === 'create')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.co-documents-health.create')}}">
                                                F.E. Sector Salud
                                                </a>
                                            </li> --}}
                                        @endif

                                        <li class="{{ ($path[0] === 'co-documents-aiu'  && $path[1] === 'create')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.co-documents-aiu.create')}}">
                                                Factura Electronica AIU
                                            </a>
                                        </li>

                                        <li class="{{ ($path[0] === 'co-documents-unreferenced-note'  && $path[1] === 'create')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.co-documents-unreferenced-note.create')}}">
                                                Nota Contable Sin Referencia A Factura Electronica
                                            </a>
                                        </li>

                                        {{-- <li class="{{ ($path[0] === 'documents' && $path[1] === 'create')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.documents.create')}}">
                                                comprobante electrónico
                                            </a>
                                        </li> --}}
                                    @endif
                                @endif
                            @endif

                            @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')

                                @if(in_array('list_document', $vc_module_levels))
                                    {{-- <li class="{{ ($path[0] === 'documents' && $path[1] != 'create' && $path[1] != 'not-sent')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.documents.index')}}">
                                            Listado de comprobantes
                                        </a>
                                    </li> --}}

                                    <li class="{{ ($path[0] === 'co-documents'  && $path[1] != 'create'  )?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.co-documents.index')}}">
                                            Listado de comprobantes
                                        </a>
                                    </li>
                                @endif
                                 @if(in_array('quotations', $vc_module_levels))

                                    <li class="{{ ($path[0] === 'quotations')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.quotations.index')}}">
                                            Cotizaciones
                                        </a>
                                    </li>
                                @endif

                                @if(in_array('remissions', $vc_module_levels))
                                <li class="{{ ($path[0] === 'co-remissions')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.co-remissions.index')}}">
                                        Remisiones
                                    </a>
                                </li>
                                @endif


                            @endif

                            {{-- @if(in_array('documents', $vc_modules) && $vc_company->soap_type_id != '03')

                                @if(in_array('document_not_sent', $vc_module_levels))
                                    <li class="{{ ($path[0] === 'documents' && $path[1] === 'not-sent')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.documents.not_sent')}}">
                                            Comprobantes no enviados
                                        </a>
                                    </li>
                                @endif

                            @endif --}}

                            @if(auth()->user()->type != 'integrator' && in_array('documents', $vc_modules) )

                                {{-- @if(auth()->user()->type != 'integrator' && in_array('document_contingengy', $vc_module_levels) && $vc_company->soap_type_id != '03')
                                <li class="{{ ($path[0] === 'contingencies' )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.contingencies.index')}}">
                                        Documentos de contingencia
                                    </a>
                                </li>
                                @endif --}}

                                @if(in_array('catalogs', $vc_module_levels))

                                    <li class="nav-parent
                                        {{ ($path[0] === 'items')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'co-items')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'co-clients')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'categories')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'brands')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'person-types')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'co-taxes')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'persons' && $path[1] === 'customers')?'nav-active nav-expanded':'' }}
                                        ">
                                        {{-- <a class="nav-link" href="#">
                                            Catálogos
                                        </a> --}}
                                        <ul class="nav nav-children">

                                            {{-- <li class="{{ ($path[0] === 'co-items')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.co-items.index')}}">
                                                    Productos colombia
                                                </a>
                                            </li>

                                            <li class="{{ ($path[0] === 'co-clients')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.co-clients.index')}}">
                                                    Clientes colombia
                                                </a>
                                            </li> --}}

                                            {{-- <li class="{{ ($path[0] === 'co-taxes')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.co-taxes.index')}}">
                                                    Impuestos colombia
                                                </a>
                                            </li> --}}

                                            {{-- <li class="{{ ($path[0] === 'items')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.items.index')}}">
                                                    Productos
                                                </a>
                                            </li> --}}
                                            {{-- <li class="{{ ($path[0] === 'categories')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.categories.index')}}">
                                                    Categorías
                                                </a>
                                            </li> --}}
                                            {{-- <li class="{{ ($path[0] === 'brands')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.brands.index')}}">
                                                    Marcas
                                                </a>
                                            </li> --}}
                                            {{-- <li class="{{ ($path[0] === 'persons' && $path[1] === 'customers')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'customers'])}}">
                                                    Clientes
                                                </a>
                                            </li> --}}
                                            <!-- <li class="{{ ($path[0] === 'person-types')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.person_types.index')}}">
                                                    Tipos de clientes
                                                </a>
                                            </li> -->
                                        </ul>
                                    </li>
                                @endif

                                {{-- @if(in_array('summary_voided', $vc_module_levels) && $vc_company->soap_type_id != '03')

                                    <li class="nav-parent
                                        {{ ($path[0] === 'summaries')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'voided')?'nav-active nav-expanded':'' }}
                                        ">
                                        <a class="nav-link" href="#">
                                            Resúmenes y Anulaciones
                                        </a>
                                        <ul class="nav nav-children">
                                            <li class="{{ ($path[0] === 'summaries')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.summaries.index')}}">
                                                    Resúmenes
                                                </a>
                                            </li>
                                            <li class="{{ ($path[0] === 'voided')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.voided.index')}}">
                                                    Anulaciones
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif --}}

                                {{-- <li class="{{ ($path[0] === 'sale-opportunities')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.sale_opportunities.index')}}">
                                        Oportunidad de venta
                                    </a>
                                </li> --}}

                                {{-- @if(in_array('quotations', $vc_module_levels))

                                    <li class="{{ ($path[0] === 'quotations')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.quotations.index')}}">
                                            Cotizaciones
                                        </a>
                                    </li>
                                @endif
                                @if(in_array('remissions', $vc_module_levels))
                                <li class="{{ ($path[0] === 'co-remissions')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.co-remissions.index')}}">
                                        Remisiones
                                    </a>
                                </li>
                                @endif --}}

                                {{-- <li class="nav-parent
                                    {{ ($path[0] === 'contracts')?'nav-active nav-expanded':'' }}
                                    {{ ($path[0] === 'production-orders')?'nav-active nav-expanded':'' }}
                                    ">
                                    <a class="nav-link" href="#">
                                        Contratos
                                    </a>
                                    <ul class="nav nav-children">
                                        <li class="{{ ($path[0] === 'contracts')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.contracts.index')}}">
                                                Listado
                                            </a>
                                        </li>
                                        <li class="{{ ($path[0] === 'production-orders')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.production_orders.index')}}">
                                                Ordenes de Producción
                                            </a>
                                        </li>
                                    </ul>
                                </li> --}}


                                {{-- <li class="{{ ($path[0] === 'order-notes')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.order_notes.index')}}">
                                        Pedidos
                                    </a>
                                </li> --}}

                                {{--@if(in_array('sale_notes', $vc_module_levels))

                                    <li class="{{ ($path[0] === 'sale-notes')?'nav-active':'' }}">
                                        <a class="nav-link" href="{{route('tenant.sale_notes.index')}}">
                                            Notas de Venta
                                        </a>
                                    </li>
                                @endif --}}

                              {{--  <li class="{{ ($path[0] === 'technical-services')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.technical_services.index')}}">
                                        Servicio de soporte técnico
                                    </a>
                                </li> --}}

                                @if(in_array('incentives', $vc_module_levels))

                                    <li class="nav-parent
                                        {{ ($path[0] === 'incentives')?'nav-active nav-expanded':'' }}
                                        {{ ($path[0] === 'user-commissions')?'nav-active nav-expanded':'' }}
                                        ">
                                        <a class="nav-link" href="#">
                                            Comisiones
                                        </a>
                                        <ul class="nav nav-children">
                                            <li class="{{ ($path[0] === 'user-commissions')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.user_commissions.index')}}">
                                                    Vendedores
                                                </a>
                                            </li>
                                            <li class="{{ ($path[0] === 'incentives')?'nav-active':'' }}">
                                                <a class="nav-link" href="{{route('tenant.incentives.index')}}">
                                                    Productos
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                @endif



                            @endif

                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->type != 'integrator')
                        @if(in_array('pos', $vc_modules))
                        <li class="
                        nav-parent
                        {{ ($path[0] === 'pos' )?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'p.o.s' )?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'cash')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'item-sets')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'item-set')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'document-pos')?'nav-active nav-expanded':'' }}
                        ">
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">point_of_sale</span>
                                <span>Punto de Venta P.O.S.</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="{{ ($path[0] === 'p.o.s'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.pos.index')}}">
                                        Punto de venta
                                    </a>
                                </li>
                                <li class="{{ ($path[0] === 'cash'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.cash.index')}}">
                                        Caja menor
                                    </a>
                                </li>
                                <li class="{{ ($path[0] === 'item-sets'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.item_sets.index')}}">
                                        Conjuntos/Packs/Promociones
                                    </a>
                                </li>
                                <li class="{{ ($path[0] === 'item-set'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.pos.configuration')}}">
                                        Configuración
                                    </a>
                                </li>             
                                <li class="{{ ($path[0] === 'document-pos'  )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.document_pos.index')}}">
                                        Lista Documentos
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    @endif


                    @if(in_array('ecommerce', $vc_modules))
                    <li class="nav-parent {{ in_array($path[0], ['ecommerce','items_ecommerce', 'tags', 'promotions', 'orders', 'configuration'])?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <span class="material-symbols-outlined">storefront</span>
                            <span>Tienda Virtual</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="">
                                <a class="nav-link" onclick="window.open( '{{ route("tenant.ecommerce.index") }} ')">
                                    Ir a Tienda
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'orders')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant_orders_index')}}">
                                    Pedidos
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'items_ecommerce')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.items_ecommerce.index')}}">
                                    Productos Tienda Virtual
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'tags')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.tags.index')}}">
                                    Tags - Categorias
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'promotions')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.promotion.index')}}">
                                    Promociones
                                </a>
                            </li>
                            <li class="{{ ($path[1] === 'configuration')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant_ecommerce_configuration')}}">
                                    Configuración
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif
                     @if(in_array('purchases', $vc_modules))
                        <li class="nav-parent
                            {{ ($path[0] === 'contacts')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'persons')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'customers')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'suppliers')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'co-items')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'co-clients')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'person-types')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'purchase-quotations')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'purchase-quotations')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'ecommerce' && $path[1] === 'suppliers')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'persons' && $path[1] === 'suppliers')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'persons' && $path[1] === 'purchase-quotations')?'nav-active nav-expanded':'' }}
                            ">
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">group</span>
                                <span>Contactos</span>
                            </a>
                            <ul class="nav nav-children">
                                <li class="{{ ($path[0] === 'ecommerce' && $path[1] === 'customers') ? 'nav-active' : '' }}">
                                    <a class="nav-link" href="{{ route('tenant.persons.index', ['type' => 'customers']) }}">
                                        Clientes
                                    </a>
                                </li>
                                {{-- Submenú de proveedores --}}
                                <li class="nav-parent
                                {{ ($path[0] === 'persons')?'nav-active nav-expanded':'' }}
                                {{ ($path[0] === 'purchase-quotations')?'nav-active nav-expanded':'' }}
                                ">
                                    <a class="nav-link" href="#">
                                        Proveedores
                                    </a>
                                    <ul class="nav nav-children">
                                        <li class="{{ ($path[0] === 'persons' && $path[1] === 'suppliers')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{ route('tenant.persons.index', ['type' => 'suppliers']) }}">
                                                Listado
                                            </a>
                                        </li>
                                        <li class="{{ (in_array($path[0], ['persons', 'purchase-quotations']) && $path[1] === '')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{ route('tenant.purchase-quotations.index') }}">
                                                Solicitar cotización
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul> 
                        </li>
                    @endif
                    @if(auth()->user()->type != 'integrator')

                        @if(in_array('purchases', $vc_modules))
                        <li class="
                            nav-parent
                            {{ ($path[0] === 'purchases')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'expenses')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'purchase-orders')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'fixed-asset')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'support-documents')?'nav-active nav-expanded':'' }}
                            {{ ($path[0] === 'support-document-adjust-notes')?'nav-active nav-expanded':'' }}
                            ">
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">shopping_cart</span>
                                <span>Compras</span>
                            </a>
                            <ul class="nav nav-children" style="">



                                <li class="{{ ($path[0] === 'purchases' && $path[1] === 'create')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.purchases.create')}}">
                                        Nueva Compra
                                    </a>
                                </li>

                                <li class="{{ ($path[0] === 'purchases' && $path[1] != 'create')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.purchases.index')}}">
                                        Listado De Compras
                                    </a>
                                </li>

                                <li class="{{ ($path[0] === 'purchase-orders')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.purchase-orders.index')}}">
                                        Ordenes de compra
                                    </a>
                                </li>
                                <li class="{{ ($path[0] === 'expenses' )?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.expenses.index')}}">
                                        Gastos diversos
                                    </a>
                                </li>

                                <li class="nav-parent
                                    {{ ($path[0] === 'persons' && $path[1] === 'suppliers')?'nav-active nav-expanded':'' }}
                                    {{ ($path[0] === 'purchase-quotations')?'nav-active nav-expanded':'' }}
                                    ">
                                    {{-- <a class="nav-link" href="#">
                                        Proveedores
                                    </a> --}}
                                    <ul class="nav nav-children">
                                        {{-- <li class="{{ ($path[0] === 'persons' && $path[1] === 'suppliers')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.persons.index', ['type' => 'suppliers'])}}">
                                                Listado
                                            </a>
                                        </li> --}}
                                    </ul>
                                </li>

                                {{-- documento de soporte --}}

                                <li class="nav-parent
                                    {{ ($path[0] === 'support-documents')?'nav-active nav-expanded':'' }}
                                    {{ ($path[0] === 'support-document-adjust-notes')?'nav-active nav-expanded':'' }}
                                    ">
                                    <a class="nav-link" href="#">
                                        Documentos de soporte (DSNOF)
                                    </a>
                                    <ul class="nav nav-children">

                                        <li class="{{ ($path[0] === 'support-documents' && $path[1] === 'create')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.support-documents.create')}}">
                                                Nuevo Documento
                                            </a>
                                        </li>
                                        <li class="{{ (in_array($path[0], ['support-documents', 'support-document-adjust-notes']) && $path[1] === '')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.support-documents.index')}}">
                                                Listado
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                {{-- documento de soporte --}}


                                {{-- <li class="nav-parent
                                    {{ ($path[0] === 'fixed-asset' )?'nav-active nav-expanded':'' }}
                                    ">
                                    <a class="nav-link" href="#">
                                        Activos fijos
                                    </a>
                                    <ul class="nav nav-children">

                                        <li class="{{ ($path[0] === 'fixed-asset' && $path[1] === 'items')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.fixed_asset_items.index')}}">
                                                Ítems
                                            </a>
                                        </li>
                                        <li class="{{ ($path[0] === 'fixed-asset' && $path[1] === 'purchases')?'nav-active':'' }}">
                                            <a class="nav-link" href="{{route('tenant.fixed_asset_purchases.index')}}">
                                                Compras
                                            </a>
                                        </li>
                                    </ul>
                                </li> --}}
                            </ul>
                        </li>
                        @endif
                        @if(in_array('finance', $vc_modules))
                            <li class="nav-parent {{$path[0] === 'finances' && in_array($path[1], [
                                                        'global-payments', 'balance','payment-method-types', 'unpaid', 'to-pay', 'income'
                                                    ])
                                                    ? 'nav-active nav-expanded' : ''}}">

                                <a class="nav-link" href="#">
                                    <span class="material-symbols-outlined">payments</span>
                                    <span>Finanzas</span>
                                </a>
                                <ul class="nav nav-children" style="">
                                    <li class="{{(($path[0] === 'finances') && ($path[1] == 'income')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.finances.income.index')}}">
                                            Ingresos
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'finances') && ($path[1] == 'global-payments')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.finances.global_payments.index')}}">
                                            Pagos
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'finances') && ($path[1] == 'balance')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.finances.balance.index')}}">
                                            Balance
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'finances') && ($path[1] == 'unpaid')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.finances.unpaid.index')}}">
                                            Cuentas por cobrar
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'finances') && ($path[1] == 'to-pay')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.finances.to_pay.index')}}">
                                            Cuentas por pagar
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'finances') && ($path[1] == 'payment-method-types')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.finances.payment_method_types.index')}}">
                                            Ingresos y Egresos - M. Pago
                                        </a>
                                    </li>
                                    {{-- @if(auth()->user()->type != 'integrator')
                                        <li class="{{($path[0] === 'finances') ? 'nav-active' : ''}}">
                                            <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                                Catálogos
                                            </a>
                                        </li>
                                    @endif --}}
                                </ul>
                            </li>
                        @endif
                        @if(in_array('inventory', $vc_modules))
                        <li class="nav-parent {{ (in_array($path[0], ['items','categories','brands','inventory', 'warehouses', 'moves', 'transfers']) ||
                                                ($path[0] === 'reports' && in_array($path[1], ['kardex', 'inventory', 'valued-kardex'])))?'nav-active nav-expanded':'' }}">
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">inventory</span>
                                <span>Inventario</span>
                            </a>
                            <ul class="nav nav-children" style="">
                                <li class="{{ ($path[0] === 'items')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.items.index')}}">
                                        Productos
                                    </a>
                               </li>
                                <li class="{{ ($path[0] === 'categories')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.categories.index')}}">
                                        Categorías
                                    </a>
                                </li>             
                                <li class="{{ ($path[0] === 'brands')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('tenant.brands.index')}}">
                                        Marcas
                                    </a>
                                </li>    
                                <li class="{{ ($path[0] === 'warehouses')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('warehouses.index')}}">Almacenes</a>
                                </li>
                                <li class="{{ ($path[0] === 'inventory')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('inventory.index')}}">Movimientos</a>
                                </li>
                                <li class="{{ ($path[0] === 'transfers')?'nav-active':'' }}">
                                    <a class="nav-link" href="{{route('transfers.index')}}">Traslados</a>
                                </li>
                                <li class="{{(($path[0] === 'reports') && ($path[1] === 'kardex')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('reports.kardex.index')}}">
                                        Reporte Kardex
                                    </a>
                                </li>
                                <li class="{{(($path[0] === 'reports') && ($path[1] == 'inventory')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('reports.inventory.index')}}">
                                        Reporte Inventario
                                    </a>
                                </li>
                                {{-- <li class="{{(($path[0] === 'reports') && ($path[1] === 'valued-kardex')) ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('reports.valued_kardex.index')}}">
                                        Kardex valorizado
                                    </a>
                                </li> --}}
                            </ul>
                        </li>
                        @endif

                    @endif

                    {{-- @if(in_array('configuration', $vc_modules))
                    <li class="nav-parent {{ in_array($path[0], ['users', 'establishments'])?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <span class="material-symbols-outlined">groups</span>
                            <span>Usuarios/Locales & Series</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'users')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.users.index')}}">
                                    Usuarios
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'establishments')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.establishments.index')}}">
                                    Establecimientos
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif --}}

                    {{-- @if(in_array('advanced', $vc_modules) && $vc_company->soap_type_id != '03')
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'retentions')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'dispatches')?'nav-active nav-expanded':'' }}
                        {{ ($path[0] === 'perceptions')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <i class="fas fa-receipt" aria-hidden="true"></i>
                            <span>Comprobantes avanzados</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{ ($path[0] === 'retentions')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.retentions.index')}}">
                                    Retenciones
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'dispatches')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.dispatches.index')}}">
                                    Guías de remisión
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'perceptions')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.perceptions.index')}}">
                                Percepciones
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif --}}

                    @if(in_array('reports', $vc_modules))
                    <li class="nav-parent {{  ($path[0] === 'reports' && in_array($path[1], ['report-taxes','purchases', 'search','sales','customers','items',
                                        'general-items','consistency-documents', 'quotations', 'sale-notes','cash','commissions','document-hotels',
                                        'validate-documents', 'document-detractions','commercial-analysis', 'order-notes-consolidated', 'document-pos',
                                        'order-notes-general', 'sales-consolidated', 'user-commissions', 'co-remissions', 'co-items-sold', 'co-sales-book'])) ? 'nav-active nav-expanded' : ''}}">

                        <a class="nav-link" href="#">
                            <span class="material-symbols-outlined">monitoring</span>
                            <span>Reportes</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{(($path[0] === 'reports') && ($path[1] === 'purchases')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.purchases.index')}}">
                                    Compras
                                </a>
                            </li>

                            <li class="nav-parent {{  ($path[0] === 'reports' &&
                                    in_array($path[1], ['sales','customers','items','quotations', 'sale-notes', 'document-detractions', 'document-pos',
                                    'commissions',  'general-items','sales-consolidated', 'user-commissions', 'co-remissions'])) ? 'nav-active nav-expanded' : ''}}">

                                <a class="nav-link" href="#">
                                    Ventas
                                </a>
                                <ul class="nav nav-children">
                                    @if($vc_company->soap_type_id != '03')
                                    <li class="{{(($path[0] === 'reports') && ($path[1] === 'sales')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.sales.index')}}">
                                            Consulta de Documentos
                                        </a>
                                    </li>
                                    @endif
                                    <li class="{{(($path[0] === 'reports') && ($path[1] === 'customers')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.customers.index')}}">
                                            Clientes
                                        </a>
                                    </li>

                                    <li class="{{(($path[0] === 'reports') && ($path[1] === 'document-pos')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.document_pos.index')}}">
                                            Documentos POS
                                        </a>
                                    </li>

                                    <li class="{{(($path[0] === 'reports') && ($path[1] === 'items')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.items.index')}}">
                                            Consultar Producto
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'reports') && ($path[1] === 'general-items')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.general_items.index')}}">
                                            Reporte De Productos
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'reports') && ($path[1] == 'quotations')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.quotations.index')}}">
                                            Reporte De Cotizaciones
                                        </a>
                                    </li>
                                    <li class="{{(($path[0] === 'reports') && ($path[1] == 'co-remissions')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.co-remissions.index')}}">
                                            Reporte De Remisiones
                                        </a>
                                    </li>
                                   <!-- <li class="{{(($path[0] === 'reports') && ($path[1] == 'sale-notes')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.sale_notes.index')}}">
                                            Notas de Venta
                                        </a>
                                    </li>-->
                                    {{-- @if($vc_company->soap_type_id != '03')
                                    <li class="{{(($path[0] === 'reports') && ($path[1] == 'document-detractions')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.document_detractions.index')}}">
                                            Detracciones
                                        </a>
                                    </li>
                                    @endif --}}


                                    <li class="nav-parent
                                        {{ (($path[0] === 'reports') && ($path[1] == 'commissions')) ?'nav-active nav-expanded':'' }}
                                        {{ (($path[0] === 'reports') && ($path[1] == 'user-commissions')) ?'nav-active nav-expanded':'' }}
                                        ">
                                        <a class="nav-link" href="#">
                                            Comisiones
                                        </a>
                                        <ul class="nav nav-children">

                                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'user-commissions')) ? 'nav-active' : ''}}">
                                                <a class="nav-link" href="{{route('tenant.reports.user_commissions.index')}}">
                                                    Utilidad ventas
                                                </a>
                                            </li>

                                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'commissions')) ? 'nav-active' : ''}}">
                                                <a class="nav-link" href="{{route('tenant.reports.commissions.index')}}">
                                                    Ventas
                                                </a>
                                            </li>
                                        </ul>
                                    </li>


                                    {{-- <li class="{{(($path[0] === 'reports') && ($path[1] == 'sales-consolidated')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.sales_consolidated.index')}}">
                                            Consolidado de items
                                        </a>
                                    </li> --}}
                                </ul>
                            </li>

                            {{-- <li class="nav-parent {{  ($path[0] === 'reports' &&
                                    in_array($path[1], ['order-notes-consolidated', 'order-notes-general'])) ? 'nav-active nav-expanded' : ''}}">

                                <a class="nav-link" href="#">
                                    Pedidos
                                </a>
                                <ul class="nav nav-children">

                                    <li class="{{(($path[0] === 'reports') && ($path[1] == 'order-notes-general')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.order_notes_general.index')}}">
                                            General
                                        </a>
                                    </li>

                                    <li class="{{(($path[0] === 'reports') && ($path[1] == 'order-notes-consolidated')) ? 'nav-active' : ''}}">
                                        <a class="nav-link" href="{{route('tenant.reports.order_notes_consolidated.index')}}">
                                            Consolidado de items
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}

                            {{-- @if($vc_company->soap_type_id != '03')
                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'consistency-documents')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.consistency-documents.index')}}">Consistencia documentos</a>
                            </li>

                             <li class="{{(($path[0] === 'reports') && ($path[1] == 'validate-documents')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.validate_documents.index')}}">
                                    Validador de documentos
                                </a>
                            </li>
                            @endif --}}
                            @if(in_array('hotel', $vc_business_turns))
                            <li class="{{(($path[0] === 'reports') && ($path[1] == 'document-hotels')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.document_hotels.index')}}">
                                    Giro negocio hoteles
                                </a>
                            </li>
                            @endif
                            <!-- <li class="{{(($path[0] === 'reports') && ($path[1] == 'commercial-analysis')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.commercial_analysis.index')}}">
                                    Análisis comercial
                                </a>
                            </li> -->
                            <li class="{{(($path[0] === 'reports') && ($path[1] === 'report-taxes')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.reports.taxes')}}">
                                    Impuestos
                                </a>
                            </li>

                            <li class="{{(($path[0] === 'reports') && ($path[1] === 'co-items-sold')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.co-items-sold.index')}}">
                                    Artículos vendidos
                                </a>
                            </li>

                            <li class="{{(($path[0] === 'reports') && ($path[1] === 'co-sales-book')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.co-sales-book.index')}}">
                                    Libro de ventas
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif

                    {{-- @if(in_array('accounting', $vc_modules))
                    <li class="
                        nav-parent
                        {{ ($path[0] === 'account')?'nav-active nav-expanded':'' }}
                        ">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-bar" aria-hidden="true"></i>
                            <span>Contabilidad</span>
                        </a>
                        <ul class="nav nav-children" style="">
                            <li class="{{(($path[0] === 'account') && ($path[1] === 'format')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{ route('tenant.account_format.index') }}">
                                    Exportar formatos
                                </a>
                            </li>
                            <li class="{{(($path[0] === 'account') && ($path[1] == ''))   ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{ route('tenant.account.index') }}">
                                    <!-- Exportar SISCONT/CONCAR -->
                                    Exportar formatos - Sis. Contable
                                </a>
                            </li>
                            <li class="{{(($path[0] === 'account') && ($path[1] == 'summary-report'))   ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{ route('tenant.account_summary_report.index') }}">
                                    Reporte resumido - Ventas
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif --}}

                    @if(in_array('payroll', $vc_modules))

                    <li class="nav-parent {{$path[0] === 'payroll' &&
                                            in_array($path[1], [
                                                'workers', 'document-payrolls', 'document-payroll-adjust-notes'
                                            ])
                                            // &&
                                            // in_array($path[2], [
                                            //     'create'
                                            // ])
                                            ? 'nav-active nav-expanded' : ''}}">

                        <a class="nav-link" href="#">
                            <span class="material-symbols-outlined">article_person</span>
                            <span>Nóminas</span>    
                        </a>

                        <ul class="nav nav-children" style="">

                            <li class="{{(($path[0] === 'payroll') && ($path[1] == 'workers')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.payroll.workers.index')}}">
                                    Empleados
                                </a>
                            </li>

                            <li class="{{(($path[0] === 'payroll') && ($path[1] == 'document-payrolls') && $path[2] == 'create') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.payroll.document-payrolls.create')}}">
                                    Nueva nómina
                                </a>
                            </li>

                            <li class="{{(($path[0] === 'payroll') && (in_array($path[1], ['document-payrolls', 'document-payroll-adjust-notes'])) && ($path[2] !== 'create')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.payroll.document-payrolls.index')}}">
                                    Listado de nóminas
                                </a>
                            </li>

                        </ul>

                        {{-- <ul class="nav nav-children" style="">
                            <li class="{{(($path[0] === 'payroll') && ($path[1] == 'type-workers')) ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.payroll.type-workers.index')}}">
                                    Tipos de empleados
                                </a>
                            </li>
                        </ul> --}}
                    </li>
                    @endif


                    @if(in_array('configuration', $vc_modules))
                    <li class="nav-parent {{in_array($path[0], [
                        'co-taxes','establishments','co-configuration-change-ambient', 'co-configuration','users','co-configuration-documents',
                        'companies', 'catalogs', 'advanced', 'tasks', 'inventories','company_accounts','bussiness_turns',
                        'offline-configurations','series-configurations','configurations','co-advanced-configuration'
                    ]) ? 'nav-active nav-expanded' : ''}}">
                        <a class="nav-link" href="#">
                            <span class="material-symbols-outlined">settings_slow_motion</span>
                            <span>Configuración</span>
                        </a>
                        <ul class="nav nav-children">
                             <li class="{{ ($path[0] === 'co-taxes')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.co-taxes.index')}}">
                                    Listado de Impuestos
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'establishments')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.establishments.index')}}">
                                    Establecimientos
                                </a>
                            </li>
                            <li class="{{($path[0] === 'co-configuration-change-ambient') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.configuration.change.ambient')}}">
                                    Ambiente de Operacion
                                </a>
                            </li>
                            <li class="{{($path[0] === 'co-configuration-documents') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.configuration.documents')}}">
                                    Listado De Documentos
                                </a>
                            </li>
                            <li class="{{($path[0] === 'co-configuration') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.configuration')}}">
                                    Datos de la Empresa
                                </a>
                            </li>
                            <li class="{{ ($path[0] === 'users')?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.users.index')}}">
                                    Listado de usuarios
                                </a>
                            </li>
                            {{-- <li class="{{($path[0] === 'companies') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.companies.create')}}">
                                    Empresa
                                </a>
                            </li>
                            <li class="{{($path[0] === 'company_accounts') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.company_accounts.create')}}">
                                    Cuentas contables
                                </a>
                            </li>
                            <li class="{{($path[0] === 'bussiness_turns') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.bussiness_turns.index')}}">
                                    Giro de negocio
                                </a>
                            </li>
                            @if(auth()->user()->type != 'integrator')
                            <li class="{{($path[0] === 'catalogs') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                    Catálogos
                                </a>
                            </li>
                            @endif  --}}
                            <li class="{{($path[0] === 'catalogs') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.catalogs.index')}}">
                                    Listados de atributos
                                </a>
                            </li>
                            <li class="{{($path[0] === 'co-advanced-configuration') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.co-advanced-configuration.index')}}">
                                    Avanzado
                                </a>
                            </li>

                            {{-- <li class="{{($path[0] === 'advanced') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.advanced.index')}}">
                                    Avanzado
                                </a>
                            </li>

                            <li class="{{($path[1] === 'pdf_templates') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.advanced.pdf_templates')}}">
                                    Plantillas PDF
                                </a>
                            </li>
                            @if($vc_company->soap_type_id != '03')
                            <li class="{{($path[0] === 'offline-configurations') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.offline_configurations.index')}}">
                                    Modo offline
                                </a>
                            </li>
                            <li class="{{($path[0] === 'series-configurations') ? 'nav-active' : ''}}">
                                <a class="nav-link" href="{{route('tenant.series_configurations.index')}}">
                                    Numeración de facturación
                                </a>
                            </li>
                            @endif --}}
                            {{-- @if(auth()->user()->type != 'integrator' && $vc_company->soap_type_id != '03')
                            <li class="{{($path[0] === 'tasks') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.tasks.index')}}">Tareas programadas</a>
                            </li>
                            @endif --}}

                            {{-- <li class="{{($path[0] === 'inventories' && $path[1] === 'configuration') ? 'nav-active': ''}}">
                                <a class="nav-link" href="{{route('tenant.inventories.configuration.index')}}">Inventarios</a>
                            </li> --}}
                        </ul>
                    </li>
                    @endif

                    @if(in_array('radian', $vc_modules))
                        <li class="nav-parent {{in_array($path[0], ['co-radian-events', 'co-email-reading']) ? 'nav-active nav-expanded' : ''}}">
                            <a class="nav-link" href="#">
                                <span class="material-symbols-outlined">notification_multiple</span>
                                {{-- <i class="fas fa-calendar-check"></i> --}}
                                <span>Eventos RADIAN</span>
                            </a>
                            <ul class="nav nav-children">

                                <li class="{{($path[0] === 'co-email-reading' && $path[1] == 'process-emails') ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.co-email-reading-process-emails.index')}}">
                                        Procesar correos
                                    </a>
                                </li>

                                <li class="{{($path[0] === 'co-radian-events' && $path[1] == 'reception') ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.co-radian-events-reception.index')}}">
                                        Recepción de documentos
                                    </a>
                                </li>

                                <li class="{{($path[0] === 'co-radian-events' && $path[1] == 'manage') ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.co-radian-events-manage.index')}}">
                                        Gestionar eventos Doc. Procesados
                                    </a>
                                </li>
                                {{-- <li class="{{($path[0] === 'co-radian-events' && $path[1] == 'radian-cufe') ? 'nav-active' : ''}}">
                                    <a class="nav-link" href="{{route('tenant.co-radian-cufe.index')}}">
                                        Gestionar eventos con CUFE
                                    </a>
                                </li> --}}
                            </ul>
                        </li>
                    @endif


                    {{-- @if(in_array('cuenta', $vc_modules))
                    <li class=" nav-parent
                        {{ ($path[0] === 'cuenta')?'nav-active nav-expanded':'' }}">
                        <a class="nav-link" href="#">
                            <i class="fas fa-dollar-sign" aria-hidden="true"></i>
                            <span>Mis Pagos</span>
                        </a>
                        <ul class="nav nav-children">
                            <li class="{{ (($path[0] === 'cuenta') && ($path[1] === 'configuration')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.configuration.index')}}">
                                    Configuracion
                                </a>
                            </li>
                            <li class="{{ (($path[0] === 'cuenta') && ($path[1] === 'payment_index')) ?'nav-active':'' }}">
                                <a class="nav-link" href="{{route('tenant.payment.index')}}">
                                    Lista de Pagos
                                </a>
                            </li>

                        </ul>
                    </li>
                    @endif --}}
                </ul>
            </nav>
        </div>
        <script>
            // Maintain Scroll Position
            if (typeof localStorage !== 'undefined') {
                if (localStorage.getItem('sidebar-left-position') !== null) {
                    var initialPosition = localStorage.getItem('sidebar-left-position'),
                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                    sidebarLeft.scrollTop = initialPosition;
                }
            }
        </script>
    </div>
</aside>
