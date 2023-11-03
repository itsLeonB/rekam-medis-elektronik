<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationCategory extends Model
{
    public const SYSTEM = 'http://terminology.hl7.org/CodeSystem/observation-category';
    public const CODE = ['social-history', 'vital-signs', 'imaging', 'laboratory', 'procedure', 'survey', 'exam', 'therapy', 'activity'];
    public const DISPLAY = ['social-history' => 'Social History', 'vital-signs' => 'Vital Signs', 'imaging' => 'Imaging', 'laboratory' => 'Laboratory', 'procedure' => 'Procedure', 'survey' => 'Survey', 'exam' => 'Exam', 'therapy' => 'Therapy', 'activity' => 'Activity'];
    public const DEFINITION = ['social-history' => 'Digunakan ketika melaporkan pekerjaan pasien, riwayat sosial pasien, keluarga, kondisi lingkungan, dan faktor risiko kesehatan yang berdampak pada kesehatan pasien.', 'vital-signs' => 'Digunakan ketika melaporkan berkaitan dengan hasil pengukuran fungsi dasar tubuh seperti tekanan darah, denyut nadi, laju pernapasan, tinggi badan, berat badan, body mass index (BMI), lingkar kepala, saturasi oksigen, suhu tubuh, dan luas permukaan tubuh', 'imaging' => 'Digunakan ketika melaporkan berkaitan dengan pencitraan tubuh yang meliputi X-ray, ultrasound, CT Scan, MRI, angiografi, EKG, dan kedokteran nuklir.', 'laboratory' => 'Digunakan ketika melaporkan berkaitan dengan hasil analisis spesimen yang dikeluarkan oleh laboratorium yaitu kimia klinik, hematologi, serologi, histologi, sitologi, patologi anatomi (termasuk patologi digital), mikrobiologi, dan atau virologi.', 'procedure' => 'Digunakan ketika melaporkan hasil observasi yang dihasilkan dari prosedur lain. Kategori ini termasuk observasi dari tindakan intervensi dan non-intervensi di luar laboratorium dan imaging (contoh kateterisasi jantung, endoskopi, elektrodiagnostik). Contoh : dokter penyakit dalam melaporkan ukuran polip yang didapatkan melalui pemeriksaan kolonoskopi.', 'survey' => 'Digunakan ketika melaporkan berkaitan dengan alat asesmen maupun survei alat observasi seperti Skor APGAR, Montreal Cognitive Assessment (MoCA), dll).', 'exam' => 'Berkaitan dengan observasi fisik yang dilakukan langsung oleh tenaga kesehatan dan menggunakan alat sederhana.', 'therapy' => 'Berkaitan dengan protokol terapi non-intervensi seperti terapi okupasi, terapi fisik, terapi radiasi, terapi nutrisi, dan terapi medis.', 'activity' => 'Berkaitan dengan pengukuran aktivitas tubuh guna meningkatkan/memelihara kondisi fisik dan seluruh kesehatan dan tidak berkaitan dengan supervisi praktisi. Observasi tidak dibawah pengawasan langsung praktisi seperti ahli terapi fisik (contoh: jumlah putaran berenang, langkah kaki, data terkait tidur).'];


    protected $table = 'observation_category';
    public $timestamps = false;

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }
}
