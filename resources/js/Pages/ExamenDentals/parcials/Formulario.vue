<script setup>
import { useForm, usePage, Link } from "@inertiajs/vue3";
import { useExamenDentals } from "@/composables/examen_dentals/useExamenDentals";
import { usePacientes } from "@/composables/pacientes/usePacientes";
import { ref, onMounted, computed } from "vue";
import MiDropZone from "@/Components/MiDropZone.vue";
import axios from "axios";

const propsPage = defineProps({
    detalle: {
        type: Boolean,
        default: false,
    },
});
const { oExamenDental, limpiarExamenDental } = useExamenDentals();
let form = useForm(oExamenDental);

const { flash, auth } = usePage().props;
const user = ref(auth.user);
const { getPacientes } = usePacientes();

const listPacientes = ref([]);

const tituloDialog = computed(() => {
    return oExamenDental.id == 0
        ? `Agregar Diagnóstico por Imágen`
        : `Editar Diagnóstico por Imágen`;
});

let disabled = ref(false);

const listPiezas = ref([
    11, 12, 13, 14, 15, 16, 17, 18, 21, 22, 23, 24, 25, 26, 27, 31, 32, 33, 34,
    35, 36, 37, 38, 41, 42, 43, 44, 45, 46, 47, 48, 51, 52, 53, 54, 55, 61, 62,
    63, 64, 65, 71, 72, 73, 74, 75, 81, 82, 83, 84, 85,
]);

const enviarFormulario = () => {
    disabled.value = true;
    let url =
        form["_method"] == "POST"
            ? route("examen_dentals.store")
            : route("examen_dentals.update", form.id);

    form.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            limpiarExamenDental();
            disabled.value = false;
        },
        onError: (err) => {
            disabled.value = false;
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.error
                        ? err.error
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        },
    });
};

const cargarListas = async (
    paciente_id = "",
    sin_examen_dental = false,
    con_historial = false
) => {
    listPacientes.value = await getPacientes({
        order: "desc",
        id: paciente_id,
        sin_examen_dental,
        con_historial,
    });
};

const imagen_cargada = ref(false);
const i_imagen = ref(null);
function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
    form.url_imagen1 = URL.createObjectURL(form[key]);
    imagen_cargada.value = true;
    e.target.value = null;
}

const muestra_resultado = ref(false);
const loading = ref(false);

const generarExamenDental = async () => {
    loading.value = true;
    muestra_resultado.value = false;

    var formData = new FormData();
    formData.append("imagen", form["imagen1"]);

    try {
        const response = await axios.post(
            route("examen_dentals.procesarImagen"),
            formData,
            {
                headers: { "Content-type": "multipart/form-data" },
            }
        );
        if (response) {
            form["url_imagen2"] = response.data.url_imagen2;
            form["imagen2"] = response.data.n;
            form["resultado"] = response.data.resultado;
            for (let i = 1; i <= response.data.total_marcas; i++) {
                agregarDetalle();
            }
            setTimeout(() => {
                loading.value = false;
                muestra_resultado.value = true;
            }, 1500);
        }
    } catch (err) {
        muestra_resultado.value = false;
        loading.value = false;
        console.log(err);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: `${
                err.response ? err.response.data?.message : "Ocurrió un error"
            }`,
            confirmButtonColor: "#3085d6",
            confirmButtonText: `Aceptar`,
        });
    }
};

const agregarDetalle = () => {
    form.examen_detalles.push({
        id: 0,
        examen_dental_id: 0,
        pieza: "11",
        diagnostico: "",
        observaciones: "",
    });
};

const eliminarDetalle = (index) => {
    let id = form.examen_detalles[index].id;
    if (id != 0) {
        form.eliminados.push(id);
    }
    form.examen_detalles.splice(index, 1);
};

onMounted(() => {
    if (form.id && form.id != "") {
        muestra_resultado.value = true;
        cargarListas(form.paciente_id, true, true);
    } else {
        cargarListas();
    }
});
</script>

<template>
    <div class="row">
        <div class="col-12">
            <form @submit.prevent="enviarFormulario">
                <div class="row contenedor_diagnostico">
                    <div class="loading" v-if="loading">
                        <p class="text-body-1 text-white">
                            Analizando imagen...
                        </p>
                        <div class="loader"></div>
                        <p class="text-caption text-white">¡Espere porfavor!</p>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label>Seleccionar paciente*</label>
                                        <select
                                            class="form-select"
                                            v-model="form.paciente_id"
                                        >
                                            <option value=""></option>
                                            <option
                                                v-for="item in listPacientes"
                                                :value="item.id"
                                            >
                                                {{ item.full_name }}
                                            </option>
                                        </select>
                                        <p
                                            class="text-danger text-center"
                                            v-if="form.errors?.paciente_id"
                                        >
                                            {{ form.errors?.paciente_id }}
                                        </p>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label>Dolencia actual*</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="form.dolencia_actual"
                                        />
                                        <p
                                            class="text-danger text-center"
                                            v-if="form.errors?.dolencia_actual"
                                        >
                                            {{ form.errors?.dolencia_actual }}
                                        </p>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <label>Cargar imagen*</label>
                                        <input
                                            type="file"
                                            ref="i_imagen"
                                            @change="
                                                cargaArchivo($event, 'imagen1')
                                            "
                                        />
                                    </div>
                                    <div
                                        class="col-12 text-center"
                                        v-if="imagen_cargada"
                                    >
                                        <img
                                            :src="form.url_imagen1"
                                            alt="Imagen"
                                            style="
                                                max-width: 500px;
                                                width: 100%;
                                            "
                                        />
                                    </div>
                                    <div class="col-12 text-center" v-else>
                                        NO SE SELECCIONÓ NINGUNA IMAGEN
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-lg w-100"
                            @click.prevent="generarExamenDental"
                        >
                            GENERAR RESULTADO
                        </button>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div
                                        class="col-12 text-center"
                                        v-if="form.url_imagen2"
                                    >
                                        <img
                                            :src="form.url_imagen2"
                                            alt="Imagen"
                                            style="
                                                max-width: 500px;
                                                width: 100%;
                                            "
                                        />
                                    </div>
                                    <div class="col-12 text-center" v-else>
                                        RESULTADO DEL DIAGNOSTICO
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="row"
                    v-if="(form.resultado && muestra_resultado) || form.id != 0"
                >
                    <div class="col-12 text-center mt-3">
                        <h4 class="mb-0 text-info font-weight-bold">
                            RESULTADO
                        </h4>
                        <div
                            class="alert alert-info"
                            v-show="muestra_resultado"
                        >
                            <p class="m-0">
                                {{ form.resultado }}
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead class="bg-principal">
                                <tr>
                                    <th class="text-white">PIEZA</th>
                                    <th class="text-white">DIAGNOSTICO</th>
                                    <th class="text-white">OBSERVACIONES</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        item, index
                                    ) in form.examen_detalles"
                                >
                                    <td class="p-0">
                                        <select
                                            class="form-select rounded-0"
                                            v-model="item.pieza"
                                        >
                                            <option
                                                v-for="itempieza in listPiezas"
                                                :value="itempieza"
                                            >
                                                {{ itempieza }}
                                            </option>
                                        </select>
                                    </td>
                                    <td class="p-0">
                                        <input
                                            type="text"
                                            class="form-control rounded-0"
                                            v-model="item.diagnostico"
                                        />
                                    </td>
                                    <td class="p-0">
                                        <input
                                            type="text"
                                            class="form-control rounded-0"
                                            v-model="item.observaciones"
                                        />
                                    </td>
                                    <td class="p-0">
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            @click="eliminarDetalle(index)"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="p-0">
                                        <button
                                            type="button"
                                            class="btn btn-outline-info w-100 rounded-0"
                                            @click="agregarDetalle"
                                        >
                                            Agregar fila
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p
                            class="text-danger text-center"
                            v-if="form.errors?.examen_detalles"
                        >
                            {{ form.errors?.examen_detalles }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <button
                            class="btn btn-primary"
                            @click="enviarFormulario"
                            :disabled="disabled"
                        >
                            {{
                                form.id != 0
                                    ? "ACTUALIZAR REGISTRO"
                                    : "GUARDAR RESULTADO"
                            }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<style scoped>
.contenedor_diagnostico {
    position: relative;
}

.contenedor_diagnostico .loading {
    position: absolute;
    display: flex;
    gap: 10px;
    width: 100%;
    height: 100%;
    background-color: var(--principal);
    z-index: 400;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.contenedor_diagnostico .loader {
    width: 64px;
    height: 64px;
    position: relative;
    background: #f4f4f4;
    border-radius: 4px;
    overflow: hidden;
}

.contenedor_diagnostico .loader:before {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 40px;
    transform: rotate(45deg) translate(30%, 40%);
    background: #2e86de;
    box-shadow: 32px -34px 0 5px #0097e6;
    animation: slide 2s infinite ease-in-out alternate;
}

.contenedor_diagnostico .loader:after {
    content: "";
    position: absolute;
    left: 10px;
    top: 10px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #0097e6;
    transform: rotate(0deg);
    transform-origin: 35px 145px;
    animation: rotate 2s infinite ease-in-out;
}

@keyframes slide {
    0%,
    100% {
        bottom: -35px;
    }

    25%,
    75% {
        bottom: -2px;
    }

    20%,
    80% {
        bottom: 2px;
    }
}

@keyframes rotate {
    0% {
        transform: rotate(-15deg);
    }

    25%,
    75% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(25deg);
    }
}
</style>
