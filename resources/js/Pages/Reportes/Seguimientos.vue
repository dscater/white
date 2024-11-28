<script setup>
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { useUsuarios } from "@/composables/usuarios/useUsuarios";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
exporting(Highcharts);
Highcharts.setOptions({
    lang: {
        downloadPNG: "Descargar PNG",
        downloadJPEG: "Descargar JPEG",
        downloadPDF: "Descargar PDF",
        downloadSVG: "Descargar SVG",
        printChart: "Imprimir gráfico",
        contextButtonTitle: "Menú de exportación",
        viewFullscreen: "Pantalla completa",
        exitFullscreen: "Salir de pantalla completa",
    },
});
const { getUsuarios } = useUsuarios();
const { setLoading } = useApp();

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const obtenerFechaActual = () => {
    const fecha = new Date();
    const anio = fecha.getFullYear();
    const mes = String(fecha.getMonth() + 1).padStart(2, "0"); // Mes empieza desde 0
    const dia = String(fecha.getDate()).padStart(2, "0"); // Día del mes

    return `${anio}-${mes}-${dia}`;
};

const form = ref({
    fecha_ini: obtenerFechaActual(),
    fecha_fin: obtenerFechaActual(),
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Reporte...";
    }
    return "Generar Reporte";
});

const listUsuarios = ref([]);

const generarReporte = () => {
    generando.value = true;
    axios
        .get(route("reportes.r_seguimientos"), { params: form.value })
        .then((response) => {
            // Create the chart
            Highcharts.chart("container", {
                chart: {
                    type: "column",
                },
                title: {
                    align: "center",
                    text: "Cantidad de Pacientes por Doctores",
                },
                subtitle: {
                    align: "left",
                    text: "",
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                    },
                },
                xAxis: {
                    type: "category",
                },
                yAxis: {
                    title: {
                        text: "Total",
                    },
                },
                legend: {
                    enabled: false,
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: "{point.y:.0f}",
                        },
                    },
                },

                tooltip: {
                    headerFormat:
                        '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat:
                        '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>',
                },

                series: [
                    {
                        name: "Volumen",
                        colorByPoint: true,
                        data: response.data.data,
                    },
                ],
            });
            generando.value = false;
        })
        .catch((error) => {
            console.log(error);
            generando.value = false;
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `Ocurrió un error inesperado, intente nuevamente por favor`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        });
};

onMounted(async () => {
    listUsuarios.value = await getUsuarios();
});
</script>
<template>
    <Head title="Reporte Seguimiento"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">
            Reportes > Seguimiento
        </li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Seguimiento</h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarReporte">
                        <div class="row">
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        Fecha inicio
                                        <input
                                            type="date"
                                            class="form-control"
                                            v-model="form.fecha_ini"
                                        />
                                    </div>
                                    <div class="col-md-6">
                                        Fecha fin
                                        <input
                                            type="date"
                                            class="form-control"
                                            v-model="form.fecha_fin"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary"
                                    block
                                    @click="generarReporte"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3" id="container"></div>
    </div>
</template>
