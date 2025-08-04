<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Consulta de documentos por producto</h3>
        </div>
        <div class="card mb-0">
                <div class="card-body">
                    <data-table :resource="resource">
                        <tr slot="heading">
                            <th class="">#</th>
                            <th class="">Fecha</th>
                            <th class="">Tipo Documento</th>
                            <th class="">Prefijo</th>
                            <th class="">Número</th>
                            <th class="">N° Documento</th>
                            <th class="">Cliente</th>
                            <th class="">Cantidad</th>
                            <th class="">Monto</th>
                        </tr>
                        <tr slot-scope="{ index, row }">
                            <td>{{ index }}</td> 
                            <td>{{row.date_of_issue}}</td>
                            <td>{{row.document_type_description}}</td>
                            <td>{{row.series}}</td>
                            <td>{{row.alone_number}}</td>
                            <td>{{row.customer_number}}</td>
                            <td>{{row.customer_name}}</td>
                            <td>{{row.quantity}}</td>
                            <td class="text-right">{{ getFormatDecimal(row.total) }}</td>
                        </tr>
                        
                    </data-table>
                     
                    
                </div> 
        </div>
 
    </div>
</template>

<script>
 
    import DataTable from '../../components/DataTableItems.vue'

    export default { 
        components: {DataTable},
        data() {
            return {
                resource: 'reports/items',                 
                form: {}, 

            }
        },
        async created() { 
        },
        methods: { 
            getFormatDecimal(value) {
                // Convierte la cadena a un número (si es posible)
                const numericPrice = parseFloat(value);
                if (isNaN(numericPrice)) {
                    // En caso de que la conversión no sea exitosa, maneja el error como desees
                    console.error('No se pudo convertir la cadena a un número.');
                    return value;
                }
                // Asumiendo que numericPrice es un número
                const formattedPrice = numericPrice.toLocaleString('en-US', {
                    style: 'decimal',  // Estilo 'decimal' para separadores de mil y dos decimales
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                return formattedPrice;
            },    
            
        }
    }
</script>