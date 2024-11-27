import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oEntrenamiento = reactive({
    id: 0,
    tipo: "",
    entrenamiento_imagens: [],
    eliminados: [],
    _method: "POST",
});

export const useEntrenamientos = () => {
    const { flash } = usePage().props;
    const getEntrenamientos = async () => {
        try {
            const response = await axios.get(route("entrenamientos.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.entrenamientos;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const getEntrenamientosApi = async (data) => {
        try {
            const response = await axios.get(
                route("entrenamientos.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.entrenamientos;
        } catch (err) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
                        : err.response?.data
                        ? err.response?.data?.message
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            throw err; // Puedes manejar el error según tus necesidades
        }
    };
    const saveEntrenamiento = async (data) => {
        try {
            const response = await axios.post(
                route("entrenamientos.store", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const deleteEntrenamiento = async (id) => {
        try {
            const response = await axios.delete(
                route("entrenamientos.destroy", id),
                {
                    headers: { Accept: "application/json" },
                }
            );
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            return response.data;
        } catch (err) {
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
            console.error("Error:", err);
            throw err; // Puedes manejar el error según tus necesidades
        }
    };

    const setEntrenamiento = (item = null, archivos = false) => {
        if (item) {
            oEntrenamiento.id = item.id;
            oEntrenamiento.tipo = item.tipo;
            oEntrenamiento.eliminados = [];
            if (archivos) {
                oEntrenamiento.entrenamiento_imagens = [
                    ...item.entrenamiento_imagens,
                ];
            }
            oEntrenamiento._method = "PUT";
            return oEntrenamiento;
        }
        return false;
    };

    const limpiarEntrenamiento = () => {
        oEntrenamiento.id = 0;
        oEntrenamiento.tipo = "";
        oEntrenamiento.entrenamiento_imagens = [];
        oEntrenamiento.eliminados = [];
        oEntrenamiento._method = "POST";
    };

    onMounted(() => {});

    return {
        oEntrenamiento,
        getEntrenamientos,
        getEntrenamientosApi,
        saveEntrenamiento,
        deleteEntrenamiento,
        setEntrenamiento,
        limpiarEntrenamiento,
    };
};
