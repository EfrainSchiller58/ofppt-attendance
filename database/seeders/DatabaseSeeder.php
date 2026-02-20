<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks (works for both MySQL and SQLite)
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        Notification::truncate();
        DB::table('justifications')->truncate();
        DB::table('absences')->truncate();
        DB::table('teacher_groups')->truncate();
        Student::truncate();
        Teacher::truncate();
        Group::truncate();
        User::truncate();
        DB::table('personal_access_tokens')->truncate();

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $defaultPassword = 'password123';

        // Admin
        User::create([
            'first_name' => 'Admin',
            'last_name'  => 'OFPPT',
            'email'      => 'admin@ofppt.com',
            'password'   => Hash::make($defaultPassword),
            'role'       => 'admin',
            'must_change_password' => false,
        ]);

        // Groups
        $g1 = Group::create(['name' => 'DEV201', 'level' => '2ème année']);
        $g2 = Group::create(['name' => 'DEVOFWS', 'level' => '1ère année']);

        // Teachers (4)
        $teachers = [
            ['first_name' => 'Salma', 'last_name' => 'Karim', 'subject' => 'Développement Web', 'groups' => [$g1->id]],
            ['first_name' => 'Omar', 'last_name' => 'Haddad', 'subject' => 'Base de données', 'groups' => [$g1->id, $g2->id]],
            ['first_name' => 'Nadia', 'last_name' => 'Fassi', 'subject' => 'Réseaux', 'groups' => [$g2->id]],
            ['first_name' => 'Yassine', 'last_name' => 'Amrani', 'subject' => 'PHP / Laravel', 'groups' => [$g2->id]],
        ];

        foreach ($teachers as $t) {
            $email = $this->generateSchoolEmail();
            $user = User::create([
                'first_name' => $t['first_name'],
                'last_name'  => $t['last_name'],
                'email'      => $email,
                'password'   => Hash::make($defaultPassword),
                'role'       => 'teacher',
                'must_change_password' => true,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'subject' => $t['subject'],
            ]);

            $teacher->groups()->attach($t['groups']);
        }

        // Students (20 per group)
        $firstNames = ['Youssef', 'Fatima', 'Ahmed', 'Sara', 'Khalid', 'Amina', 'Ilyas', 'Meryem', 'Hamza', 'Imane', 'Mehdi', 'Hajar', 'Rachid', 'Asma', 'Ayoub', 'Kawtar', 'Soufiane', 'Chaima', 'Karim', 'Lina'];
        $lastNames = ['Bennani', 'Zahra', 'Tazi', 'El Amrani', 'Fassi', 'Karim', 'El Idrissi', 'Ouali', 'Bouzid', 'Lamrani', 'Alaoui', 'Gharbi', 'Berrada', 'Safi', 'Naciri', 'Daoudi', 'Kabbaj', 'Rahmani', 'El Hammadi', 'Chakir'];

        $groups = [$g1, $g2];
        foreach ($groups as $groupIndex => $group) {
            for ($i = 1; $i <= 20; $i++) {
                $firstName = $firstNames[($i - 1) % count($firstNames)];
                $lastName = $lastNames[($i - 1 + $groupIndex) % count($lastNames)];
                $email = $this->generateSchoolEmail();
                $cne = sprintf('F%s%03d', $groupIndex + 1, $i);

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'password'   => Hash::make($defaultPassword),
                    'role'       => 'student',
                    'must_change_password' => true,
                ]);

                Student::create([
                    'user_id'  => $user->id,
                    'cne'      => $cne,
                    'group_id' => $group->id,
                ]);
            }
        }
    }

    private function generateSchoolEmail(): string
    {
        do {
            $code = 'f' . random_int(10000, 99999);
            $email = $code . '@ofppt.com';
        } while (User::where('email', $email)->exists());

        return $email;
    }
}
