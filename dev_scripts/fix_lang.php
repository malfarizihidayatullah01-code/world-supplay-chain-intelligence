<?php
$directory = new RecursiveDirectoryIterator('resources/views');
$iterator = new RecursiveIteratorIterator($directory);
$regex = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$replacements = [
    'Intelijen Berita Global' => 'Global News Intelligence',
    'Pusat pemantauan berita terkini untuk sektor logistik, perdagangan, dan maritim dunia.' => 'Monitoring center for the latest news in logistics, trade, and global maritime sectors.',
    'Total Berita' => 'Total News',
    'Terakhir diperbarui:' => 'Last updated:',
    'menit lalu' => 'minutes ago',
    'Sync Berita' => 'Sync News',
    'Cari kata kunci berita...' => 'Search news keywords...',
    'Semua Kategori' => 'All Categories',
    'Semua Negara' => 'All Countries',
    'Memuat Berita...' => 'Loading News...',
    'Ringkasan Data' => 'Data Summary',
    'Distribusi Kategori' => 'Category Distribution',
    
    'Mesin Perbandingan Negara' => 'Country Comparison Engine',
    'Negara A' => 'Country A',
    'Negara B' => 'Country B',
    'Silakan pilih kedua negara terlebih dahulu.' => 'Please select both countries first.',
    'Harap pilih dua negara yang berbeda.' => 'Please select two different countries.',
    
    'Cari negara...' => 'Search country...',
    'Tidak ada negara ditemukan' => 'No country found',
    'negara lainnya, ketik lebih spesifik' => 'other countries, type more specifically',
    
    'Cari Pelabuhan' => 'Search Port',
    'Lihat Detail' => 'View Details',
    
    'Tambah Artikel Baru' => 'Add New Article',
    'Kembali ke Daftar' => 'Back to List',
    'Simpan Artikel' => 'Save Article',
    'Tambah Pelabuhan Baru' => 'Add New Port',
    'Simpan Perubahan' => 'Save Changes',
    'Batal' => 'Cancel',
    'Cari...' => 'Search...',
    '>NEGARA<' => '>COUNTRY<',
    '\'Negara\'' => '\'Country\'',
    '>Negara<' => '>Country<'
];

foreach ($regex as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;
    
    foreach ($replacements as $id => $en) {
        $content = str_replace($id, $en, $content);
    }
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated $path\n";
    }
}
echo "Done.\n";
