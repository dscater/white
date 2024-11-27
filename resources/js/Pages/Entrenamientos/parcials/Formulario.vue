<script setup>
import { useForm, usePage, Link } from "@inertiajs/vue3";
import { useEntrenamientos } from "@/composables/entrenamientos/useEntrenamientos";
import { usePacientes } from "@/composables/pacientes/usePacientes";
import { ref, onMounted, computed } from "vue";
import MiDropZone from "@/Components/MiDropZone.vue";

const propsPage = defineProps({
    detalle: {
        type: Boolean,
        default: false,
    },
});
const { oEntrenamiento, limpiarEntrenamiento } = useEntrenamientos();
let form = useForm(oEntrenamiento);

const { flash, auth } = usePage().props;
const user = ref(auth.user);

const listTipos = ref([]);

const tituloDialog = computed(() => {
    return oEntrenamiento.id == 0
        ? `Agregar Entrenamiento de Imágenes`
        : `Editar Entrenamiento de Imágenes`;
});

const disabled = ref(false);
const loading = ref(false);
const maximo_imagenes = ref(10);

const enviarFormulario = () => {
    loading.value = true;
    let url =
        form["_method"] == "POST"
            ? route("entrenamientos.store")
            : route("entrenamientos.update", form.id);

    for (let i = 0; i < form.entrenamiento_imagens.length; i++) {
        const file = form["entrenamiento_imagens"][i];
        if (file instanceof File) {
            console.log("archivo");
        } else {
            console.log("no  archivo");
            form["entrenamiento_imagens"].splice(i, 1);
        }
    }

    setTimeout(() => {
        form.post(url, {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                loading.value = false;
                Swal.fire({
                    icon: "success",
                    title: "Correcto",
                    text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: `Aceptar`,
                });
                limpiarEntrenamiento();
            },
            onError: (err) => {
                loading.value = false;

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
    }, 500);
};

const cargarListas = (data) => {
    getTiposDiagnosticos(data);
};

const getTiposDiagnosticos = (data) => {
    axios
        .get(route("entrenamientos.getTiposDiagnosticos", data), {
            params: data,
        })
        .then((response) => {
            listTipos.value = response.data;
        });
};

const detectaArchivos = (files) => {
    disabled.value = true;
    form.entrenamiento_imagens = files;
    // for (let i = 0; i < files.length; i++) {
    //     const file = files[i];
    //     form.entrenamiento_imagens.push(file.file);
    // }
    setTimeout(() => {
        disabled.value = false;
    }, 500);
};

const detectaEliminados = (files) => {
    disabled.value = true;
    form.eliminados = files;
    // form.eliminados = [];
    // for (let i = 0; i < files.length; i++) {
    //     const id = files[i];
    //     form.eliminados.push(id);
    // }
    setTimeout(() => {
        disabled.value = false;
    }, 500);
};

const getMaximoImagens = () => {
    axios.get(route("entrenamientos.getMaximoImagenes")).then((response) => {
        maximo_imagenes.value = response.data;
    });
};
onMounted(() => {
    if (form.id && form.id != "") {
        cargarListas({
            tipo: form.tipo,
        });
    } else {
        cargarListas();
    }
    getMaximoImagens();
});
</script>

<template>
    <div class="row">
        <div class="col-12">
            <form @submit.prevent="enviarFormulario">
                <div class="row contenedor_entrenamiento">
                    <div class="loading" v-if="loading">
                        <p class="text-body-1 text-white">Entrenando...</p>
                        <div class="loader"></div>
                        <p class="text-caption text-white">¡Espere porfavor!</p>
                    </div>
                    <div class="col-12">
                        <select class="form-select" v-model="form.tipo">
                            <option value="">- Seleccione -</option>
                            <option v-for="item in listTipos" :value="item">
                                {{ item }}
                            </option>
                        </select>
                        <p
                            class="text-danger text-center"
                            v-if="form.errors?.tipo"
                        >
                            {{ form.errors?.tipo }}
                        </p>
                    </div>
                    <div class="col-12">
                        <h4 class="mt-3 w-100 text-center">Cargar imágenes</h4>
                        <span v-if="form.entrenamiento_imagens.length > 0"
                            >{{ form.entrenamiento_imagens.length }} imagénes
                            cargadas</span
                        >
                    </div>
                    <div
                        class="col-12"
                        :style="[
                            loading ? 'max-height:180px;overflow:hidden;' : '',
                        ]"
                    >
                        <MiDropZone
                            :files="form.entrenamiento_imagens"
                            @UpdateFiles="detectaArchivos"
                            @addEliminados="detectaEliminados"
                        ></MiDropZone>
                        <p
                            class="text-danger text-center"
                            v-if="form.errors?.entrenamiento_imagens"
                        >
                            {{ form.errors?.entrenamiento_imagens }}
                        </p>
                    </div>
                    <div class="col-md-4 mt-3">
                        <button class="btn btn-primary" :disabled="disabled">
                            <i
                                class="fa"
                                :class="[
                                    oEntrenamiento.id != 0
                                        ? 'fa-sync'
                                        : 'fa-save',
                                ]"
                            ></i>
                            <span
                                v-text="
                                    oEntrenamiento.id != 0
                                        ? 'Actualizar Entrenamiento de Imágenes'
                                        : 'Guardar Entrenamiento de Imágenes'
                                "
                            ></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<style scoped>
.contenedor_entrenamiento {
    position: relative;
}

.contenedor_entrenamiento .loading {
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

.contenedor_entrenamiento .loader {
    width: 64px;
    height: 64px;
    position: relative;
    background: #f4f4f4;
    border-radius: 4px;
    overflow: hidden;
}

.contenedor_entrenamiento .loader:before {
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

.contenedor_entrenamiento .loader:after {
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
