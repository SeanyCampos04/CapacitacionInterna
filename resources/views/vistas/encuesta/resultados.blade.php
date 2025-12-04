<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Resultados de la Encuesta - Curso:') }} {{ $curso->nombre }}
        </h2>
    </x-slot>

    <div class="container mx-auto mt-8 px-6 space-y-8">
        <h3 class="font-semibold text-xl text-black">Cantidad de respuestas: {{$encuestas->count()}}</h3>
        <!-- Leyenda General -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-center text-lg font-semibold text-gray-700 mb-4">Leyenda General</h3>
            <div class="flex flex-wrap space-x-4 justify-center">
                <div class="flex items-center mb-2">
                    <span class="w-4 h-4 inline-block mr-2" style="background-color: #FF6384;"></span>
                    <span>Totalmente en desacuerdo</span>
                </div>
                <div class="flex items-center mb-2">
                    <span class="w-4 h-4 inline-block mr-2" style="background-color: #36A2EB;"></span>
                    <span>Parcialmente en desacuerdo</span>
                </div>
                <div class="flex items-center mb-2">
                    <span class="w-4 h-4 inline-block mr-2" style="background-color: #FFCE56;"></span>
                    <span>Indiferente</span>
                </div>
                <div class="flex items-center mb-2">
                    <span class="w-4 h-4 inline-block mr-2" style="background-color: #4BC0C0;"></span>
                    <span>Parcialmente de acuerdo</span>
                </div>
                <div class="flex items-center mb-2">
                    <span class="w-4 h-4 inline-block mr-2" style="background-color: #9966FF;"></span>
                    <span>Totalmente de acuerdo</span>
                </div>
            </div>
        </div>
        <!-- Sección 1: Evaluación del Evento -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Evaluación del Evento</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div class="flex flex-col items-center">
                    <canvas id="chart_evento_expectativas" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">El evento cubrió sus expectativas</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_evento_objetivo" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Se cumplió con el objetivo y programa</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_instructor_dudas" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">El instructor aclaró las dudas que se presentaron durante el curso</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_contenidos_material" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Los contenidos del material estuvieron estructurados en forma lógica, clara y sencilla</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_contenidos_curso" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Los contenidos del curso son útiles para su desempeño laboral</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_condiciones_adecuadas" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Las condiciones físicas y/o virtuales fueron las adecuadas para el desarrollo del evento</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_personal_organizador" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">El personal organizador realizó las actividades necesarias para el mejor desarrollo del evento</h3>
                </div>
            </div>
        </div>

        <!-- Sección 2: Evaluación del Instructor -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Evaluación del Instructor</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="flex flex-col items-center">
                    <canvas id="chart_instructor_habilidad" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">El instructor mostró habilidad para transmitir el contenido del curso</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_instructor_exposicion" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">El instructor expuso de manera clara y precisa el objetivo, el programa y criterios de evaluación del curso</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_instructor_aclara" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">El instructor aclaró las dudas que se presentaron durante el curso</h3>
                </div>
            </div>
        </div>

        <!-- Sección 3: Desarrollo Profesional y/o Docente -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Desarrollo Profesional y/o Docente</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="flex flex-col items-center">
                    <canvas id="chart_competencias_desarrolladas" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Las competencias desarrolladas con el evento mejoran su desempeño docente y/o profesional</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_competencias_adquiridas" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Las competencias adquiridas con el evento propician el trabajo colaborativo</h3>
                </div>
                <div class="flex flex-col items-center">
                    <canvas id="chart_competencias_comprension" width="400" height="300"></canvas>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Las competencias adquiridas le permitirán mayor comprensión de sus funciones y responsabilidades en la institución</h3>
                </div>
            </div>
        </div>

        <!-- Sección 4: Participación y Participantes por Departamento -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Otros</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="flex flex-col items-center">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Participación activa durante el evento</h3>
                    <canvas id="chart_participacion" width="400" height="300"></canvas>
                </div>
                <div class="flex flex-col items-center">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Participantes por departamento</h3>
                    <canvas id="chart_departamentos" width="400" height="300"></canvas>
                </div>
                <div class="flex flex-col items-center space-y-4 max-h-[500px] overflow-y-auto w-full">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Comentarios</h3>
                    @foreach ($encuestas as $encuesta)
                        <div class="w-full max-w-md p-4 bg-gray-100 shadow-md rounded-lg border-2-4 border-blue-500 mb-2">
                            <p class="text-gray-600 text-sm">{{ $encuesta->comentario }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuración global para ocultar los labels del eje X en las gráficas
        const globalOptions = {
            responsive: true,
            plugins: {
                legend: { display: false } // Oculta la leyenda en la parte superior
            },
            scales: {
                x: { display: false }, // Oculta los labels del eje X
                y: { beginAtZero: false } // Configura el eje Y para empezar desde cero
            }
        };

        // Gráfico de Participación
        var ctx_participacion = document.getElementById('chart_participacion').getContext('2d');
        var chart_participacion = new Chart(ctx_participacion, {
            type: 'pie',
            data: {
                labels: ['Sí', 'No'],
                datasets: [{
                    label: 'Respuestas',
                    data: @json($resultados['participacion']),
                    backgroundColor: ['#FF6384', '#36A2EB'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true } // Mantén la leyenda para los gráficos de tipo pie
                }
            }
        });

        // Gráfico de Participantes por Departamento
        var ctx_departamentos = document.getElementById('chart_departamentos').getContext('2d');
        var chart_departamentos = new Chart(ctx_departamentos, {
            type: 'bar',
            data: {
                labels: @json(array_keys($departamentos->toArray())),
                datasets: [{
                    label: 'Participantes',
                    data: @json(array_values($departamentos->toArray())),
                    backgroundColor: '#4BC0C0',
                    borderWidth: 1
                }]
            },
            options: { responsive: true }
        });

        // Otros Gráficos (Barra)
        @foreach($resultados as $pregunta => $respuestas)
            @if($pregunta !== 'participacion')
                var ctx_{{ $pregunta }} = document.getElementById('chart_{{ $pregunta }}').getContext('2d');
                var chart_{{ $pregunta }} = new Chart(ctx_{{ $pregunta }}, {
                    type: 'bar',
                    data: {
                        labels: [
                            'Totalmente en desacuerdo',
                            'Parcialmente en desacuerdo',
                            'Indiferente',
                            'Parcialmente de acuerdo',
                            'Totalmente de acuerdo'
                        ],
                        datasets: [{
                            label: 'Respuestas',
                            data: @json($respuestas),
                            backgroundColor: [
                                '#FF6384',
                                '#36A2EB',
                                '#FFCE56',
                                '#4BC0C0',
                                '#9966FF'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: globalOptions
                });
            @endif
        @endforeach
    </script>

</x-app-layout>
