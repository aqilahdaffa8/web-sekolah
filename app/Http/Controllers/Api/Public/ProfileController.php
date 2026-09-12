<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * GET /api/public/profile
     */
    public function index(): JsonResponse
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        $missions = $settings['missions'] ?? null;
        if (is_string($missions)) {
            $decoded = json_decode($missions, true);
            $missions = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode("\n", $missions)));
        }

        $orgMembers = $settings['organization_members'] ?? null;
        if (is_string($orgMembers)) {
            $orgMembers = json_decode($orgMembers, true) ?: [];
        }

        $programs = Program::with('classes')->get()->map(function ($p) {
            return [
                'id'            => $p->id,
                'name'          => $p->program_name,
                'program_name'  => $p->program_name,
                'description'   => $p->description ?? 'Kompetensi keahlian unggulan berstandar industri dengan kurikulum terkini dan pembelajaran berbasis praktik.',
                'image'         => $p->image,
                'level'         => 'SMK 3 Tahun',
                'accreditation' => 'A',
                'classes'       => $p->classes,
            ];
        });

        $facilities = Facility::with('program')->get()->map(function ($f) {
            return [
                'id'            => $f->id,
                'name'          => $f->facility_name ?? $f->name,
                'facility_name' => $f->facility_name ?? $f->name,
                'description'   => $f->description ?? 'Fasilitas praktik modern berstandar industri untuk mengasah keterampilan siswa.',
                'location'      => $f->location ?? 'Kampus SMKN 1 Katapang',
                'image'         => $f->image ?? $f->image_url,
                'image_url'     => $f->image ?? $f->image_url,
                'program'       => $f->program,
            ];
        });

        return response()->json([
            'school' => $settings,
            'vision' => $settings['vision'] ?? 'Menjadi sekolah kejuruan unggulan yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global.',
            'missions' => $missions ?: [
                'Menyelenggarakan pendidikan bermutu berbasis kompetensi industri 4.0.',
                'Mengembangkan karakter, budi pekerti luhur, dan jiwa wirausaha peserta didik.',
                'Membangun kemitraan strategis dengan dunia usaha dan industri (DUDI) bereputasi.',
                'Mengembangkan sarana prasarana dan unit Teaching Factory berstandar internasional.',
            ],
            'history' => $settings['history'] ?? 'SMKN 1 Katapang berdiri sejak tahun 1999 dengan komitmen mencetak generasi vokasi unggul dan berkarakter di bidang teknologi dan industri. Selama lebih dari dua dekade, sekolah terus berkembang pesat hingga memiliki 7 kompetensi keahlian unggulan dengan ribuan alumni yang telah sukses berkarier di berbagai industri nasional maupun multinasional.',
            'organization_members' => $orgMembers ?: [
                ['name' => 'Drs. H. Agus Ruswandi, M.Pd.', 'position' => 'Kepala Sekolah', 'photo' => null],
                ['name' => 'Dra. Hj. Nunung Maryati', 'position' => 'Wakasek Kurikulum', 'photo' => null],
                ['name' => 'Ir. Bambang Sugianto', 'position' => 'Wakasek Kesiswaan', 'photo' => null],
                ['name' => 'Asep Saepudin, S.T., M.Kom.', 'position' => 'Wakasek Hubin & Humas', 'photo' => null],
                ['name' => 'Dedi Junaedi, S.Pd.', 'position' => 'Wakasek Sarana Prasarana', 'photo' => null],
                ['name' => 'Rina Marlina, S.Kom.', 'position' => 'Ketua Program Keahlian RPL', 'photo' => null],
            ],
            'programs' => $programs,
            'facilities' => $facilities,
        ]);
    }
}
