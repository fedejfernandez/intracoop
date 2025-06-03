<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trabajador;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ImportarNominaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nomina:importar {archivo : Ruta al archivo Excel de la nómina}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa los datos de la nómina desde un archivo Excel, actualizando o creando trabajadores y usuarios.';

    // Mapeo de columnas Excel a atributos del modelo Trabajador
    protected $columnMapping = [
        'Legajo' => 'NumeroLegajo',
        'Apellido y Nombre' => 'NombreCompleto',
        'Dirección' => 'Direccion',
        'Localidad' => 'Localidad',
        'Provincia' => 'Provincia',
        'Código Postal' => 'CodigoPostal',
        'Teléfono' => 'Telefono',
        'Tipo Documento' => 'TipoDocumento',
        'Nro Documento' => 'DNI_CUIL', // Se usará como DNI/CUIL principal si C.U.I.L. no está o es igual
        'Nacionalidad' => 'Nacionalidad',
        'Estado Civil' => 'EstadoCivil',
        'Sexo' => 'Sexo',
        'Fecha de Nacimiento' => 'FechaNacimiento',
        'Banco' => 'Banco',
        'Nro. de Cuenta Bancaria' => 'NroCuentaBancaria',
        'C.B.U.' => 'CBU',
        'Datos Adic. Bco.' => 'DatosAdicBco',
        'e-mail' => 'Email',
        'Categoria' => 'Categoria',
        'Función' => 'Puesto', // Mapeamos Función del Excel a Puesto en el modelo
        'Fecha de Ingreso' => 'FechaIngreso',
        'Fecha Reconocida' => 'FechaReconocida',
        'C.U.I.L.' => 'DNI_CUIL_Excel', // Temporal para comparar con Nro Documento
        'C.C.T.' => 'CCT',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('archivo');

        if (!file_exists($filePath)) {
            $this->error("El archivo no existe en la ruta especificada: {$filePath}");
            return 1;
        }

        $this->info("Iniciando importación desde: {$filePath}");

        $data = Excel::toArray((object) [], $filePath);

        if (empty($data) || empty($data[0])) {
            $this->error('No se pudieron leer datos del archivo Excel o está vacío.');
            return 1;
        }

        $rows = $data[0];
        $header = array_map('trim', array_shift($rows)); // Limpiar encabezados

        $processedCount = 0;
        $createdCount = 0;
        $updatedCount = 0;
        $errorCount = 0;

        foreach ($rows as $rowIndex => $row) {
            if (empty(array_filter($row))) { 
                continue;
            }
            
            // Asegurarse de que $row tenga la misma cantidad de elementos que $header
            if (count($header) !== count($row)) {
                $this->error("Error en fila " . ($rowIndex + 2) . ": El número de columnas no coincide con el encabezado. Saltando fila.");
                $errorCount++;
                continue;
            }
            $rowData = array_combine($header, $row);

            $this->info("Procesando fila " . ($rowIndex + 2) . ": " . ($rowData['Apellido y Nombre'] ?? 'Desconocido') . " (Legajo: " . ($rowData['Legajo'] ?? 'N/A') . ")" );

            $trabajadorData = [];
            foreach ($this->columnMapping as $excelCol => $modelAttr) {
                if (isset($rowData[$excelCol])) {
                    $value = $rowData[$excelCol];
                    if (Str::startsWith($modelAttr, 'Fecha')) {
                        if (empty($value)) {
                            $trabajadorData[$modelAttr] = null;
                        } else {
                            try {
                                if (is_numeric($value)) {
                                    $trabajadorData[$modelAttr] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
                                } else {
                                    // Intentar parsear con formatos comunes, incluyendo d/m/Y
                                    $trabajadorData[$modelAttr] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                                }
                            } catch (\Exception $e1) {
                                try {
                                    // Si falla d/m/Y, intentar un parseo más general
                                    $trabajadorData[$modelAttr] = Carbon::parse($value)->format('Y-m-d');
                                } catch (\Exception $e2) {
                                    $this->warn("  Advertencia: Fecha '{$value}' en columna '{$excelCol}' no válida. Se dejará como null. Error: " . $e2->getMessage());
                                    $trabajadorData[$modelAttr] = null;
                                }
                            }
                        }
                    } elseif ($modelAttr === 'Email') {
                        $trimmedEmail = trim($value);
                        $trabajadorData[$modelAttr] = !empty($trimmedEmail) ? $trimmedEmail : null;
                    } else {
                        $trabajadorData[$modelAttr] = is_string($value) ? trim($value) : $value;
                    }
                } else {
                     // Si la columna del Excel no existe en $rowData (puede pasar si el Excel tiene menos columnas de las esperadas)
                     // Se asigna null si el atributo del modelo es fillable, para evitar errores más adelante.
                     if (in_array($modelAttr, (new Trabajador())->getFillable())){
                        $trabajadorData[$modelAttr] = null;
                     }
                }
            }
            
            $cuilDesdeExcel = $trabajadorData['DNI_CUIL_Excel'] ?? null;
            $nroDocDesdeExcel = $trabajadorData['DNI_CUIL'] ?? null; 

            if (!empty($cuilDesdeExcel) && strlen(preg_replace('/[^0-9]/', '', $cuilDesdeExcel)) == 11) {
                $trabajadorData['DNI_CUIL'] = preg_replace('/[^0-9]/', '', $cuilDesdeExcel);
            } elseif (!empty($nroDocDesdeExcel)) {
                $trabajadorData['DNI_CUIL'] = preg_replace('/[^0-9]/', '', $nroDocDesdeExcel);
            } else {
                 $trabajadorData['DNI_CUIL'] = null;
            }
            unset($trabajadorData['DNI_CUIL_Excel']);

            $rules = [
                'NumeroLegajo' => 'required|string|max:255',
                'NombreCompleto' => 'required|string|max:255',
                'DNI_CUIL' => 'required|string|max:20', // Ahora es requerido para creación
                'Email' => 'nullable|email|max:255',
                'FechaNacimiento' => 'nullable|date',
                'FechaIngreso' => 'nullable|date',
                'FechaReconocida' => 'nullable|date',
            ];
            
            // Si el Email no es null, debe ser único en la tabla users si se va a crear un nuevo usuario.
            // Esta validación es más compleja de poner aquí directamente si el usuario ya existe.
            // Se manejará más abajo al intentar crear/asociar el usuario.

            $validator = Validator::make($trabajadorData, $rules);

            if ($validator->fails()) {
                $this->error("  Errores de validación para " . ($trabajadorData['NombreCompleto'] ?? 'N/A') . " (Legajo: " . ($trabajadorData['NumeroLegajo'] ?? 'N/A') . "):");
                foreach ($validator->errors()->all() as $error) {
                    $this->error("    - {$error}");
                }
                $errorCount++;
                continue;
            }
            
            // Inicia la transacción aquí, fuera del try-catch principal del bucle,
            // para que cada fila sea su propia transacción.
            DB::transaction(function () use ($rowData, $rowIndex, &$trabajadorData, &$createdCount, &$updatedCount, &$processedCount, &$errorCount) {
                // El try-catch interno manejará excepciones específicas de la fila sin detener todo el bucle,
                // pero la transacción se revertirá para esta fila si hay un error de BD.
                try {
                    // Mover la lógica de procesamiento de $rowData y $trabajadorData aquí dentro si es necesario
                    // o asegurar que $trabajadorData se pase correctamente si se modifica antes de este punto.
                    // Por simplicidad, asumimos que $trabajadorData está listo antes de la transacción como estaba antes.

                    // La validación de datos del $validator ya ocurrió antes, así que no necesitamos repetirla aquí.
                    // Si la validación falla, ya se hizo `continue`.

                    $trabajador = null;
                    if (!empty($trabajadorData['NumeroLegajo'])) {
                        $trabajador = Trabajador::where('NumeroLegajo', $trabajadorData['NumeroLegajo'])->first();
                    }

                    if (!$trabajador && !empty($trabajadorData['DNI_CUIL'])) {
                        $trabajador = Trabajador::where('DNI_CUIL', $trabajadorData['DNI_CUIL'])->first();
                        if ($trabajador && empty($trabajador->NumeroLegajo) && !empty($trabajadorData['NumeroLegajo'])) {
                            $trabajador->NumeroLegajo = $trabajadorData['NumeroLegajo'];
                        }
                    }
                    
                    $fillableFields = (new Trabajador())->getFillable();
                    $datosParaGuardar = array_intersect_key($trabajadorData, array_flip($fillableFields));

                    if ($trabajador) {
                        if (isset($datosParaGuardar['Email']) && $datosParaGuardar['Email'] !== $trabajador->Email && $datosParaGuardar['Email'] !== null) {
                            if (Trabajador::where('Email', $datosParaGuardar['Email'])->where('ID_Trabajador', '!=', $trabajador->ID_Trabajador)->exists()) {
                                // Este error debería lanzar una excepción o ser manejado para que la transacción haga rollback
                                throw new \Exception("El email " . $datosParaGuardar['Email'] . " ya está en uso por otro trabajador. Legajo: ".$datosParaGuardar['NumeroLegajo']);
                            }
                        }
                        $trabajador->update($datosParaGuardar);
                        $this->line("  Actualizado trabajador: " . $trabajador->NombreCompleto . " (Legajo: " . $trabajador->NumeroLegajo . ")");
                        $updatedCount++;
                    } else {
                        if (empty($datosParaGuardar['DNI_CUIL'])) {
                            throw new \Exception("DNI/CUIL es requerido para crear un nuevo trabajador. Legajo: ".$datosParaGuardar['NumeroLegajo']);
                        }
                        if (Trabajador::where('DNI_CUIL', $datosParaGuardar['DNI_CUIL'])->exists()) {
                            throw new \Exception("Ya existe un trabajador con DNI/CUIL " . $datosParaGuardar['DNI_CUIL'] . ". No se puede crear duplicado para '". $datosParaGuardar['NombreCompleto']."' con Legajo '".$datosParaGuardar['NumeroLegajo']."'");
                        }
                        if (isset($datosParaGuardar['Email']) && $datosParaGuardar['Email'] !== null && Trabajador::where('Email', $datosParaGuardar['Email'])->exists()){
                            throw new \Exception("El email " . $datosParaGuardar['Email'] . " ya está en uso por otro trabajador. No se puede crear duplicado para Legajo: ".$datosParaGuardar['NumeroLegajo']);
                        }                    

                        $trabajador = Trabajador::create($datosParaGuardar);
                        $this->info("  Creado nuevo trabajador: " . $trabajador->NombreCompleto . " (Legajo: " . $trabajador->NumeroLegajo . ")");
                        $createdCount++;
                    }

                    if (!empty($trabajador->Email) && !$trabajador->user_id) {
                        $user = User::where('email', $trabajador->Email)->first();
                        if (!$user) {
                            if (User::where('email', $trabajador->Email)->exists()) {
                                 $this->warn("    Advertencia: Ya existe un usuario con el email {$trabajador->Email} pero no está asociado a este trabajador. Verifique manualmente.");
                                 // Considerar si esto debería ser un error que impida la transacción o solo una advertencia.
                            } else {
                                $password = Str::random(10);
                                $user = User::create([
                                    'name' => $trabajador->NombreCompleto,
                                    'email' => $trabajador->Email,
                                    'password' => Hash::make($password),
                                    'role' => 'portal', 
                                ]);
                                $this->line("    Usuario creado para {$trabajador->Email} con contraseña temporal: {$password}");
                            }
                        }
                        if ($user && !$trabajador->user_id) {
                            $trabajador->user_id = $user->id;
                            $trabajador->save(); // Esta es una operación de escritura, crucial para la transacción
                            $this->line("    Usuario {$user->email} asociado al trabajador " . $trabajador->NombreCompleto);
                        }
                    }
                    $processedCount++;

                } catch (\Illuminate\Database\QueryException $e) {
                    // Los QueryExceptions harán rollback de la transacción automáticamente si no se manejan aquí.
                    // Pero es bueno loguearlos y contar el error.
                    $this->error("  Error de BD procesando fila " . ($rowIndex + 2) . ": " . $e->getMessage());
                    $errorCount++;
                    // No es necesario `continue` aquí si el DB::transaction está fuera del catch general del bucle.
                    // Si la transacción falla, no se debe incrementar processedCount.
                    // El control del bucle exterior manejará el `continue` o el paso a la siguiente iteración.
                    // De hecho, si esto ocurre, la transacción ya habrá hecho rollback.
                    throw $e; // Relanzar para que DB::transaction lo maneje y haga rollback.
                } catch (\Exception $e) {
                    $this->error("  Error Inesperado procesando fila " . ($rowIndex + 2) . ": " . $e->getMessage());
                    $errorCount++;
                    throw $e; // Relanzar para que DB::transaction lo maneje y haga rollback.
                }
            }); // Fin de DB::transaction
        }

        $this->info('------------------------------------------------------------');
        $this->info("Importación completada.");
        $this->info("Total de filas en el archivo (después del encabezado): " . count($rows));
        $this->info("Filas procesadas exitosamente: {$processedCount}");
        $this->info("Trabajadores creados: {$createdCount}");
        $this->info("Trabajadores actualizados: {$updatedCount}");
        $this->info("Filas con errores (saltadas): {$errorCount}");
        return 0;
    }
}
