<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, router } from "@inertiajs/vue3";
import { useExamenDentals } from "@/composables/examen_dentals/useExamenDentals";
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { deleteExamenDental } = useExamenDentals();

const columns = [
    {
        title: "",
        data: "codigo",
    },
    {
        title: "PACIENTE",
        data: "paciente.full_name",
    },
    {
        title: "DOLENCIA",
        data: "dolencia_actual",
    },
    {
        title: "RESULTADO",
        data: "resultado",
    },
    {
        title: "FECHA DE REGISTRO",
        data: "fecha_registro_t",
    },
    {
        title: "ACCIONES",
        data: null,
        render: function (data, type, row) {
            return `
                <button class="mx-0 rounded-0 btn btn-primary imprimir" data-id="${
                    row.id
                }"><i class="fa fa-file-pdf"></i></button>
                <button class="mx-0 rounded-0 btn btn-warning editar" data-id="${
                    row.id
                }"><i class="fa fa-edit"></i></button>
                <button class="mx-0 rounded-0 btn btn-danger eliminar"
                 data-id="${row.id}" 
                 data-nombre="${row.cod} | ${row.tipo}" 
                 data-url="${route(
                     "examen_dentals.destroy",
                     row.id
                 )}"><i class="fa fa-trash"></i></button>
            `;
        },
    },
];
const loading = ref(false);

const accionesRow = () => {
    // imprimir
    $("#table-examen_dental").on("click", "button.imprimir", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        const url = route("reportes.r_examen_dentals", {
            examen_dental_id: id,
        });
        window.open(url, "_blank");
    });
    // editar
    $("#table-examen_dental").on("click", "button.editar", function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        router.get(route("examen_dentals.edit", id));
    });
    // eliminar
    $("#table-examen_dental").on("click", "button.eliminar", function (e) {
        e.preventDefault();
        let nombre = $(this).attr("data-nombre");
        let id = $(this).attr("data-id");
        Swal.fire({
            title: "¿Quierés eliminar este registro?",
            html: `<strong>${nombre}</strong>`,
            showCancelButton: true,
            confirmButtonColor: "#B61431",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "No, cancelar",
            denyButtonText: `No, cancelar`,
        }).then(async (result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                let respuesta = await deleteExamenDental(id);
                if (respuesta && respuesta.sw) {
                    updateDatatable();
                }
            }
        });
    });
};

var datatable = null;
const datatableInitialized = ref(false);
const updateDatatable = () => {
    datatable.ajax.reload();
};

onMounted(async () => {
    datatable = initDataTable(
        "#table-examen_dental",
        columns,
        route("examen_dentals.api")
    );
    datatableInitialized.value = true;
    accionesRow();
});
onBeforeUnmount(() => {
    if (datatable) {
        datatable.clear();
        datatable.destroy(false); // Destruye la instancia del DataTable
        datatable = null;
        datatableInitialized.value = false;
    }
});
</script>
<template>
    <Head title="Examen Dental"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Examen Dental</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Examen Dental</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title btn-nuevo">
                        <Link
                            :href="route('examen_dentals.create')"
                            class="btn btn-primary d-inline-block"
                        >
                            <i class="fa fa-plus"></i> Nuevo
                        </Link>
                    </h4>
                    <panel-toolbar
                        :mostrar_loading="loading"
                        @loading="updateDatatable"
                    />
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <table
                        id="table-examen_dental"
                        width="100%"
                        class="table table-striped table-bordered align-middle text-nowrap tabla_datos"
                    >
                        <thead>
                            <tr>
                                <th width="2%"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th width="2%"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>
</template>
