<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link } from "@inertiajs/vue3";
import { useExamenDentals } from "@/composables/examen_dentals/useExamenDentals";
import { ref, onMounted, onBeforeUnmount } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
import Formulario from "./parcials/Formulario.vue";
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { oExamenDental, limpiarExamenDental } = useExamenDentals();
limpiarExamenDental();
const loading = ref(false);

onMounted(async () => {});
onBeforeUnmount(() => {});
</script>
<template>
    <Head title="Exámen Dental"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item">
            <Link :href="route('examen_dentals.index')">Exámen Dental</Link>
        </li>
        <li class="breadcrumb-item active">Nuevo</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Exámen Dental > nuevo</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title btn-nuevo">
                        <Link
                            :href="route('examen_dentals.index')"
                            class="btn btn-outline-default d-inline-block"
                        >
                            <i class="fa fa-arrow-left"></i> Volver
                        </Link>
                    </h4>
                    <panel-toolbar :mostrar_loading="loading" />
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <Formulario></Formulario>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>
</template>
