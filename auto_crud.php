<?php

$basePath = __DIR__;
$models = [
    'User' => ['username', 'role', 'status'],
    'Pelatih' => ['nama_pelatih', 'bidang', 'no_hp', 'alamat'],
    'Sanggar' => ['nama_sanggar', 'alamat', 'email', 'no_hp', 'visi', 'misi'],
    'ProgramKelas' => ['nama_program', 'kategori', 'deskripsi', 'biaya', 'status'],
    'InformasiBerita' => ['judul', 'isi', 'tanggal_publish', 'foto'],
    'Galeri' => ['judul', 'foto', 'keterangan'],
    'Pembayaran' => ['tanggal_bayar', 'bulan', 'jumlah', 'status'],
    'Pendaftaran' => ['nama_calon', 'tanggal_daftar', 'no_hp', 'alamat', 'status'],
    'LaporanKeuangan' => ['periode', 'total_pemasukan', 'total_pengeluaran', 'keterangan'],
    'Kontak' => ['nama_pengirim', 'email', 'pesan', 'tanggal_kirim'],
    'Prestasi' => ['nama_event', 'tahun', 'tingkat', 'keterangan'],
];

foreach ($models as $model => $fields) {
    // 1. Controller
    $controllerName = $model . 'Controller';
    $viewFolder = strtolower($model);
    $variable = strtolower($model);
    $primaryKey = "id_" . (($model == 'ProgramKelas') ? 'program' : 
                   (($model == 'InformasiBerita') ? 'informasi' : 
                   (($model == 'LaporanKeuangan') ? 'laporan' : strtolower($model))));
    if ($model == 'User') $primaryKey = 'id_user';

    $controllerCode = "<?php\n\nnamespace App\Http\Controllers;\n\nuse App\Models\\$model;\nuse Illuminate\Http\Request;\n\nclass $controllerName extends Controller\n{\n";
    $controllerCode .= "    public function index() { \n        $$variable = $model::paginate(10);\n        return view('$viewFolder.index', compact('$variable'));\n    }\n";
    $controllerCode .= "    public function create() { \n        return view('$viewFolder.create');\n    }\n";
    $controllerCode .= "    public function store(Request \$request) { \n        $model::create(\$request->all());\n        return redirect()->route('$viewFolder.index')->with('success', 'Data berhasil disimpan.');\n    }\n";
    $controllerCode .= "    public function edit(\$id) { \n        \$data = $model::where('$primaryKey', \$id)->firstOrFail();\n        return view('$viewFolder.edit', compact('data'));\n    }\n";
    $controllerCode .= "    public function update(Request \$request, \$id) { \n        \$data = $model::where('$primaryKey', \$id)->firstOrFail();\n        \$data->update(\$request->all());\n        return redirect()->route('$viewFolder.index')->with('success', 'Data berhasil diubah.');\n    }\n";
    $controllerCode .= "    public function destroy(\$id) { \n        $model::where('$primaryKey', \$id)->delete();\n        return redirect()->route('$viewFolder.index')->with('success', 'Data berhasil dihapus.');\n    }\n";
    $controllerCode .= "}\n";

    file_put_contents("$basePath/app/Http/Controllers/$controllerName.php", $controllerCode);

    // 2. Views
    $viewPath = "$basePath/resources/views/$viewFolder";
    if (!is_dir($viewPath)) {
        mkdir($viewPath, 0755, true);
    }

    // index.blade.php
    $indexCode = "@extends('layouts.admin')\n@section('content')\n<div class='card shadow-sm border-0'>\n<div class='card-header bg-white pt-4 d-flex justify-content-between'><h5>Kelola $model</h5><a href=\"{{ route('$viewFolder.create') }}\" class='btn btn-primary btn-sm'>Tambah Data</a></div>\n<div class='card-body'>\n";
    $indexCode .= "<table class='table table-hover'><thead><tr>\n";
    foreach($fields as $f) { $indexCode .= "<th>" . ucfirst(str_replace('_', ' ', $f)) . "</th>"; }
    $indexCode .= "<th>Aksi</th></tr></thead><tbody>\n";
    $indexCode .= "@foreach($$variable as \$item)\n<tr>\n";
    foreach($fields as $f) { $indexCode .= "<td>{{ \$item->$f }}</td>"; }
    $indexCode .= "<td><a href=\"{{ route('$viewFolder.edit', \$item->$primaryKey) }}\" class='btn btn-sm btn-outline-secondary'>Edit</a> \n";
    $indexCode .= "<form action=\"{{ route('$viewFolder.destroy', \$item->$primaryKey) }}\" method='POST' class='d-inline' onsubmit=\"return confirm('Hapus?');\">@csrf @method('DELETE') <button class='btn btn-sm btn-outline-danger'>Hapus</button></form></td>\n";
    $indexCode .= "</tr>\n@endforeach\n</tbody></table>\n</div></div>\n@endsection";
    file_put_contents("$viewPath/index.blade.php", $indexCode);

    // create.blade.php
    $createCode = "@extends('layouts.admin')\n@section('content')\n<div class='card shadow-sm border-0'><div class='card-header bg-white pt-4'><h5>Tambah $model</h5></div><div class='card-body'>\n";
    $createCode .= "<form action=\"{{ route('$viewFolder.store') }}\" method='POST'>@csrf\n";
    foreach($fields as $f) {
        $createCode .= "<div class='mb-3'><label>" . ucfirst(str_replace('_', ' ', $f)) . "</label><input type='text' name='$f' class='form-control'></div>\n";
    }
    $createCode .= "<button class='btn btn-success'>Simpan</button>\n<a href=\"{{ route('$viewFolder.index') }}\" class='btn btn-secondary'>Batal</a>\n</form>\n</div></div>\n@endsection";
    file_put_contents("$viewPath/create.blade.php", $createCode);

    // edit.blade.php
    $editCode = "@extends('layouts.admin')\n@section('content')\n<div class='card shadow-sm border-0'><div class='card-header bg-white pt-4'><h5>Edit $model</h5></div><div class='card-body'>\n";
    $editCode .= "<form action=\"{{ route('$viewFolder.update', \$data->$primaryKey) }}\" method='POST'>@csrf @method('PUT')\n";
    foreach($fields as $f) {
        $editCode .= "<div class='mb-3'><label>" . ucfirst(str_replace('_', ' ', $f)) . "</label><input type='text' name='$f' value=\"{{ \$data->$f }}\" class='form-control'></div>\n";
    }
    $editCode .= "<button class='btn btn-primary'>Update</button>\n<a href=\"{{ route('$viewFolder.index') }}\" class='btn btn-secondary'>Batal</a>\n</form>\n</div></div>\n@endsection";
    file_put_contents("$viewPath/edit.blade.php", $editCode);
}
echo "CRUD Generated Successfully!\n";

// Generate routes stub
$routeCode = "";
foreach ($models as $model => $fields) {
    $viewFolder = strtolower($model);
    $routeCode .= "Route::resource('$viewFolder', App\Http\Controllers\\{$model}Controller::class);\n";
}
file_put_contents("$basePath/routes/web_crud_routes.txt", $routeCode);
