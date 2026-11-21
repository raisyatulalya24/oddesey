<?php
namespace Database\Seeders;
use App\Models\{Subject, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            // Di situs publik JANGAN membuat akun demo berpassword 'password'. Admin dibuat dari ADMIN_EMAIL / ADMIN_PASSWORD.
            if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
                User::create(['name' => 'Admin Oddesey', 'email' => env('ADMIN_EMAIL'), 'password' => env('ADMIN_PASSWORD'), 'role' => 'admin']);
            }
        } else {
            User::create(['name' => 'Admin Oddesey', 'email' => 'admin@oddesey.test', 'password' => 'password', 'role' => 'admin']);
            User::create(['name' => 'Siswa Demo', 'email' => 'siswa@oddesey.test', 'password' => 'password', 'role' => 'student']);
        }

        $subjects = [];
        foreach ([['Biologi', 'leaf', 'Eksplorasi makhluk hidup, ekosistem, dan keajaiban sel.'], ['Fisika', 'atom', 'Hukum alam semesta, energi, dan rahasia gerak.'],
                  ['Kimia', 'flask', 'Reaksi, molekul, dan sifat zat di sekitar kita.'], ['Astronomi', 'planet', 'Jelajahi planet, bintang, dan misteri luar angkasa.'],
                  ['Matematika', 'calc', 'Logika, angka, dan pola yang membentuk sains.']] as [$n, $i, $d]) {
            $subjects[$n] = Subject::create(['name' => $n, 'slug' => Str::slug($n), 'icon' => $i, 'description' => $d]);
        }

        // [bidang, judul, ringkasan, level, tipe simulasi|null, parameter, pertanyaan refleksi, isi]
        $items = [
            // ---------- BIOLOGI ----------
            ['Biologi', 'Fotosintesis', 'Bagaimana tumbuhan mengubah cahaya menjadi energi.', 'dasar', 'photosynthesis',
                [['light', 'Intensitas Cahaya', '%', 0, 100, 60, 5], ['co2', 'Kadar CO₂', '%', 0, 100, 50, 5]],
                ['Apa yang terjadi pada laju fotosintesis saat cahaya diperbesar tetapi CO₂ tetap rendah?', 'Mengapa faktor yang paling sedikit menjadi pembatas laju fotosintesis?'],
                "Fotosintesis adalah proses ketika tumbuhan, alga, dan sebagian bakteri mengubah energi cahaya menjadi energi kimia berupa glukosa. Reaksi umumnya: 6CO₂ + 6H₂O + cahaya → C₆H₁₂O₆ + 6O₂.\n\nProses ini berlangsung di kloroplas. Klorofil menyerap cahaya terutama merah dan biru, lalu memantulkan cahaya hijau, sehingga daun tampak hijau.\n\nLaju fotosintesis dipengaruhi intensitas cahaya, kadar karbon dioksida, dan suhu. Bila salah satu faktor sangat rendah, faktor itu menjadi pembatas, sehingga menambah faktor lain tidak lagi menaikkan lajunya."],
            ['Biologi', 'Mitokondria dan Energi Sel', 'Pembangkit tenaga di dalam sel.', 'dasar', null, [], [],
                "Mitokondria disebut pembangkit tenaga sel karena menghasilkan sebagian besar ATP, molekul pembawa energi sel.\n\nRespirasi aerob berlangsung bertahap: glikolisis di sitoplasma, siklus Krebs di matriks mitokondria, dan transpor elektron di membran dalam (krista). Satu molekul glukosa dapat menghasilkan sekitar 30–32 ATP.\n\nMitokondria memiliki DNA sendiri dan membran ganda. Hal ini mendukung teori endosimbiosis, yaitu mitokondria berasal dari bakteri purba yang hidup di dalam sel inang."],
            ['Biologi', 'Struktur dan Fungsi Sel', 'Unit terkecil kehidupan dan organel-organelnya.', 'dasar', null, [], [],
                "Sel adalah unit struktural dan fungsional terkecil makhluk hidup. Ada dua tipe: prokariotik (tanpa inti sejati, misalnya bakteri) dan eukariotik (berinti sejati, misalnya sel hewan dan tumbuhan).\n\nOrganel penting: inti sel menyimpan DNA, ribosom membuat protein, retikulum endoplasma dan badan Golgi memproses serta mengirim protein, dan lisosom mencerna sisa-sisa sel.\n\nSel tumbuhan memiliki dinding sel, kloroplas, dan vakuola besar, sedangkan sel hewan tidak memiliki dinding sel maupun kloroplas."],
            ['Biologi', 'DNA dan Pewarisan Sifat', 'Kode genetik dan hukum Mendel.', 'menengah', null, [], [],
                "DNA (asam deoksiribonukleat) menyimpan informasi genetik dalam urutan empat basa: adenin (A), timin (T), guanin (G), dan sitosin (C). A berpasangan dengan T, sedangkan G berpasangan dengan C.\n\nGen adalah potongan DNA yang menyandi protein. Informasi mengalir dari DNA ke RNA (transkripsi), lalu ke protein (translasi).\n\nGregor Mendel menunjukkan bahwa sifat diwariskan lewat pasangan alel. Pada persilangan dua individu heterozigot (Aa × Aa), perbandingan fenotipe dominan : resesif adalah 3 : 1."],
            ['Biologi', 'Ekosistem dan Rantai Makanan', 'Aliran energi antar makhluk hidup.', 'dasar', null, [], [],
                "Ekosistem adalah hubungan timbal balik antara makhluk hidup (komponen biotik) dan lingkungannya (komponen abiotik).\n\nProdusen (tumbuhan) membuat makanan sendiri, konsumen memakan organisme lain, dan pengurai (dekomposer) menguraikan sisa makhluk hidup menjadi zat hara.\n\nEnergi mengalir satu arah dan hanya sekitar 10% yang diteruskan ke tingkat trofik berikutnya, sehingga rantai makanan jarang lebih dari 4–5 tingkat."],
            // ---------- FISIKA ----------
            ['Fisika', 'Gerak dan Gaya', 'Gerak parabola: gerak lurus dan percepatan gravitasi.', 'menengah', 'projectile',
                [['v', 'Kecepatan Awal', 'm/s', 5, 50, 25, 1], ['angle', 'Sudut Elevasi', '°', 5, 85, 45, 1], ['g', 'Gravitasi', 'm/s²', 1.6, 25, 9.8, 0.1]],
                ['Pada sudut berapa jangkauan paling jauh? Apakah sesuai dugaanmu?', 'Apa yang berubah jika gravitasi diperkecil seperti di Bulan?'],
                "Gerak parabola adalah perpaduan gerak lurus beraturan pada sumbu horizontal dan gerak lurus berubah beraturan pada sumbu vertikal akibat gravitasi.\n\nDengan kecepatan awal v dan sudut θ: v_x = v cos θ dan v_y = v sin θ. Waktu di udara t = 2v sin θ / g, jangkauan R = v² sin 2θ / g, dan tinggi maksimum H = v² sin² θ / 2g.\n\nTanpa hambatan udara, jangkauan terjauh dicapai pada sudut 45°. Buktikan sendiri di simulasi."],
            ['Fisika', 'Gelombang dan Frekuensi', 'Hubungan amplitudo, frekuensi, dan periode.', 'dasar', 'wave',
                [['amp', 'Amplitudo', 'satuan', 1, 10, 5, 1], ['freq', 'Frekuensi', 'Hz', 0.5, 5, 1.5, 0.1]],
                ['Apa hubungan frekuensi dengan periode?', 'Apa efek memperbesar amplitudo pada gelombang?'],
                "Gelombang memindahkan energi tanpa memindahkan materi. Gelombang transversal bergetar tegak lurus arah rambat, sedangkan gelombang longitudinal bergetar sejajar arah rambat.\n\nFrekuensi f (Hz) adalah jumlah getaran per detik, dan periode T = 1/f. Cepat rambat v = λ × f, dengan λ adalah panjang gelombang.\n\nAmplitudo menentukan besar energi gelombang, sedangkan frekuensi menentukan tinggi rendah nada pada bunyi."],
            ['Fisika', 'Hukum Newton', 'Tiga hukum dasar gerak benda.', 'menengah', null, [], [],
                "Hukum I Newton: benda tetap diam atau bergerak lurus beraturan bila resultan gaya nol (kelembaman).\n\nHukum II: percepatan benda sebanding dengan resultan gaya dan berbanding terbalik dengan massa, F = m × a. Gaya 10 N pada benda 2 kg menghasilkan percepatan 5 m/s².\n\nHukum III: jika benda A memberi gaya aksi pada B, maka B memberi gaya reaksi pada A yang sama besar dan berlawanan arah. Pasangan aksi-reaksi bekerja pada benda yang berbeda."],
            ['Fisika', 'Energi dan Usaha', 'Usaha, energi kinetik, dan energi potensial.', 'menengah', null, [], [],
                "Usaha adalah gaya yang menyebabkan perpindahan: W = F × s × cos θ, dengan satuan joule (J).\n\nEnergi kinetik EK = ½mv² dan energi potensial gravitasi EP = mgh. Pada sistem tanpa gesekan, energi mekanik (EK + EP) tetap. Inilah hukum kekekalan energi mekanik.\n\nContoh: bola 1 kg yang dijatuhkan dari ketinggian 5 m (g = 10 m/s²) memiliki EP 50 J, dan sesaat sebelum menyentuh tanah kecepatannya sekitar 10 m/s."],
            ['Fisika', 'Listrik Dasar', 'Arus, tegangan, hambatan, dan Hukum Ohm.', 'dasar', null, [], [],
                "Arus listrik adalah aliran muatan, diukur dalam ampere (A). Tegangan (V) adalah beda potensial yang mendorong arus, sedangkan hambatan (R) dalam ohm (Ω) menahan arus.\n\nHukum Ohm: V = I × R. Baterai 6 V pada hambatan 3 Ω menghasilkan arus 2 A.\n\nPada rangkaian seri, hambatan total dijumlahkan (R = R₁ + R₂) dan arus sama di setiap komponen. Pada rangkaian paralel, tegangan sama dan 1/R = 1/R₁ + 1/R₂."],
            // ---------- KIMIA ----------
            ['Kimia', 'Laju Reaksi', 'Faktor yang mempercepat reaksi kimia.', 'menengah', 'reaction',
                [['temp', 'Suhu', '°C', 10, 80, 25, 1], ['conc', 'Konsentrasi', '%', 10, 100, 50, 5]],
                ['Bagaimana suhu memengaruhi laju reaksi?', 'Jelaskan peran konsentrasi menggunakan teori tumbukan.'],
                "Laju reaksi menyatakan seberapa cepat pereaksi berkurang atau hasil reaksi bertambah per satuan waktu.\n\nMenurut teori tumbukan, reaksi terjadi bila partikel bertumbukan dengan energi minimal (energi aktivasi) dan arah yang tepat. Menaikkan suhu membuat partikel bergerak lebih cepat, dan menaikkan konsentrasi membuat tumbukan lebih sering.\n\nAturan praktis: setiap kenaikan suhu 10 °C, laju reaksi sering menjadi sekitar dua kali lipat. Katalis mempercepat reaksi dengan menurunkan energi aktivasi tanpa ikut habis bereaksi."],
            ['Kimia', 'Struktur Atom', 'Proton, neutron, dan elektron.', 'dasar', null, [], [],
                "Atom terdiri atas inti (proton bermuatan positif dan neutron tak bermuatan) dan elektron bermuatan negatif yang mengelilingi inti.\n\nNomor atom = jumlah proton, nomor massa = proton + neutron. Atom netral memiliki jumlah elektron yang sama dengan proton. Isotop adalah atom unsur yang sama dengan jumlah neutron berbeda.\n\nElektron menempati kulit-kulit energi: kulit pertama maksimal 2 elektron, kulit kedua 8. Elektron di kulit terluar (elektron valensi) menentukan sifat kimia unsur."],
            ['Kimia', 'Tabel Periodik', 'Pola sifat unsur dalam golongan dan periode.', 'dasar', null, [], [],
                "Tabel periodik menyusun unsur berdasarkan kenaikan nomor atom. Lajur vertikal disebut golongan, lajur horizontal disebut periode.\n\nUnsur segolongan memiliki jumlah elektron valensi yang sama sehingga sifatnya mirip. Contohnya logam alkali (golongan 1) bereaksi hebat dengan air, sedangkan gas mulia (golongan 18) sangat stabil.\n\nDalam satu periode dari kiri ke kanan, jari-jari atom cenderung mengecil dan keelektronegatifan meningkat."],
            ['Kimia', 'Ikatan Kimia', 'Ikatan ion dan kovalen.', 'menengah', null, [], [],
                "Atom berikatan agar mencapai konfigurasi elektron yang stabil seperti gas mulia (aturan oktet).\n\nIkatan ion terjadi karena serah-terima elektron antara logam dan nonlogam, misalnya NaCl. Ikatan kovalen terjadi karena pemakaian bersama pasangan elektron antar nonlogam, misalnya H₂O dan CO₂.\n\nSenyawa ion umumnya berwujud padat bertitik leleh tinggi dan menghantarkan listrik saat larut atau lebur, sedangkan senyawa kovalen umumnya bertitik leleh lebih rendah."],
            ['Kimia', 'Asam dan Basa', 'Skala pH dan reaksi netralisasi.', 'menengah', null, [], [],
                "Menurut Arrhenius, asam melepas ion H⁺ dalam air dan basa melepas ion OH⁻. Contoh: HCl adalah asam, NaOH adalah basa.\n\nDerajat keasaman dinyatakan dengan pH = −log[H⁺]. Pada skala 0–14: pH < 7 asam, pH = 7 netral, pH > 7 basa. Setiap turun satu satuan pH, keasaman naik 10 kali.\n\nReaksi asam dengan basa disebut netralisasi dan menghasilkan garam dan air, misalnya HCl + NaOH → NaCl + H₂O."],
            // ---------- ASTRONOMI ----------
            ['Astronomi', 'Orbit Planet', 'Hukum Kepler dan periode orbit.', 'menengah', 'orbit',
                [['radius', 'Jarak dari Bintang', 'AU', 0.4, 5, 1, 0.1], ['mass', 'Massa Bintang', 'M☉', 0.5, 2, 1, 0.1]],
                ['Apa yang terjadi pada periode saat planet berada lebih jauh?', 'Bagaimana massa bintang memengaruhi kecepatan orbit?'],
                "Planet mengorbit bintang karena gaya gravitasi. Johannes Kepler merumuskan tiga hukum gerak planet.\n\nHukum III Kepler: kuadrat periode orbit sebanding dengan pangkat tiga jarak rata-rata. Dalam satuan tahun dan AU untuk bintang bermassa Matahari, T² = a³. Bumi (a = 1 AU) memiliki T = 1 tahun, sedangkan Mars (a ≈ 1,52 AU) sekitar 1,88 tahun.\n\nSemakin jauh planet dari bintang, semakin panjang periodenya dan semakin lambat kecepatan orbitnya."],
            ['Astronomi', 'Tata Surya', 'Matahari, planet, dan benda langit lainnya.', 'dasar', null, [], [],
                "Tata Surya terdiri dari Matahari dan benda-benda yang mengorbitnya: delapan planet, planet kerdil, satelit, asteroid, dan komet.\n\nPlanet dalam berbatu (Merkurius, Venus, Bumi, Mars). Planet luar berupa raksasa gas (Jupiter, Saturnus) dan raksasa es (Uranus, Neptunus).\n\nMatahari mengandung sekitar 99,8% massa Tata Surya. Cahayanya butuh sekitar 8 menit 20 detik untuk sampai ke Bumi."],
            ['Astronomi', 'Bintang dan Siklus Hidupnya', 'Dari nebula hingga katai putih.', 'menengah', null, [], [],
                "Bintang adalah bola gas panas raksasa yang menghasilkan energi lewat fusi nuklir: inti hidrogen bergabung menjadi helium.\n\nBintang lahir dari awan gas dan debu (nebula). Sebagian besar hidupnya dihabiskan pada deret utama, seperti Matahari saat ini. Kelak Matahari menjadi raksasa merah, melepas lapisan luarnya, lalu menyusut menjadi katai putih.\n\nBintang yang jauh lebih masif mengakhiri hidup sebagai supernova dan meninggalkan bintang neutron atau lubang hitam."],
            ['Astronomi', 'Gerhana dan Fase Bulan', 'Mengapa Bulan berubah bentuk dan gerhana terjadi.', 'dasar', null, [], [],
                "Bulan tidak memancarkan cahaya sendiri. Kita melihatnya karena Bulan memantulkan cahaya Matahari. Fase Bulan terjadi karena posisi Bulan terhadap Bumi dan Matahari berubah, dengan siklus sekitar 29,5 hari.\n\nGerhana Matahari terjadi saat Bulan berada di antara Matahari dan Bumi (bulan baru). Gerhana Bulan terjadi saat Bumi berada di antara Matahari dan Bulan (purnama).\n\nGerhana tidak terjadi setiap bulan karena bidang orbit Bulan miring sekitar 5° terhadap bidang orbit Bumi."],
            ['Astronomi', 'Galaksi dan Alam Semesta', 'Bima Sakti, Andromeda, dan Big Bang.', 'menengah', null, [], [],
                "Galaksi adalah kumpulan miliaran bintang, gas, debu, dan materi gelap yang terikat oleh gravitasi. Tata Surya berada di galaksi Bima Sakti, galaksi spiral dengan diameter sekitar 100.000 tahun cahaya.\n\nGalaksi besar terdekat adalah Andromeda, sekitar 2,5 juta tahun cahaya dari kita.\n\nPengamatan Edwin Hubble menunjukkan galaksi-galaksi saling menjauh, yang berarti alam semesta mengembang. Menurut teori Big Bang, alam semesta bermula sekitar 13,8 miliar tahun lalu."],
            // ---------- MATEMATIKA ----------
            ['Matematika', 'Fungsi Sinus', 'Fungsi periodik pada gelombang dan putaran.', 'menengah', 'wave',
                [['amp', 'Amplitudo', 'satuan', 1, 10, 5, 1], ['freq', 'Frekuensi', 'Hz', 0.5, 5, 1.5, 0.1]],
                ['Bagaimana amplitudo mengubah bentuk grafik sinus?', 'Apa hubungan frekuensi dengan periode grafik?'],
                "Fungsi sinus y = A sin(ωx) menggambarkan gejala periodik seperti gelombang dan putaran. A adalah amplitudo (tinggi puncak) dan periode T = 2π/ω.\n\nNilai sin berada pada rentang −1 sampai 1. Beberapa nilai penting: sin 0° = 0, sin 30° = 1/2, sin 90° = 1, sin 180° = 0.\n\nMemperbesar amplitudo membuat grafik lebih tinggi, sedangkan memperbesar frekuensi membuat gelombang lebih rapat. Coba ubah keduanya di simulasi."],
            ['Matematika', 'Aljabar Dasar', 'Variabel dan persamaan linear.', 'dasar', null, [], [],
                "Aljabar memakai huruf (variabel) untuk mewakili bilangan yang belum diketahui. Persamaan linear satu variabel berbentuk ax + b = c.\n\nUntuk menyelesaikannya, lakukan operasi yang sama pada kedua ruas. Contoh: 3x + 5 = 20 → 3x = 15 → x = 5.\n\nBentuk penting lain: (a + b)² = a² + 2ab + b² dan a² − b² = (a + b)(a − b)."],
            ['Matematika', 'Geometri Bangun Datar', 'Keliling, luas, dan teorema Pythagoras.', 'dasar', null, [], [],
                "Geometri mempelajari bentuk, ukuran, dan posisi. Keliling adalah panjang sisi-sisi pembatas, sedangkan luas adalah besar daerah di dalamnya.\n\nRumus penting: luas persegi panjang = p × l, luas segitiga = ½ × alas × tinggi, luas lingkaran = πr², dan keliling lingkaran = 2πr.\n\nTeorema Pythagoras pada segitiga siku-siku: a² + b² = c², dengan c sisi miring. Segitiga dengan sisi 3, 4, dan 5 adalah contoh klasiknya."],
            ['Matematika', 'Statistika Dasar', 'Rata-rata, median, dan modus.', 'dasar', null, [], [],
                "Statistika mengumpulkan, menyajikan, dan menafsirkan data. Ukuran pemusatan data ada tiga: rata-rata (jumlah data dibagi banyaknya), median (nilai tengah setelah data diurutkan), dan modus (nilai yang paling sering muncul).\n\nContoh data 4, 6, 6, 8, 11: rata-rata 7, median 6, modus 6.\n\nData dapat disajikan sebagai tabel, diagram batang, diagram garis, atau diagram lingkaran, sesuai tujuan penyajiannya."],
            ['Matematika', 'Peluang', 'Mengukur kemungkinan suatu kejadian.', 'menengah', null, [], [],
                "Peluang menyatakan seberapa besar kemungkinan suatu kejadian: P(A) = banyak hasil yang diinginkan ÷ banyak seluruh hasil yang mungkin. Nilainya antara 0 (mustahil) dan 1 (pasti).\n\nPada pelemparan satu dadu, peluang muncul angka genap adalah 3/6 = 1/2. Pada dua koin, peluang keduanya angka adalah 1/2 × 1/2 = 1/4 karena kedua kejadian saling bebas.\n\nPeluang kejadian komplemen: P(A') = 1 − P(A)."],
        ];

        foreach ($items as [$s, $title, $sum, $lvl, $type, $params, $qs, $content]) {
            $m = $subjects[$s]->materials()->create(['title' => $title, 'slug' => Str::slug($title), 'summary' => $sum, 'level' => $lvl, 'content' => $content]);
            if (!$type) continue;
            $sim = $m->simulations()->create(['title' => "Simulasi $title", 'type' => $type, 'description' => "Atur parameter lalu jalankan simulasi $title."]);
            foreach ($params as [$n, $l, $u, $min, $max, $def, $st]) $sim->parameters()->create(['name' => $n, 'label' => $l, 'unit' => $u, 'min_value' => $min, 'max_value' => $max, 'default_value' => $def, 'step' => $st]);
            foreach ($qs as $i => $q) $sim->questions()->create(['question' => $q, 'sort_order' => $i + 1]);
        }
        // ---- 50 simulasi tambahan: [tipe, judul, deskripsi, [parameter nama|label|satuan|min|max|bawaan|langkah], pertanyaan refleksi] ----
        $extra = [
            'Fotosintesis' => [
                ['transp', 'Transpirasi Daun', 'Atur suhu, kelembapan, dan angin untuk melihat laju penguapan air lewat stomata.', ['temp|Suhu|°C|10|45|28|1', 'humidity|Kelembapan Udara|%|10|100|60|5', 'wind|Kecepatan Angin|m/s|0|20|3|1'], 'Mengapa tumbuhan di daerah panas dan kering menutup stomata pada siang hari?'],
                ['enzyme', 'Aktivitas Enzim', 'Cari suhu dan pH optimum tempat enzim bekerja paling baik.', ['temp|Suhu|°C|0|80|37|1', 'ph|pH||1|13|7|0.5'], 'Apa yang terjadi pada aktivitas enzim saat suhu jauh di atas optimum, dan mengapa?'],
            ],
            'Mitokondria dan Energi Sel' => [
                ['respiration', 'Respirasi dan Detak Jantung', 'Lihat bagaimana intensitas olahraga menaikkan kebutuhan energi tubuh.', ['intensity|Intensitas Olahraga|/10|1|10|3|1', 'age|Usia|tahun|15|60|20|1'], 'Mengapa detak jantung dan laju napas naik saat berolahraga? Kaitkan dengan ATP.'],
            ],
            'Struktur dan Fungsi Sel' => [
                ['osmosis', 'Osmosis pada Sel', 'Ubah konsentrasi larutan di luar sel dan amati air masuk atau keluar.', ['ext|Konsentrasi Larutan Luar|%|0.1|2|0.9|0.1'], 'Apa yang terjadi pada sel hewan jika ditaruh di air tawar? Jelaskan arah perpindahan air.'],
                ['cell', 'Rasio Luas Permukaan terhadap Volume', 'Bandingkan sel kecil dan besar untuk memahami mengapa sel berukuran mikroskopis.', ['r|Jari-jari Sel|µm|1|50|10|1'], 'Mengapa sel yang terlalu besar sulit memenuhi kebutuhannya?'],
            ],
            'DNA dan Pewarisan Sifat' => [
                ['dna', 'Heliks DNA dan Suhu Leleh', 'Atur panjang DNA dan kandungan G–C untuk melihat suhu lelehnya.', ['len|Panjang DNA|bp|20|200|50|5', 'gc|Kandungan G–C|%|20|80|50|5'], 'Mengapa DNA dengan lebih banyak pasangan G–C lebih sulit dipisahkan?'],
                ['hardy', 'Hardy-Weinberg', 'Hitung proporsi genotipe dalam populasi dari frekuensi alel.', ['p|Frekuensi Alel A (p)||0.05|0.95|0.5|0.05'], 'Jika p = 0,5, berapa persen populasi yang heterozigot? Mengapa nilainya maksimum di sana?'],
            ],
            'Ekosistem dan Rantai Makanan' => [
                ['logistic', 'Pertumbuhan Populasi', 'Amati populasi tumbuh cepat lalu melambat saat mendekati daya dukung.', ['r|Laju Pertumbuhan||0.1|1|0.4|0.05', 'K|Daya Dukung|ekor|100|1000|500|50', 'N0|Populasi Awal|ekor|10|100|20|5'], 'Mengapa pertumbuhan populasi melambat ketika mendekati daya dukung lingkungan?'],
                ['food', 'Aliran Energi Rantai Makanan', 'Lihat berapa energi yang tersisa di setiap tingkat trofik.', ['energy|Energi Produsen|kJ|1000|20000|10000|500', 'eff|Efisiensi Transfer|%|5|20|10|1'], 'Mengapa rantai makanan jarang lebih dari lima tingkat?'],
                ['predprey', 'Predator dan Mangsa', 'Amati populasi mangsa dan predator naik turun bergantian.', ['a|Laju Pertumbuhan Mangsa||0.4|1.2|0.8|0.05', 'b|Laju Pemangsaan||0.01|0.05|0.03|0.005', 'd|Kematian Predator||0.3|1|0.6|0.05'], 'Mengapa puncak populasi predator selalu muncul setelah puncak populasi mangsa?'],
            ],
            'Gerak dan Gaya' => [
                ['freefall', 'Jatuh Bebas', 'Jatuhkan benda dari berbagai ketinggian dan gravitasi.', ['h|Ketinggian|m|5|100|20|5', 'g|Gravitasi|m/s²|1.6|25|9.8|0.1'], 'Jika gravitasi diperkecil seperti di Bulan, bagaimana waktu jatuhnya berubah?'],
                ['circular', 'Gerak Melingkar', 'Ubah jari-jari dan kecepatan untuk melihat gaya sentripetal.', ['r|Jari-jari|m|0.5|5|2|0.5', 'v|Kecepatan|m/s|1|20|6|1', 'm|Massa|kg|0.1|5|1|0.1'], 'Apa yang terjadi pada gaya sentripetal jika kecepatan digandakan?'],
            ],
            'Gelombang dan Frekuensi' => [
                ['pendulum', 'Bandul Sederhana', 'Cari tahu faktor yang memengaruhi periode ayunan bandul.', ['L|Panjang Tali|m|0.2|3|1|0.1', 'g|Gravitasi|m/s²|1.6|25|9.8|0.1', 'a0|Sudut Awal|°|5|40|20|1'], 'Apakah massa bandul memengaruhi periodenya? Faktor apa yang berpengaruh?'],
                ['spring', 'Pegas dan Hukum Hooke', 'Ubah kekakuan pegas dan massa untuk melihat getarannya.', ['k|Konstanta Pegas|N/m|10|200|50|5', 'm|Massa|kg|0.1|5|1|0.1', 'A|Amplitudo|m|0.05|0.5|0.3|0.05'], 'Bagaimana periode berubah jika pegas dibuat lebih kaku?'],
                ['refraction', 'Pembiasan Cahaya', 'Amati arah cahaya saat berpindah antar dua medium.', ['n1|Indeks Bias Medium 1||1|2.5|1|0.05', 'n2|Indeks Bias Medium 2||1|2.5|1.5|0.05', 'theta1|Sudut Datang|°|0|85|40|1'], 'Kapan cahaya dibelokkan mendekati garis normal, dan kapan terjadi pemantulan sempurna?'],
            ],
            'Hukum Newton' => [
                ['incline', 'Bidang Miring', 'Kapan benda meluncur? Atur sudut, massa, dan gesekan.', ['angle|Sudut Bidang|°|5|60|30|1', 'mass|Massa|kg|1|20|5|1', 'mu|Koefisien Gesek||0|0.8|0.2|0.05'], 'Apakah massa benda memengaruhi percepatan di bidang miring tanpa gesekan?'],
                ['collision', 'Tumbukan Dua Benda', 'Bandingkan tumbukan lenting dan tidak lenting.', ['m1|Massa Benda 1|kg|1|10|4|1', 'm2|Massa Benda 2|kg|1|10|2|1', 'v1|Kecepatan Awal Benda 1|m/s|1|10|5|1', 'e|Koefisien Restitusi||0|1|1|0.1'], 'Mengapa energi kinetik berkurang pada tumbukan tidak lenting sempurna?'],
                ['buoyancy', 'Gaya Apung', 'Tentukan benda mengapung, melayang, atau tenggelam.', ['rhob|Massa Jenis Benda|kg/m³|200|2000|700|50', 'rhof|Massa Jenis Fluida|kg/m³|800|1300|1000|50'], 'Bagaimana kapal baja yang sangat berat bisa mengapung?'],
            ],
            'Energi dan Usaha' => [
                ['gas', 'Gas Ideal', 'Hubungkan suhu, volume, dan tekanan gas melalui gerak partikelnya.', ['T|Suhu|K|200|600|300|10', 'V|Volume|L|1|10|5|1', 'n|Jumlah Gas|mol|0.1|2|1|0.1'], 'Apa yang terjadi pada tekanan jika volume dikecilkan pada suhu tetap?'],
            ],
            'Listrik Dasar' => [
                ['ohm', 'Rangkaian Seri dan Hukum Ohm', 'Hitung arus dan tegangan pada dua hambatan yang disusun seri.', ['V|Tegangan Baterai|V|1|24|12|1', 'R1|Hambatan R1|Ω|1|100|20|1', 'R2|Hambatan R2|Ω|1|100|40|1'], 'Apa yang terjadi pada arus jika hambatan kedua diperbesar?'],
            ],
            'Laju Reaksi' => [
                ['equilibrium', 'Kesetimbangan Kimia', 'Geser kesetimbangan N₂O₄ ⇌ 2 NO₂ dengan suhu dan tekanan.', ['T|Suhu|K|250|400|298|2', 'P|Tekanan|atm|0.5|5|1|0.5'], 'Ke arah mana kesetimbangan bergeser saat tekanan dinaikkan? Jelaskan dengan asas Le Chatelier.'],
                ['calorimetry', 'Kalorimetri', 'Masukkan logam panas ke air dan hitung suhu akhirnya.', ['m|Massa Logam|g|10|200|50|10', 'c|Kalor Jenis Logam|J/g°C|0.1|0.9|0.4|0.05', 'Tm|Suhu Awal Logam|°C|50|200|100|10'], 'Mengapa suhu akhir campuran selalu berada di antara suhu awal air dan logam?'],
            ],
            'Struktur Atom' => [
                ['decay', 'Peluruhan Radioaktif', 'Amati inti atom meluruh menurut waktu paruhnya.', ['half|Waktu Paruh|hari|1|50|10|1', 'N0|Jumlah Inti Awal||100|1000|1000|100', 't|Waktu|hari|0|200|30|1'], 'Berapa persen inti tersisa setelah tiga kali waktu paruh?'],
            ],
            'Tabel Periodik' => [
                ['config', 'Konfigurasi Elektron', 'Lihat susunan elektron pada kulit atom untuk nomor atom 1 sampai 36.', ['Z|Nomor Atom||1|36|11|1'], 'Bagaimana jumlah elektron kulit terluar menjelaskan kemiripan unsur satu golongan?'],
            ],
            'Ikatan Kimia' => [
                ['stoich', 'Pereaksi Pembatas', 'Tentukan pereaksi yang habis lebih dulu pada pembentukan air.', ['nh|Mol H₂|mol|0.5|10|4|0.5', 'no|Mol O₂|mol|0.5|10|1|0.5'], 'Mengapa jumlah produk ditentukan oleh pereaksi pembatas?'],
                ['solubility', 'Kelarutan terhadap Suhu', 'Lihat berapa banyak KNO₃ yang bisa larut pada berbagai suhu.', ['T|Suhu|°C|0|100|40|5', 'mass|Massa KNO₃ per 100 g air|g|10|250|60|10'], 'Bagaimana kamu membuat larutan jenuh KNO₃ mengendap kembali?'],
                ['electro', 'Sel Volta', 'Bandingkan dua elektroda dan lihat potensial sel yang dihasilkan.', ['e1|E° Elektroda A|V|-2.5|1.2|-0.76|0.02', 'e2|E° Elektroda B|V|-2.5|1.2|0.34|0.02'], 'Elektroda mana yang menjadi anode, dan ke mana elektron mengalir?'],
            ],
            'Asam dan Basa' => [
                ['ph', 'Skala pH', 'Geser pH dan lihat perubahan warna indikator serta konsentrasi ion.', ['ph|pH||0|14|7|0.5'], 'Berapa kali lebih asam larutan pH 3 dibanding pH 5?'],
                ['titration', 'Titrasi Asam-Basa', 'Teteskan NaOH ke HCl dan temukan titik ekuivalen.', ['Ma|Molaritas HCl|M|0.05|0.5|0.1|0.05', 'Mb|Molaritas NaOH|M|0.05|0.5|0.1|0.05', 'Vb|Volume NaOH Ditambahkan|mL|0|60|10|1'], 'Mengapa pH berubah sangat tajam di sekitar titik ekuivalen?'],
                ['molarity', 'Molaritas Larutan', 'Hitung konsentrasi larutan dari massa, Mr, dan volume.', ['g|Massa Zat Terlarut|g|1|50|10|1', 'Mr|Massa Molekul Relatif (Mr)||18|180|58.5|0.5', 'V|Volume Larutan|mL|50|1000|500|50'], 'Apa yang terjadi pada molaritas jika volume larutan digandakan?'],
            ],
            'Orbit Planet' => [
                ['kepler2', 'Hukum Kepler II', 'Orbit lonjong: planet bergerak lebih cepat saat dekat bintang.', ['e|Eksentrisitas||0|0.8|0.5|0.05', 'a|Sumbu Semi-mayor|AU|0.5|5|1|0.1'], 'Di titik mana planet bergerak paling cepat, dan mengapa?'],
                ['escape', 'Kecepatan Lepas', 'Berapa cepat roket harus melaju agar lolos dari gravitasi planet?', ['mass|Massa Planet|M⊕|0.1|300|1|0.1', 'rad|Jari-jari Planet|R⊕|0.3|12|1|0.1'], 'Mengapa Jupiter jauh lebih sulit ditinggalkan dibanding Bumi?'],
            ],
            'Tata Surya' => [
                ['gravity', 'Berat di Planet Lain', 'Bandingkan beratmu di planet-planet Tata Surya.', ['mass|Massa Tubuhmu|kg|10|100|50|5', 'planet|Planet (1=Merkurius ... 8=Neptunus)||1|8|3|1'], 'Apa perbedaan massa dan berat? Mana yang berubah saat berpindah planet?'],
                ['lightt', 'Perjalanan Cahaya', 'Hitung lama cahaya dan pesawat menempuh jarak antar benda langit.', ['obj|Objek (1=Bulan 2=Mars 3=Matahari 4=Jupiter 5=Saturnus 6=Neptunus)||1|6|3|1', 'frac|Kecepatan Pesawat (× c)||0.01|1|0.1|0.01'], 'Mengapa kita melihat Matahari sebagaimana keadaannya sekitar 8 menit lalu?'],
            ],
            'Bintang dan Siklus Hidupnya' => [
                ['star', 'Kecerahan Bintang', 'Hubungkan magnitudo mutlak, jarak, dan kecerahan semu.', ['M|Magnitudo Mutlak||-5|15|4.8|0.1', 'd|Jarak|pc|1|1000|10|1'], 'Mengapa bintang yang sangat terang bisa tampak redup dari Bumi?'],
            ],
            'Gerhana dan Fase Bulan' => [
                ['phases', 'Fase Bulan', 'Geser posisi Bulan di orbitnya dan lihat bentuk yang tampak dari Bumi.', ['angle|Posisi Bulan di Orbit|°|0|360|90|5'], 'Mengapa Bulan tampak berubah bentuk padahal selalu setengah bagiannya diterangi Matahari?'],
                ['tides', 'Pasang Surut', 'Gabungan tarikan Bulan dan Matahari menentukan tinggi pasang.', ['ang|Sudut Bulan–Matahari|°|0|360|0|15', 'dist|Jarak Bulan|% dari rata-rata|90|110|100|1'], 'Kapan terjadi pasang purnama dan pasang perbani? Apa penyebabnya?'],
                ['season', 'Panjang Siang dan Musim', 'Lihat pengaruh kemiringan sumbu Bumi pada lamanya siang sepanjang tahun.', ['tilt|Kemiringan Sumbu Bumi|°|0|40|23.4|0.1', 'day|Hari ke-|hari|1|365|172|1', 'lat|Lintang|°|-60|60|0|5'], 'Mengapa panjang siang hampir tidak berubah di khatulistiwa?'],
            ],
            'Galaksi dan Alam Semesta' => [
                ['hubble', 'Hukum Hubble', 'Semakin jauh galaksi, semakin cepat ia menjauh.', ['d|Jarak Galaksi|Mpc|10|1000|100|10', 'H0|Konstanta Hubble|km/s/Mpc|60|80|70|1'], 'Apa arti galaksi-galaksi yang menjauh bagi perkembangan alam semesta?'],
                ['telescope', 'Teleskop Bias', 'Hitung perbesaran, daya pisah, dan pengumpulan cahaya teleskop.', ['fo|Panjang Fokus Objektif|mm|500|2000|1000|50', 'fe|Panjang Fokus Okuler|mm|5|40|10|1', 'D|Diameter Objektif|mm|50|300|100|10'], 'Mengapa diameter lensa objektif lebih penting daripada perbesaran untuk melihat objek redup?'],
            ],
            'Fungsi Sinus' => [
                ['trig', 'Lingkaran Satuan', 'Hubungkan sudut pada lingkaran satuan dengan grafik sinus.', ['angle|Sudut|°|0|360|45|5'], 'Pada sudut berapa sin bernilai maksimum, dan berapa nilainya?'],
                ['riemann', 'Luas di Bawah Kurva', 'Hampiri luas di bawah y = x² dengan persegi panjang.', ['n|Jumlah Subinterval||2|100|10|1', 'b|Batas Atas Integral||1|5|4|0.5'], 'Apa yang terjadi pada galat jika jumlah persegi panjang diperbanyak?'],
            ],
            'Aljabar Dasar' => [
                ['linear', 'Garis Lurus', 'Pahami gradien dan titik potong dari persamaan garis.', ['m|Gradien (m)||-5|5|1|0.5', 'c|Konstanta (c)||-5|5|0|0.5'], 'Apa arti gradien negatif pada grafik garis?'],
                ['quad', 'Fungsi Kuadrat', 'Lihat bentuk parabola, titik puncak, dan akar-akarnya.', ['a|a||-3|3|1|0.1', 'b|b||-6|6|0|0.5', 'c|c||-6|6|-3|0.5'], 'Bagaimana tanda a menentukan arah buka parabola, dan apa arti diskriminan negatif?'],
                ['expo', 'Eksponen dan Logaritma', 'Bandingkan fungsi eksponen dengan inversnya, logaritma.', ['a|Basis (a)||1.2|4|2|0.1', 'x|Nilai x||-2|3|1|0.5'], 'Mengapa grafik eksponen dan logaritma saling mencerminkan terhadap garis y = x?'],
                ['seq', 'Barisan Aritmetika', 'Hitung suku ke-n dan jumlah n suku pertama.', ['a1|Suku Pertama||1|20|2|1', 'd|Beda||0|10|3|1', 'n|Banyak Suku||2|30|10|1'], 'Bagaimana kamu menemukan suku ke-100 tanpa menuliskan semua suku?'],
            ],
            'Geometri Bangun Datar' => [
                ['pytha', 'Teorema Pythagoras', 'Lihat luas persegi pada sisi-sisi segitiga siku-siku.', ['a|Sisi Tegak a||1|12|3|1', 'b|Sisi Tegak b||1|12|4|1'], 'Bagaimana luas persegi pada sisi miring berhubungan dengan dua persegi lainnya?'],
                ['transform', 'Transformasi Geometri', 'Geser, putar, dan skalakan segitiga pada bidang koordinat.', ['tx|Translasi x||-5|5|2|1', 'ty|Translasi y||-5|5|1|1', 'rot|Rotasi|°|0|360|30|15', 'k|Skala||0.5|2|1|0.1'], 'Transformasi mana yang mengubah ukuran bangun dan mana yang tidak?'],
            ],
            'Statistika Dasar' => [
                ['normal', 'Distribusi Normal', 'Lihat sebaran data berbentuk lonceng dan peluang dalam ± k simpangan baku.', ['mu|Rata-rata (μ)||40|80|60|1', 'sigma|Simpangan Baku (σ)||2|20|8|1', 'k|Rentang (± k σ)||1|3|1|0.5'], 'Berapa persen data berada dalam satu simpangan baku dari rata-rata?'],
            ],
            'Peluang' => [
                ['prob', 'Percobaan Peluang', 'Lempar koin berulang dan lihat frekuensi relatif mendekati peluang teoretis.', ['n|Jumlah Percobaan||10|2000|100|10', 'p|Peluang Sukses||0.1|0.9|0.5|0.05'], 'Mengapa hasil percobaan semakin mendekati peluang teoretis jika percobaan diperbanyak?'],
            ],
        ];
        foreach ($extra as $materialTitle => $sims) {
            $m = \App\Models\Material::where('title', $materialTitle)->firstOrFail();
            foreach ($sims as [$type, $title, $desc, $params, $question]) {
                $sim = $m->simulations()->create(['title' => $title, 'type' => $type, 'description' => $desc]);
                foreach ($params as $row) {
                    [$n, $l, $u, $min, $max, $def, $st] = explode('|', $row);
                    $sim->parameters()->create(['name' => $n, 'label' => $l, 'unit' => $u, 'min_value' => $min, 'max_value' => $max, 'default_value' => $def, 'step' => $st]);
                }
                $sim->questions()->create(['question' => $question, 'sort_order' => 1]);
            }
        }
    }
}
