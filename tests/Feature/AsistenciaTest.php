<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Alumno;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AsistenciaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function alumno_entry_and_comprobante_workflow()
    {
        // Preparar storage fake
        Storage::fake('public');

        // Crear roles necesarios
        Role::firstOrCreate(['name' => 'alumno', 'guard_name' => 'alumno']);
        Role::firstOrCreate(['name' => 'maestro', 'guard_name' => config('auth.defaults.guard', 'web')]);

        // Crear alumno y asignar codigo de barra
        $alumno = Alumno::create([
            'numero_control' => '0001',
            'CURP' => 'CURP123',
            'especialidad' => 1,
            'semestre' => 1,
            'Grupo' => 1,
            'Nombre' => 'Test Alumno',
            'email' => 'alumno@test.local',
            'estatus' => 'activo',
            'remember_token' => null,
        ]);
        $alumno->codigo_barra = 'ABC123';
        $alumno->save();

        // Registrar entrada por codigo de barras
        $resp = $this->postJson(route('asistencias.entrada'), ['codigo_barra' => 'ABC123']);
        $resp->assertStatus(200)->assertJson(['ok' => true]);

        // Subir comprobante como alumno (autenticado)
        $file = UploadedFile::fake()->create('comprobante.pdf', 100);
        $resp2 = $this->actingAs($alumno, 'alumno')->post(route('asistencias.comprobantes.subir'), [
            'alumno_id' => $alumno->id,
            'archivo' => $file,
        ]);
        // Redirección back con success
        $resp2->assertStatus(302);

        // Obtener comprobante creado
        $comp = \App\Models\ComprobanteAsistencia::first();
        $this->assertNotNull($comp);
        Storage::disk('public')->assertExists($comp->archivo);

    // Crear usuario maestro y asignar rol maestro
    $maestro = User::factory()->create();
    $maestro->assignRole('maestro');

        $resp3 = $this->actingAs($maestro)->post(route('maestros.asistencias.comprobantes.revisar', $comp->id), [
            'estado' => 'aprobado',
            'comentario' => 'Ok',
        ]);
        $resp3->assertStatus(302);

        $comp->refresh();
        $this->assertEquals('aprobado', $comp->estado);
    }
}
