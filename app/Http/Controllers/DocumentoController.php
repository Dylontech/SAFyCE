<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentoController extends Controller
{
    public function myDocuments()
    {
        $alumno = Auth::guard('alumno')->user();
        $documentos = Documento::where('alumno_id', $alumno->id)->get();
        return view('documentos.my_documents', compact('documentos'));
    }

    public function create()
    {
        $types = Documento::requiredTypes();
        return view('documentos.upload', compact('types'));
    }

    public function store(Request $request)
    {
        $alumno = Auth::guard('alumno')->user();

        $rules = [];
        foreach (Documento::requiredTypes() as $type) {
            $rules[$type] = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240';
        }

        $validated = $request->validate($rules);

        foreach (Documento::requiredTypes() as $type) {
            if ($request->hasFile($type)) {
                $file = $request->file($type);
                $path = $file->store('documentos/' . $alumno->id, 'public');

                Documento::create([
                    'alumno_id' => $alumno->id,
                    'document_type' => $type,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'status' => Documento::STATUS_PENDING
                ]);
            }
        }

        return redirect()->route('documentos.my')->with('success', 'Documentos subidos correctamente.');
    }

    // For control escolar: list all pending documents
    public function index()
    {
        $documentos = Documento::orderBy('status')->orderBy('created_at', 'desc')->get();
        return view('documentos.index', compact('documentos'));
    }

    public function download(Documento $documento)
    {
        // Permitir descarga sólo si es el alumno propietario o un usuario autenticado con rol de control escolar
        $alumno = Auth::guard('alumno')->user();
        $webUser = Auth::user();

        $isOwner = $alumno && $alumno->id == $documento->alumno_id;
        $isControl = false;
        if ($webUser && method_exists($webUser, 'hasRole')) {
            $isControl = $webUser->hasRole('controlescolar') || $webUser->hasRole('control_escolar') || $webUser->hasRole('admin');
        }

        if (! $isOwner && ! $isControl) {
            abort(403, 'No tienes permiso para descargar este archivo.');
        }

        if (!Storage::disk('public')->exists($documento->file_path)) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        return Storage::disk('public')->download($documento->file_path, $documento->original_name ?: basename($documento->file_path));
    }

    public function review(Request $request, Documento $documento)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'comment' => 'nullable|string'
        ]);

        $documento->status = $request->action === 'approve' ? Documento::STATUS_APPROVED : Documento::STATUS_REJECTED;
        $documento->reviewer_comment = $request->comment;
        $documento->reviewed_by = Auth::id();
        $documento->reviewed_at = now();
        $documento->save();

        return redirect()->back()->with('success', 'Documento revisado.');
    }
}
