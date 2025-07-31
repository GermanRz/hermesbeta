// TODO: documentacion: https://driverjs.com/docs/installation
// * Se solicita instanciar la clase de driver.js desde el objeto 'window'
const driver = window.driver.js.driver;

// * (RECOMENDACIÓN: INSTALAR LA EXTENSIÓN BETTER COMMENTS PARA MEJOR VISUALIZACIÓN DE LOS COMENTARIOS)
//   <!-- ========== Start Section ==========
//   TODO: Tour de inventario 
//   ========== End Section ========== -->
$(document).on("click", "#btnTourInventario", () => {
    // * Construye los pasos dinámicamente, es decir, steps(atributo del driverjs) recorrerá cada posición del array dinámico que creamos. En este caso stepsInventario.
    let stepsInventario = [];

    // Solo agregar los pasos si el usuario tiene los permisos necesarios o no.
    if (usuarioActual["permisos"].includes(1)) { // * Si el usuario tiene el permiso 1 (agregar equipo), agregará este paso al tour
        stepsInventario.push({ // * Al array de stepsInventario agregarle este paso
            element: '.tourAgregarEquipo',
            popover: {
                title: 'Agregar un equipo',
                description: 'Con este botón podrás agregar un equipo a la plataforma con su información. <br>Tales como su eqtiqueta, número de serie, descripción, categoría y su cuentadante',
                side: 'top',
                align: 'center',
                className: 'driverjs-custom-popover' 
            }
        });
    }

    if(usuarioActual["permisos"].includes(6)){
        stepsInventario.push({
            element: '.tourImportarEquipos',
            popover: {
                title: 'Importar equipos por archivo Excel',
                description: 'Con este botón podrás importar equipos a la plataforma desde un archivo Excel.',
                side: 'top',
                align: 'center',
                className: 'driverjs-custom-popover' 
            }
        });
    }

    stepsInventario.push({
        element: '.dt-buttons',
        popover: {
            title: 'Botones de exportación',
            description: 'Con estos botones podrás exportar los datos de la tabla a un archivo Excel, CSV (archivo de texto plano separado por comas), PDF (formato de archivo de portabilidad).',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    if(usuarioActual["permisos"].includes(3)){
        stepsInventario.push({
            element: '.btnEditarEquipo',
            popover: {
                title: 'Botón de edición',
                description: 'Con este botón podrás editar los datos de un equipo.',
                side: 'top',
                align: 'center',
                className: 'driverjs-custom-popover' 
            }
        });
    }

    if(usuarioActual["permisos"].includes(5)){
        stepsInventario.push({
            element: '.btnTraspasarEquipo',
            popover: {
                title: 'Botón de traspaso de cuentadante',
                description: 'Con este botón podrás traspasar un equipo a otra cuentadante autorizado.',
                side: 'top',
                align: 'center',
                className: 'driverjs-custom-popover' 
            }
        });
    }

    if(usuarioActual["permisos"].includes(4)){
        stepsInventario.push({
            element: '.btnTraspasarUbicacion',
            popover: {
                title: 'Botón de traspaso de ubicación',
                description: 'Con este botón podrás traspasar un equipo a otra ubicación.',
                side: 'top',
                align: 'center',
                className: 'driverjs-custom-popover' 
            }
        });
    }

    stepsInventario.push({
        element: '.btnHistorialEquipo',
        popover: {
            title: 'Botón de historial de equipo',
            description: 'Con este botón podrás ver el historial de un equipo. Mostrando su trazabilidad a lo largo del tiempo; sus movimientos como cuando fue agregado, préstamos, mantenimiento, traspasos de ubicación o cuentadante, etc.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });


    stepsInventario.push({
        popover: {
            title: 'Ya sabes como manejar el inventario<br>Felicidades!',
            description: '<div class="text-center"><img src="vistas/img/Logo/android-chrome-192x192.png" alt="gif fin tour" class="img-fluid"></div>',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });





    const driverObj = driver({
        popoverClass: 'driverjs-theme',
        showProgress: true,
        nextBtnText: 'Siguiente -›',
        prevBtnText: '‹- Anterior',
        doneBtnText: 'Ok',
        progressText: 'Paso {{current}} de {{total}}', 

        // Los pasos del tour para el inventario
        steps: stepsInventario
    });

    driverObj.drive();
});

//   <!-- ========== Start Section ==========
//   TODO: Tour de inicio
//   ========== End Section ========== -->

$(document).on("click", "#btnTourInicio", () => {
    const stepsInicio = [];

    stepsInicio.push({
        popover: {
            title: '¡Bienvenido a Hermes!',
            description: 'Te guiaremos a través de las funcionalidades principales de la plataforma. <br>Puedes usar las teclas flecha también para avanzar o retroceder en los pasos del tour. (<- ->)',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsInicio.push({
        element: '.tour-home',
        popover: {
            title: 'Inicio',
            description: 'Aquí podrás visualizar gráficas y estadísticas en general.',
            side: 'right',
            align: 'center',
            className: 'driverjs-custom-popover'
        }
    });

    if(usuarioActual["permisos"].includes(30) || usuarioActual["permisos"].includes(31)){ // * Si el usuario tiene el permiso 30 (administrar), agregará este paso al tour
        stepsInicio.push({
            element: '.tour-administar',
            popover: {
                title: 'Administración',
                description: 'En esta sección se administran los permisos, usuarios, sedes, roles, etc.',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    if(usuarioActual["permisos"].includes(34)){ // * Si el usuario tiene el permiso 31 (administrar usuarios), agregará este paso al tour
        stepsInicio.push({
            element: '.tour-usuarios',
            popover: {
                title: 'Usuarios',
                description: 'Aquí se administran los usuarios de la plataforma de Hermes.',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    if(usuarioActual["permisos"].includes(1)){ // * Si el usuario tiene el permiso 2 (administrar equipos), agregará este paso al tour
        stepsInicio.push({
            element: '.tour-equipos',
            popover: {
                title: 'Equipos',
                description: 'En esta sección se administran los equipos de la plataforma de Hermes.',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    if(usuarioActual["permisos"].includes(9)){
        stepsInicio.push({
            element: '.tour-solicitudes',
            popover: {
                title: 'Solicitudes',
                description: 'Aquí podrás consultar las solicitudes de los usuarios.',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    if(usuarioActual["permisos"].includes(42)){
        stepsInicio.push({
            element: '.tour-autorizaciones',
            popover: {
                title: 'Autorizaciones',
                description: 'En esta sección están los usuarios con autorización de los equipos pendientes por confirmar en trámite.',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    if(usuarioActual["permisos"].includes(18)){
        stepsInicio.push({
            element: '.tour-salidas',
            popover: {
                title: 'Salidas',
                description: 'En esta sección se consultan las salidas por autorizar de los equipos',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    if(usuarioActual["permisos"].includes(13)){
        stepsInicio.push({
            element: '.tour-devoluciones',
            popover: {
                title: 'Devoluciones',
                description: 'Los equipos en devolución se consultan en esta sección',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    if(usuarioActual["permisos"].includes(20)){
        stepsInicio.push({
            element: '.tour-mantenimiento',
            popover: {
                title: 'Mantenimiento',
                description: 'Equipos que llegan de devoluciones, llegan a mantenimiento o cuando se solicita de uno',
                side: 'right',
                align: 'center',
                className: 'driverjs-custom-popover'
            }
        });
    }

    stepsInicio.push({
        popover: {
            title: 'Inicio',
            description: 'En la sección de inicio podrás ver un resumen de la información de la plataforma representada en gráficas.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsInicio.push({
        element: '.estadisticasTour',
        popover: {
            title: 'Estadísticas',
            description: 'En la sección de estadísticas podrás ver un resumen de la información de la plataforma<br>Visualizando la cantidad de <strong>Equipos</strong>, <strong>Sonido</strong>, <strong>Videobeam</strong> y <strong>Controles</strong>.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsInicio.push({
        element: '.estadosEquiposTour',
        popover: {
            title: 'Gráficas de equipos por<br> estado',
            description: 'En la sección de gráficas de equipos por estado podrás ver un resumen de la información de los equipos',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsInicio.push({
        element: '.estadosPrestamosTour',
        popover: {
            title: 'Gráficas de préstamos por<br> estado',
            description: 'En la sección de gráficas de préstamos por estado podrás ver un resumen de la información de los préstamos',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsInicio.push({
        element: '.prestamosPorDiaTour',
        popover: {
            title: 'Gráficas de préstamos por<br>día',
            description: 'Diariamente se prestan equipos. En este gráfico podrás visualizar la cantidad de equipos prestados por día, incluyendo la cantidad de equipos prestados de esta semana y la semana pasada.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsInicio.push({
        element: '.usuariosPorFichaTour',
        popover: {
            title: 'Gráficas de usuarios por<br> ficha',
            description: 'Aquí podrás visualizar la cantidad de aprendices por ficha.<br>También podrás hacer un filtro de aprendices por género.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsInicio.push({
        popover: {
            title: 'Gracias por tomar el tour de Hermes!',
            description: '<div class="text-center"><img src="vistas/img/Logo/android-chrome-192x192.png" alt="gif fin tour" class="img-fluid"></div>',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });


    const driverObj = driver({
        popoverClass: 'driverjs-theme',
        showProgress: true,
        nextBtnText: 'Siguiente -›',
        prevBtnText: '‹- Anterior',
        doneBtnText: 'Ok',
        progressText: 'Paso {{current}} de {{total}}', 

        // Los pasos del tour para el inventario
        steps: stepsInicio
    });

    driverObj.drive();

})

//   <!-- ========== Start Section ==========
//   TODO: Tour de solicitudes
//   ========== End Section ========== -->

$(document).on("click", "#btnTourSolicitudes", () => {
    // Ejecutar el tour completo directamente ya que el botón solo aparece después de presionar el buscador
    mostrarModuloSolicitudes();
    
    const stepsSolicitudes = [];

    stepsSolicitudes.push({
        popover: {
            title: '¡Bienvenido al módulo de Solicitudes!',
            description: 'Te guiaremos a través del proceso para hacer una solicitud de equipos y herramientes.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    if(usuarioActual["permisos"].includes(30)){
            stepsSolicitudes.push({
            element: '#numeroSolicitante',
            popover: {
                title: 'Número de Identificación',
                description: 'Aquí debes ingresar el número de identificación del solicitante para buscar sus datos.',
                side: 'top',
                align: 'center',
                className: 'driverjs-custom-popover' 
            }
        });

        stepsSolicitudes.push({
            element: '#btnBuscarSolicitante',
            popover: {
                title: 'Buscar Solicitante',
                description: 'Con este botón podrás buscar los datos del solicitante ingresado.',
                side: 'top',
                align: 'center',
                className: 'driverjs-custom-popover' 
            }
        });
    }
    stepsSolicitudes.push({
        element: '#reservation',
        popover: {
            title: 'Rango de Fechas',
            description: 'Aquí podrás seleccionar el rango de fechas para ver los equipos disponibles en ese período.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsSolicitudes.push({
        element: '#tblActivosSolicitar',
        popover: {
            title: 'Equipos Disponibles',
            description: 'Aquí se muestran todos los equipos disponibles en el rango de fechas seleccionado. Puedes agregar equipos a la solicitud.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsSolicitudes.push({
        element: '#nombreSolicitante',
        popover: {
            title: 'Nombre del Solicitante',
            description: 'Aquí aparecerá automáticamente el nombre del solicitante una vez que lo busques.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsSolicitudes.push({
        element: '#initialDate',
        popover: {
            title: 'Fecha de Inicio',
            description: 'Selecciona la fecha de inicio para la solicitud de equipos.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsSolicitudes.push({
        element: '#finalDate',
        popover: {
            title: 'Fecha de Fin',
            description: 'Selecciona la fecha de fin para la solicitud de equipos.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsSolicitudes.push({
        element: '.btnGuardarSolicitud',
        popover: {
            title: 'Guardar Solicitud',
            description: 'Con este botón podrás guardar la solicitud de equipos creada.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsSolicitudes.push({
        element: '#btnHistorial',
        popover: {
            title: 'Historial',
            description: 'Con este botón podrás ver el historial de solicitudes del solicitante.',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    stepsSolicitudes.push({
        popover: {
            title: '¡Ya sabes cómo crear solicitudes!',
            description: '<div class="text-center"><img src="vistas/img/Logo/android-chrome-192x192.png" alt="gif fin tour" class="img-fluid"></div>',
            side: 'top',
            align: 'center',
            className: 'driverjs-custom-popover' 
        }
    });

    const driverObj = driver({
        popoverClass: 'driverjs-theme',
        showProgress: true,
        nextBtnText: 'Siguiente -›',
        prevBtnText: '‹- Anterior',
        doneBtnText: 'Ok',
        progressText: 'Paso {{current}} de {{total}}', 
        steps: stepsSolicitudes
    });

    driverObj.drive();
});

// Funciones auxiliares para el módulo de solicitudes
function mostrarModuloSolicitudes() {
    // Remover la clase d-none del módulo de solicitudes
    const moduloSolicitudes = document.getElementById('modulo-solicitudes');
    if (moduloSolicitudes) {
        moduloSolicitudes.classList.remove('d-none');
    }
}

// Función para controlar la visibilidad del botón del tour
function controlarVisibilidadTour() {
    const inputId = document.getElementById('btnBuscarSolicitante');
    const btnTour = document.getElementById('btnTourSolicitudes');
    
    if (inputId && btnTour) {
        // Escuchar cambios en el input
        inputId.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                // Si hay valor, mostrar el botón del tour
                btnTour.style.display = 'inline-block';
                btnTour.style.color = '#000';
                btnTour.style.cursor = 'pointer';
            } else {
                // Si no hay valor, ocultar el botón del tour
                btnTour.style.display = 'none';
            }
        });
        
        // Verificar estado inicial
        if (inputId.value.trim() === '') {
            btnTour.style.display = 'none';
        }
    }
}

// Escuchar el evento de clic en el botón de búsqueda para mostrar el botón del tour
$(document).on("click", "#btnBuscarSolicitante", function() {
    // Esperar un momento para que se carguen los datos del solicitante
    setTimeout(() => {
        const nombreSolicitante = document.getElementById('nombreSolicitante');
        const btnTour = document.getElementById('btnTourSolicitudes');
        
        if (nombreSolicitante && nombreSolicitante.value.trim() !== '' && btnTour) {
            // Si se encontró el solicitante, mostrar el botón del tour
            btnTour.style.display = 'inline-block';
            btnTour.style.color = '#000';
            btnTour.style.cursor = 'pointer';
        }
    }, 1000);
});

// Ejecutar cuando el DOM esté listo
$(document).ready(function() {
    controlarVisibilidadTour();
});
