<script setup>
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { usePacientes } from "@/composables/pacientes/usePacientes";
import { ref, onMounted, onBeforeUnmount } from "vue";
import PanelToolbar from "@/Components/PanelToolbar.vue";
import axios from "axios";
const { url_assets } = usePage().props;
const { getPacientes } = usePacientes();
const loading = ref(false);

const form = ref({
    paciente_id: "",
    examen_dental_id: "",
});
const listPacientes = ref([]);
const listExamenDentals = ref([]);
const listEstados = ref(["PENDIENTE", "EN PROCESO", "FINALIZADO"]);

const cargarPacientes = async () => {
    listPacientes.value = await getPacientes();
};

const cargarExamenDentals = (e) => {
    let value = e.target.value;
    if (value != "") {
        axios.get(route("examen_dentals.paciente", value)).then((response) => {
            listExamenDentals.value = response.data.examen_dentals;
        });
    } else {
        listExamenDentals.value = [];
    }
};

const oExamenDental = ref(null);
const cargaInfoExamenDental = (e) => {
    let value = e.target.value;
    if (value != "") {
        axios.get(route("examen_dentals.show", value)).then((response) => {
            oExamenDental.value = response.data;
        });
    } else {
        oExamenDental.value = null;
    }
};

const actualizaSeguimiento = (e, id, col) => {
    const value = e.target.value;
    axios
        .post(route("seguimientos.update", id), {
            col,
            value,
            _method: "put",
        })
        .then((response) => {
            $.gritter.add({
                title: "Registro correcto",
                text: "",
                image: url_assets + "imgs/check.png",
                sticky: false,
                time: 1000,
                class_name: "my-sticky-class",
            });
        });
};

const intervalKeyup = ref(null);

const detectaKeyupInfo = (e, id, col) => {
    clearInterval(intervalKeyup.value);
    intervalKeyup.value = setTimeout(() => {
        actualizaSeguimiento(e, id, col);
    }, 500);
};

onMounted(async () => {
    cargarPacientes();
});
onBeforeUnmount(() => {});
</script>
<template>
    <Head title="Seguimiento"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Seguimiento</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Seguimiento</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title btn-nuevo"></h4>
                    <panel-toolbar :mostrar_loading="loading" />
                </div>
                <!-- END panel-heading -->
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Seleccionar paciente</label>
                            <select
                                class="form-select"
                                v-model="form.paciente_id"
                                @change="cargarExamenDentals($event)"
                            >
                                <option value="">- Seleccione -</option>
                                <option
                                    v-for="item in listPacientes"
                                    :value="item.id"
                                >
                                    {{ item.full_name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Seleccionar examen</label>
                            <select
                                class="form-select"
                                v-model="form.examen_dental_id"
                                @change="cargaInfoExamenDental($event)"
                            >
                                <option value="">- Seleccione -</option>
                                <option
                                    v-for="item in listExamenDentals"
                                    :value="item.id"
                                >
                                    {{ item.codigo }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3" v-if="oExamenDental">
                        <div class="col-12">
                            <h4 class="w-100">DETALLE DEL EXAMEN DENTAL</h4>
                        </div>
                        <div class="col-12" style="overflow: auto">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-light">
                                        <th colspan="5">
                                            <strong>Código examen: </strong
                                            >{{ oExamenDental.codigo }}
                                        </th>
                                    </tr>
                                    <tr class="bg-light">
                                        <th colspan="5">
                                            <strong>Dolencia: </strong
                                            >{{ oExamenDental.dolencia_actual }}
                                        </th>
                                    </tr>
                                    <tr class="bg-light">
                                        <th colspan="5">
                                            <strong>Resultado: </strong
                                            >{{ oExamenDental.resultado }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>PIEZA</th>
                                        <th>DIAGNOSTICO</th>
                                        <th>OBSERVACIONES</th>
                                        <th>ESTADO</th>
                                        <th>OBSERVACIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in oExamenDental.examen_detalles"
                                    >
                                        <td>{{ item.pieza }}</td>
                                        <td>{{ item.diagnostico }}</td>
                                        <td>{{ item.observaciones }}</td>
                                        <td style="min-width: 140px">
                                            <select
                                                class="form-select"
                                                v-model="
                                                    item.seguimiento.estado
                                                "
                                                @change="
                                                    actualizaSeguimiento(
                                                        $event,
                                                        item.seguimiento.id,
                                                        'estado'
                                                    )
                                                "
                                            >
                                                <option
                                                    v-for="item_estado in listEstados"
                                                    :value="item_estado"
                                                >
                                                    {{ item_estado }}
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <textarea
                                                type="text"
                                                class="form-control"
                                                v-model="
                                                    item.seguimiento.observacion
                                                "
                                                rows="1"
                                                @keyup="
                                                    detectaKeyupInfo(
                                                        $event,
                                                        item.seguimiento.id,
                                                        'observacion'
                                                    )
                                                "
                                            ></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>
</template>
