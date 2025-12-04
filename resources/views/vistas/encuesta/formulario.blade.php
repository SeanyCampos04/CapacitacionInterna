<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Encuesta - ') }} {{ $curso->nombre }}
        </h2>
    </x-slot>
    <div class="min-h-screen flex flex-col  items-center pt-6  bg-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Encuesta para Participantes Inscritos</h2>

        <!-- Formulario completo con tres secciones -->
        <form id="surveyForm" action="{{ route('encuesta.store') }}" method="POST" class="w-full lg:max-w-6xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
        <input type="hidden" name="participante_id" value="{{ $participante->id }}">
            <!-- Sección 1: Evaluación del evento -->
            <div id="section1" class="space-y-6">
                <label class="block text-black-700 font-bold">Evalúa el evento</label>
                <div class="overflow-x-auto">
                    <!-- Aquí va la tabla de evaluación del evento (copiada del código original) -->
                    <!-- Ejemplo de tabla simplificado -->
                    <table class="w-full table-auto border-collapse border border-black-300">
                        <thead>
                            <tr class="bg-blue-200"
                                style="background-color: #1B396A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                <th class="border border-gray-300 px-4 py-2 text-left"></th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Totalmente de acuerdo</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Parcialmente de acuerdo</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Indiferente</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Parcialmente en desacuerdo</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Totalmente en desacuerdo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">El evento cubrió sus expectativas</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_expectativas" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_expectativas" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_expectativas" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_expectativas" value="2"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_expectativas" value="1"
                                        required>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Se cumplió con el objetivo y programa</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_objetivo" value="5" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_objetivo" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_objetivo" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_objetivo" value="2"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="evento_objetivo" value="1"
                                        required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">El instructor aclaró las dudas que se
                                    presentaron durante el curso</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_dudas" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_dudas" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_dudas" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_dudas" value="2"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_dudas" value="1"
                                        required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Los contenidos del material estuvieron
                                    estructurados en forma lógica, clara y sencilla</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_material" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_material" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_material" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_material" value="2"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_material" value="1"
                                        required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Los contenidos del curso son útiles para
                                    su desempeño laboral</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_curso" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_curso" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_curso" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_curso" value="2"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="contenidos_curso" value="1"
                                        required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Las condiciones físicas y/o virtuales
                                    fueron las adecuadas para el desarrollo del evento</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="condiciones_adecuadas" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="condiciones_adecuadas"
                                        value="4" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="condiciones_adecuadas" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="condiciones_adecuadas"
                                        value="2" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="condiciones_adecuadas"
                                        value="1" required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">El personal organizador realizó las
                                    actividades necesarias para el mejor desarrollo del evento</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="personal_organizador" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="personal_organizador" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="personal_organizador" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="personal_organizador"
                                        value="2" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="personal_organizador"
                                        value="1" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sección 2: Evaluación del instructor -->
            <div id="section2" class="space-y-6 hidden">
                <llabel class="block text-black-700 font-bold">Evalúa al instructor</label>
                <div class="overflow-x-auto">
                    <!-- Aquí va la tabla de evaluación del instructor (copiada del código original) -->
                    <table class="w-full table-auto border-collapse border border-black-300">
                        <thead>
                            <tr class="bg-blue-200"
                                style="background-color: #1B396A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                <th class="border border-gray-300 px-4 py-2 text-left"></th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Totalmente de acuerdo</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Parcialmente de acuerdo</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Indiferente</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Parcialmente en desacuerdo
                                </th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Totalmente en desacuerdo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">El instructor mostró habilidad para
                                    transmitir el contenido del curso</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_habilidad" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_habilidad" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_habilidad" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_habilidad"
                                        value="2" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_habilidad"
                                        value="1" required>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">El instructor expuso de manera clara y
                                    precisa el objetivo, el programa y criterios de evaluación del curso</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_exposicion" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_exposicion"
                                        value="4" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_exposicion" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_exposicion"
                                        value="2" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_exposicion"
                                        value="1" required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">El instructor aclaró las dudas que se
                                    presentaron durante el curso</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_aclara" value="5"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_aclara" value="4"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_aclara" value="3" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_aclara" value="2"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="instructor_aclara" value="1"
                                        required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="section3" class="space-y-6 hidden">
                <llabel class="block text-black-700 font-bold">Evalúa el Desarrollo Profesional y/o Docente</label>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-black-300">
                        <thead>
                            <tr class="bg-blue-200"
                                style="background-color: #1B396A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                                <th class="border border-gray-300 px-4 py-2 text-left"></th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Totalmente de acuerdo</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Parcialmente de acuerdo</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Indiferente</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Parcialmente en desacuerdo
                                </th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Totalmente en desacuerdo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Las competencias desarrolladas con el
                                    evento mejoran su desempeño docente y/o profesional</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_desarrolladas"
                                        value="5" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_desarrolladas"
                                        value="4" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_desarrolladas" value="3"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_desarrolladas"
                                        value="2" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_desarrolladas"
                                        value="1" required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Las competencias adquiridas con el evento
                                    propician el trabajo colaborativo</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_adquiridas"
                                        value="5" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_adquiridas"
                                        value="4" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_adquiridas" value="3"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_adquiridas"
                                        value="2" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_adquiridas"
                                        value="1" required>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-gray-300 px-4 py-2">Las competencias adquiridas le permitirán
                                    mayor comprensión de sus funciones y responsabilidades en la institución</td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_comprension"
                                        value="5" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_comprension"
                                        value="4" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_comprension" value="3"
                                        required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_comprension"
                                        value="2" required>
                                </td>
                                <td class="border border-gray-300 text-center">
                                    <input type="radio" name="competencias_comprension"
                                        value="1" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div><br>

                <div class="space-y-2">
                    <label class="block text-gray-700 font-semibold">¿Participaste activamente durante el evento?</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="radio" name="participacion" value="1" required>
                            <span class="ml-2">Sí</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="participacion" value="0" required>
                            <span class="ml-2">No</span>
                        </label>
                    </div>
                </div><br>

                <div class="space-y-2">

                    <label for="comentarios" class="block text-gray-700 font-semibold"
                        style="background-color: #1B396A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">Comentarios</label>
                    <textarea id="comentarios" name="comentarios" rows="4" placeholder="Escribe tus comentarios aquí..."
                        class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <!-- Botones de navegación -->
            <div class="flex justify-between">
                <button type="button" id="prevBtn"
                    class="py-3 px-6 bg-gray-400 text-white font-bold rounded-lg shadow-md hover:bg-gray-500 transition duration-300 ease-in-out hidden"
                    style="background-color: #1B396A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                    Anterior
                </button>
                <button type="button" id="nextBtn"
                    class="py-3 px-6 bg-blue-500 text-white font-bold rounded-lg shadow-md hover:bg-blue-600 transition duration-300 ease-in-out"
                    style="background-color: #1B396A; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">
                    Siguiente
                </button>
                <button type="submit" id="submitBtn"
                    class="py-3 px-6 bg-green-500 text-white font-bold rounded-lg shadow-md hover:bg-green-600 transition duration-300 ease-in-out hidden"
                    onclick="return confirm('¿Estás seguro de que quieres enviar estas respuestas?');">
                    Enviar
                </button>
            </div>
        </form>
    </div>

    <script>
        let currentSection = 1;
        const totalSections = 3;

        // Mostrar sección actual y ocultar las otras
        function showSection(section) {
            for (let i = 1; i <= totalSections; i++) {
                document.getElementById(`section${i}`).classList.add('hidden');
            }
            document.getElementById(`section${section}`).classList.remove('hidden');

            // Mostrar/ocultar botones según la sección
            document.getElementById('prevBtn').classList.toggle('hidden', section === 1);
            document.getElementById('nextBtn').classList.toggle('hidden', section === totalSections);
            document.getElementById('submitBtn').classList.toggle('hidden', section !== totalSections);
        }

        // Navegar hacia adelante
        document.getElementById('nextBtn').addEventListener('click', function() {
            if (currentSection < totalSections) {
                currentSection++;
                showSection(currentSection);
            }
        });

        // Navegar hacia atrás
        document.getElementById('prevBtn').addEventListener('click', function() {
            if (currentSection > 1) {
                currentSection--;
                showSection(currentSection);
            }
        });

        // Mostrar la primera sección al cargar
        showSection(currentSection);
    </script>
</x-app-layout>
