<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateStudentEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:update-emails {--email= : Base email for Gmail aliases}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all fictional student emails to testable Gmail aliases';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📧 Student Email Update Tool');
        $this->info('=============================');
        $this->newLine();

        // Get or prompt for email
        $baseEmail = $this->option('email');

        if (!$baseEmail) {
            $baseEmail = $this->ask(
                'Enter your Gmail address (emails will be sent to username+student1@gmail.com, username+student2@gmail.com, etc.)',
                'your-email@gmail.com'
            );
        }

        // Validate email format
        if (!filter_var($baseEmail, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Invalid email address format');
            return 1;
        }

        // Extract email parts
        $emailParts = explode('@', $baseEmail);
        if (count($emailParts) !== 2) {
            $this->error('❌ Invalid email format');
            return 1;
        }

        $emailName = $emailParts[0];
        $emailDomain = $emailParts[1];

        $this->newLine();
        $this->info("Using base email: {$baseEmail}");
        $this->info("Student aliases will be: {$emailName}+student1@gmail.com, {$emailName}+student2@gmail.com, etc.");
        $this->newLine();

        // Get all student users
        $students = DB::table('users')
            ->join('students', 'users.id', '=', 'students.user_id')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->get();

        if ($students->isEmpty()) {
            $this->warn('⚠️  No students found in database');
            return 0;
        }

        $this->info("Found {$students->count()} students to update");
        $this->newLine();

        // Show preview
        $this->table(
            ['#', 'Student Name', 'Old Email', 'New Email'],
            $students->map(function ($student, $index) use ($emailName) {
                return [
                    $index + 1,
                    "{$student->first_name} {$student->last_name}",
                    $student->email,
                    "{$emailName}+student" . ($index + 1) . "@gmail.com"
                ];
            })->toArray()
        );

        $this->newLine();

        // Confirm before updating
        if (!$this->confirm('Do you want to proceed with updating these emails?', true)) {
            $this->info('❌ Update cancelled');
            return 0;
        }

        // Perform the update
        DB::beginTransaction();

        try {
            $counter = 1;
            foreach ($students as $student) {
                $newEmail = "{$emailName}+student{$counter}@gmail.com";

                DB::table('users')
                    ->where('id', $student->id)
                    ->update(['email' => $newEmail]);

                $this->line("✅ Updated {$student->first_name} {$student->last_name}: {$student->email} → {$newEmail}");
                $counter++;
            }

            DB::commit();

            $this->newLine();
            $this->info('✅ Successfully updated all student emails!');
            $this->info('');
            $this->info('📝 Next steps:');
            $this->info('1. Students can now login with their new emails');
            $this->info("2. All emails will be sent to {$baseEmail}");
            $this->info('3. Test the email notifications with an absence or justification');
            $this->newLine();

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error updating emails: ' . $e->getMessage());
            return 1;
        }
    }
}
