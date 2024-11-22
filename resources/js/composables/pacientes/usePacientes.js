import axios from "axios";
import { onMounted, ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const oPaciente = ref({
    id: 0,
    nombre: "",
    paterno: "",
    materno: "",
    ci: "",
    ci_exp: "",
    fecha_nac: "",
    sexo: "",
    estado_civil: "",
    nacionalidad: "",
    lugar_nac: "",
    ocupacion: "",
    dir: "",
    fono: "",
    correo: "",
    nom_familiar: "",
    fono_familiar: "",
    foto: "",
    _method: "POST",
});

export const usePacientes = () => {
    const { flash } = usePage().props;
    const getPacientes = async () => {
        try {
            const response = await axios.get(route("pacientes.listado"), {
                headers: { Accept: "application/json" },
            });
            return response.data.pacientes;
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

    const getPacientesByTipo = async (data) => {
        try {
            const response = await axios.get(route("pacientes.byTipo"), {
                headers: { Accept: "application/json" },
                params: data,
            });
            return response.data.pacientes;
        } catch (error) {
            console.error("Error:", error);
            throw error; // Puedes manejar el error según tus necesidades
        }
    };

    const getPacientesApi = async (data) => {
        try {
            const response = await axios.get(
                route("pacientes.paginado", data),
                {
                    headers: { Accept: "application/json" },
                }
            );
            return response.data.pacientes;
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
    const savePaciente = async (data) => {
        try {
            const response = await axios.post(route("pacientes.store", data), {
                headers: { Accept: "application/json" },
            });
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

    const deletePaciente = async (id) => {
        try {
            const response = await axios.delete(
                route("pacientes.destroy", id),
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

    const setPaciente = (item = null) => {
        if (item) {
            oPaciente.value.id = item.id;
            oPaciente.value.nombre = item.nombre;
            oPaciente.value.paterno = item.paterno;
            oPaciente.value.materno = item.materno;
            oPaciente.value.ci = item.ci;
            oPaciente.value.ci_exp = item.ci_exp;
            oPaciente.value.fecha_nac = item.fecha_nac;
            oPaciente.value.sexo = item.sexo;
            oPaciente.value.estado_civil = item.estado_civil;
            oPaciente.value.nacionalidad = item.nacionalidad;
            oPaciente.value.lugar_nac = item.lugar_nac;
            oPaciente.value.ocupacion = item.ocupacion;
            oPaciente.value.dir = item.dir;
            oPaciente.value.fono = item.fono;
            oPaciente.value.correo = item.correo;
            oPaciente.value.nom_familiar = item.nom_familiar;
            oPaciente.value.fono_familiar = item.fono_familiar;
            oPaciente.value.foto = item.foto;
            oPaciente.value._method = "PUT";
            return oPaciente;
        }
        return false;
    };

    const limpiarPaciente = () => {
        oPaciente.value.id = 0;
        oPaciente.value.nombre = "";
        oPaciente.value.paterno = "";
        oPaciente.value.materno = "";
        oPaciente.value.ci = "";
        oPaciente.value.ci_exp = "";
        oPaciente.value.fecha_nac = "";
        oPaciente.value.sexo = "";
        oPaciente.value.estado_civil = "";
        oPaciente.value.nacionalidad = "";
        oPaciente.value.lugar_nac = "";
        oPaciente.value.ocupacion = "";
        oPaciente.value.dir = "";
        oPaciente.value.fono = "";
        oPaciente.value.correo = "";
        oPaciente.value.nom_familiar = "";
        oPaciente.value.fono_familiar = "";
        oPaciente.value.foto = "";
        oPaciente.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oPaciente,
        getPacientes,
        getPacientesApi,
        savePaciente,
        deletePaciente,
        setPaciente,
        limpiarPaciente,
        getPacientesByTipo,
    };
};
