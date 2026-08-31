<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AcademicMasterSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            'Rekayasa Perangkat Lunak' => ['RPL 1', 'RPL 2'],
            'Teknik Komputer dan Jaringan' => ['TKJ 1', 'TKJ 2'],
            'Akuntansi' => ['AK 1'],
        ];

        foreach ($programs as $programName => $classes) {
            $program = Program::firstOrCreate(['program_name' => $programName]);

            foreach ($classes as $className) {
                ClassRoom::firstOrCreate([
                    'class_name' => $className,
                    'program_id' => $program->id,
                ]);
            }
        }

        $subjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'Pemrograman Web', 'Basis Data'];

        foreach ($subjects as $subjectName) {
            Subject::firstOrCreate(['subject_name' => $subjectName]);
        }
    }
}
