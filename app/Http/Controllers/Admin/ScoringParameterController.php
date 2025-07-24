<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScoringParameter; // Import model ScoringParameter
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi unik

class ScoringParameterController extends Controller
{
    // Pastikan user adalah admin
    public function __construct()
    {
        $this->middleware('can:manage-users'); // Menggunakan gate yang sama dengan manajemen user
    }

    /**
     * Display a listing of the resource (Daftar Parameter Scoring).
     */
    public function index()
    {
        $parameters = ScoringParameter::all();
        return view('admin.parameters.index', compact('parameters'));
    }

    /**
     * Show the form for creating a new resource (Form Tambah Parameter).
     */
    public function create()
    {
        $categories = ['UMKM/Pengusaha', 'Pegawai'];
        return view('admin.parameters.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage (Simpan Parameter Baru).
     */
    public function store(Request $request)
    {
        $request->validate([
            'parameter_name' => 'required|string|max:255|unique:scoring_parameters',
            'category' => ['required', Rule::in(['UMKM/Pengusaha', 'Pegawai'])],
            'description' => 'nullable|string',
            'rules_type' => ['required', Rule::in(['range', 'discrete'])], // Tipe aturan: range atau discrete
            'options' => 'required|array|min:1', // Array opsi/rentang
            'options.*.value' => 'nullable|string', // Untuk discrete
            'options.*.min' => 'nullable|numeric', // Untuk range
            'options.*.max' => 'nullable|numeric', // Untuk range
            'options.*.score' => 'required|integer', // Semua opsi/rentang harus punya skor
        ]);

        $rules = [
            'type' => $request->rules_type,
            'options' => [],
        ];

        foreach ($request->options as $option) {
            $item = ['score' => (int)$option['score']];
            if ($request->rules_type === 'discrete') {
                $item['value'] = $option['value'];
            } else { // range
                if (isset($option['min']) && $option['min'] !== null) {
                    $item['min'] = (float)$option['min'];
                }
                if (isset($option['max']) && $option['max'] !== null) {
                    $item['max'] = (float)$option['max'];
                }
            }
            $rules['options'][] = $item;
        }

        ScoringParameter::create([
            'parameter_name' => $request->parameter_name,
            'category' => $request->category,
            'description' => $request->description,
            'rules' => $rules, // Laravel akan otomatis mengkonversi array ini ke JSON
        ]);

        return redirect()->route('admin.parameters.index')->with('success', 'Parameter scoring berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * Tidak terlalu relevan untuk manajemen parameter sederhana.
     */
    public function show(ScoringParameter $parameter)
    {
        return view('admin.parameters.show', compact('parameter'));
    }

    /**
     * Show the form for editing the specified resource (Form Edit Parameter).
     */
    public function edit(ScoringParameter $parameter)
    {
        $categories = ['UMKM/Pengusaha', 'Pegawai'];
        // Pastikan rules diubah kembali ke format yang bisa diedit di form
        $rules_type = $parameter->rules['type'] ?? 'discrete';
        $options = $parameter->rules['options'] ?? [];

        return view('admin.parameters.edit', compact('parameter', 'categories', 'rules_type', 'options'));
    }

    /**
     * Update the specified resource in storage (Update Parameter).
     */
    public function update(Request $request, ScoringParameter $parameter)
    {
        $request->validate([
            'parameter_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('scoring_parameters')->ignore($parameter->id),
            ],
            'category' => ['required', Rule::in(['UMKM/Pengusaha', 'Pegawai'])],
            'description' => 'nullable|string',
            'rules_type' => ['required', Rule::in(['range', 'discrete'])],
            'options' => 'required|array|min:1',
            'options.*.value' => 'nullable|string',
            'options.*.min' => 'nullable|numeric',
            'options.*.max' => 'nullable|numeric',
            'options.*.score' => 'required|integer',
        ]);

        $rules = [
            'type' => $request->rules_type,
            'options' => [],
        ];

        foreach ($request->options as $option) {
            $item = ['score' => (int)$option['score']];
            if ($request->rules_type === 'discrete') {
                $item['value'] = $option['value'];
            } else { // range
                if (isset($option['min']) && $option['min'] !== null) {
                    $item['min'] = (float)$option['min'];
                }
                if (isset($option['max']) && $option['max'] !== null) {
                    $item['max'] = (float)$option['max'];
                }
            }
            $rules['options'][] = $item;
        }

        $parameter->update([
            'parameter_name' => $request->parameter_name,
            'category' => $request->category,
            'description' => $request->description,
            'rules' => $rules,
        ]);

        return redirect()->route('admin.parameters.index')->with('success', 'Parameter scoring berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage (Hapus Parameter).
     */
    public function destroy(ScoringParameter $parameter)
    {
        // Pertimbangkan apakah parameter yang sudah digunakan dalam aplikasi tidak boleh dihapus
        // Untuk saat ini, kita izinkan penghapusan sederhana
        $parameter->delete();
        return redirect()->route('admin.parameters.index')->with('success', 'Parameter scoring berhasil dihapus.');
    }
}