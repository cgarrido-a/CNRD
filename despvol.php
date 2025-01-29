<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED);

include_once('plantillas/DecInc.inc.php');
?>

<div class="container mt-4">
    <h1 class="mb-4">Vista Jerárquica de Voluntarios</h1>

    <!-- Filtro por Región -->
    <div class="mb-3">
        <label for="regionFilter" class="form-label">Filtrar por Región:</label>
        <select id="regionFilter" class="form-select">
            <option value="">Todas las Regiones</option>
            <!-- Las opciones se generarán dinámicamente con JavaScript -->
        </select>
    </div>

    <!-- Contenedor de la Vista -->
    <div id="vistaVoluntarios">
        <!-- Aquí se cargará dinámicamente la jerarquía -->
    </div>
</div>

<script>
    const API_URL = 'ruta_a_tu_api.php'; // Cambia esta ruta según tu servidor

    // Cargar JSON desde el backend
    async function cargarDatos(region = '') {
        try {
            const response = {
                "Región Metropolitana": {
                    "Santiago": [{
                            "nombre": "Clínica B",
                            "total_voluntarios": 63,
                            "tipos_alimentacion": {
                                "veganos": 5,
                                "vegetarianos": 21,
                                "omnivoros": 37
                            }
                        },
                        {
                            "nombre": "Clínica A",
                            "total_voluntarios": 97,
                            "tipos_alimentacion": {
                                "veganos": 31,
                                "vegetarianos": 26,
                                "omnivoros": 40
                            }
                        },
                        {
                            "nombre": "Clínica C",
                            "total_voluntarios": 78,
                            "tipos_alimentacion": {
                                "veganos": 1,
                                "vegetarianos": 5,
                                "omnivoros": 72
                            }
                        }
                    ],
                    "Puente Alto": [{
                            "nombre": "Clínica B",
                            "total_voluntarios": 77,
                            "tipos_alimentacion": {
                                "veganos": 22,
                                "vegetarianos": 15,
                                "omnivoros": 40
                            }
                        },
                        {
                            "nombre": "Clínica C",
                            "total_voluntarios": 76,
                            "tipos_alimentacion": {
                                "veganos": 24,
                                "vegetarianos": 15,
                                "omnivoros": 37
                            }
                        }
                    ],
                    "Maipú": [{
                            "nombre": "Clínica A",
                            "total_voluntarios": 95,
                            "tipos_alimentacion": {
                                "veganos": 27,
                                "vegetarianos": 0,
                                "omnivoros": 68
                            }
                        },
                        {
                            "nombre": "Clínica B",
                            "total_voluntarios": 71,
                            "tipos_alimentacion": {
                                "veganos": 4,
                                "vegetarianos": 16,
                                "omnivoros": 51
                            }
                        }
                    ]
                },
                "Valparaíso": {
                    "Valparaíso": [{
                            "nombre": "Clínica C",
                            "total_voluntarios": 76,
                            "tipos_alimentacion": {
                                "veganos": 4,
                                "vegetarianos": 23,
                                "omnivoros": 49
                            }
                        },
                        {
                            "nombre": "Clínica A",
                            "total_voluntarios": 52,
                            "tipos_alimentacion": {
                                "veganos": 3,
                                "vegetarianos": 14,
                                "omnivoros": 35
                            }
                        },
                        {
                            "nombre": "Clínica B",
                            "total_voluntarios": 86,
                            "tipos_alimentacion": {
                                "veganos": 23,
                                "vegetarianos": 26,
                                "omnivoros": 37
                            }
                        }
                    ],
                    "Viña del Mar": [{
                        "nombre": "Clínica B",
                        "total_voluntarios": 59,
                        "tipos_alimentacion": {
                            "veganos": 4,
                            "vegetarianos": 7,
                            "omnivoros": 48
                        }
                    }],
                    "Quilpué": [{
                        "nombre": "Clínica C",
                        "total_voluntarios": 90,
                        "tipos_alimentacion": {
                            "veganos": 16,
                            "vegetarianos": 15,
                            "omnivoros": 59
                        }
                    }]
                },
                "Biobío": {
                    "Concepción": [{
                            "nombre": "Clínica C",
                            "total_voluntarios": 97,
                            "tipos_alimentacion": {
                                "veganos": 14,
                                "vegetarianos": 19,
                                "omnivoros": 64
                            }
                        },
                        {
                            "nombre": "Clínica B",
                            "total_voluntarios": 73,
                            "tipos_alimentacion": {
                                "veganos": 4,
                                "vegetarianos": 10,
                                "omnivoros": 59
                            }
                        },
                        {
                            "nombre": "Clínica A",
                            "total_voluntarios": 83,
                            "tipos_alimentacion": {
                                "veganos": 26,
                                "vegetarianos": 4,
                                "omnivoros": 53
                            }
                        }
                    ],
                    "Talcahuano": [{
                        "nombre": "Clínica C",
                        "total_voluntarios": 80,
                        "tipos_alimentacion": {
                            "veganos": 17,
                            "vegetarianos": 20,
                            "omnivoros": 43
                        }
                    }],
                    "Chillán": [{
                        "nombre": "Clínica A",
                        "total_voluntarios": 91,
                        "tipos_alimentacion": {
                            "veganos": 7,
                            "vegetarianos": 7,
                            "omnivoros": 77
                        }
                    }]
                },
                "Araucanía": {
                    "Temuco": [{
                        "nombre": "Clínica B",
                        "total_voluntarios": 67,
                        "tipos_alimentacion": {
                            "veganos": 18,
                            "vegetarianos": 20,
                            "omnivoros": 29
                        }
                    }],
                    "Villarrica": [{
                            "nombre": "Clínica B",
                            "total_voluntarios": 93,
                            "tipos_alimentacion": {
                                "veganos": 26,
                                "vegetarianos": 26,
                                "omnivoros": 41
                            }
                        },
                        {
                            "nombre": "Clínica C",
                            "total_voluntarios": 91,
                            "tipos_alimentacion": {
                                "veganos": 2,
                                "vegetarianos": 4,
                                "omnivoros": 85
                            }
                        },
                        {
                            "nombre": "Clínica A",
                            "total_voluntarios": 75,
                            "tipos_alimentacion": {
                                "veganos": 0,
                                "vegetarianos": 13,
                                "omnivoros": 62
                            }
                        }
                    ],
                    "Angol": [{
                        "nombre": "Clínica C",
                        "total_voluntarios": 95,
                        "tipos_alimentacion": {
                            "veganos": 7,
                            "vegetarianos": 12,
                            "omnivoros": 76
                        }
                    }]
                }
            };
            if (!response.ok) {
                throw new Error('Error al obtener los datos');
            }
            const data = await response.json();
            renderVista(data);
            actualizarFiltroRegiones(data);
        } catch (error) {
            console.error('Error al cargar los datos:', error);
            alert('No se pudieron cargar los datos. Por favor, inténtelo más tarde.');
        }
    }

    // Renderizar la vista jerárquica
    function renderVista(data) {
        const container = document.getElementById('vistaVoluntarios');
        container.innerHTML = ''; // Limpiar contenido previo

        for (const region in data) {
            const regionDiv = document.createElement('div');
            regionDiv.classList.add('region');
            regionDiv.innerHTML = `<h2>${region} (Total Voluntarios)</h2>`;

            for (const comuna in data[region]) {
                const comunaDiv = document.createElement('div');
                comunaDiv.classList.add('comuna');
                comunaDiv.innerHTML = `<h3>${comuna} (Total Voluntarios)</h3>`;

                data[region][comuna].forEach(clinica => {
                    const clinicaDiv = document.createElement('div');
                    clinicaDiv.classList.add('clinica');
                    clinicaDiv.innerHTML = `
                        <p><strong>${clinica.nombre}</strong> - Total Voluntarios: ${clinica.total_voluntarios}</p>
                        <ul>
                            <li>Veganos: ${clinica.tipos_alimentacion.veganos}</li>
                            <li>Vegetarianos: ${clinica.tipos_alimentacion.vegetarianos}</li>
                            <li>Omnívoros: ${clinica.tipos_alimentacion.omnivoros}</li>
                        </ul>
                    `;
                    comunaDiv.appendChild(clinicaDiv);
                });

                regionDiv.appendChild(comunaDiv);
            }

            container.appendChild(regionDiv);
        }
    }

    // Actualizar las opciones del filtro de regiones
    function actualizarFiltroRegiones(data) {
        const regionFilter = document.getElementById('regionFilter');
        regionFilter.innerHTML = '<option value="">Todas las Regiones</option>'; // Reiniciar opciones

        for (const region in data) {
            const option = document.createElement('option');
            option.value = region;
            option.textContent = region;
            regionFilter.appendChild(option);
        }
    }

    // Evento para el filtro de región
    document.getElementById('regionFilter').addEventListener('change', function() {
        cargarDatos(this.value);
    });

    // Cargar la vista inicial (sin filtros)
    cargarDatos();
</script>

<?php
include_once('plantillas/DecFin.inc.php');
?>