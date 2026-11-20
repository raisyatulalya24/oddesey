<?php
namespace Database\Seeders;
use App\Models\{Subject, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Admin Oddesey', 'email' => 'admin@oddesey.test', 'password' => 'password', 'role' => 'admin']);
        User::create(['name' => 'Siswa Demo', 'email' => 'siswa@oddesey.test', 'password' => 'password', 'role' => 'student']);

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
    }
}
