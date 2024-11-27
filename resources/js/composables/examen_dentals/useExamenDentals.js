import axios from "axios";
import { onMounted, reactive } from "vue";
import { usePage } from "@inertiajs/vue3";

const oExamenDental = reactive({
    id: 0,
    paciente_id: "",
    imagen1: "",
    imagen2: "",
    examen_dental: "",
    resultado: "",
    examen_detalles: [],
    eliminados: [],
    fecha_registro: "",
    // appends
    url_imagen1: "",
    url_imagen2: "",
    _method: "POST",
});

export const useExamenDentals = () => {
    const { flash } = usePage().props;
    const getExamenDentals = async () => {
        try {
            const response = await axios.get(route("examen_dentals.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.examen_dentals;
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

    const getExamenDentalsApi = async (data) => {
        try {
            const response = await axios.get(
                route("examen_dentals.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.examen_dentals;
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
    const saveExamenDental = async (data) => {
        try {
            const response = await axios.post(
                route("examen_dentals.store", data),
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

    const deleteExamenDental = async (id) => {
        try {
            const response = await axios.delete(
                route("examen_dentals.destroy", id),
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

    const setExamenDental = (item = null) => {
        if (item) {
            oExamenDental.id = item.id;
            oExamenDental.paciente_id = item.paciente_id;
            oExamenDental.dolencia_actual = item.dolencia_actual;
            oExamenDental.imagen1 = item.imagen1;
            oExamenDental.imagen2 = item.imagen2;
            oExamenDental.examen_dental = item.examen_dental;
            oExamenDental.resultado = item.resultado;
            oExamenDental.fecha_registro = item.fecha_registro;
            oExamenDental.examen_detalles = item.examen_detalles;
            oExamenDental.eliminados = [];
            // appends
            oExamenDental.url_imagen1 = item.url_imagen1;
            oExamenDental.url_imagen2 = item.url_imagen2;
            oExamenDental._method = "PUT";
            return oExamenDental;
        }
        return false;
    };

    const limpiarExamenDental = () => {
        oExamenDental.id = 0;
        oExamenDental.paciente_id = "";
        oExamenDental.dolencia_actual = "";
        oExamenDental.imagen1 = "";
        oExamenDental.imagen2 = "";
        oExamenDental.examen_dental = "";
        oExamenDental.resultado = "";
        oExamenDental.fecha_registro = "";
        oExamenDental.examen_detalles = [];
        oExamenDental.eliminados = [];
        // appends
        oExamenDental.url_imagen1 = "";
        oExamenDental.url_imagen2 = "";
        oExamenDental._method = "POST";
    };

    onMounted(() => {});

    return {
        oExamenDental,
        getExamenDentals,
        getExamenDentalsApi,
        saveExamenDental,
        deleteExamenDental,
        setExamenDental,
        limpiarExamenDental,
    };
};
