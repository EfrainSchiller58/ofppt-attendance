<?php

namespace App\Console\Commands;

use App\Mail\WeeklyReportMail;
use App\Models\Absence;
use App\Models\Teacher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyReport extends Command
{
    protected $signature = 'report:weekly';
    protected $description = 'Send weekly absence report to all teachers';

    public function handle(): int
    {
        $weekStart = now()->subWeek()->startOfWeek()->format('Y-m-d');
        $weekEnd = now()->subWeek()->endOfWeek()->format('Y-m-d');

        $teachers = Teacher::with(['user', 'groups'])->get();

        $sent = 0;

        foreach ($teachers as $teacher) {
            $teacherName = $teacher->user->first_name . ' ' . $teacher->user->last_name;

            $groups = [];
            $totalAbsences = 0;
            $totalJustified = 0;
            $totalUnjustified = 0;

            foreach ($teacher->groups as $group) {
                $absences = Absence::with('student.user')
                    ->where('group_id', $group->id)
                    ->where('teacher_id', $teacher->id)
                    ->whereBetween('date', [$weekStart, $weekEnd])
                    ->get();

                $students = [];
                $grouped = $absences->groupBy('student_id');
                foreach ($grouped as $studentId => $studentAbsences) {
                    $user = $studentAbsences->first()->student->user;
                    $hours = $studentAbsences->sum('hours');
                    $count = $studentAbsences->count();

                    $students[] = [
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'absences' => $count,
                        'hours' => round($hours, 1),
                    ];
                }

                // Sort by most absences
                usort($students, fn($a, $b) => $b['absences'] <=> $a['absences']);

                $groupAbsences = $absences->count();
                $justified = $absences->where('status', 'justified')->count();
                $unjustified = $groupAbsences - $justified;

                $totalAbsences += $groupAbsences;
                $totalJustified += $justified;
                $totalUnjustified += $unjustified;

                $groups[] = [
                    'name' => $group->name,
                    'count' => $groupAbsences,
                    'students' => $students,
                ];
            }

            try {
                Mail::to($teacher->user->email)->send(new WeeklyReportMail(
                    teacherName: $teacherName,
                    weekStart: $weekStart,
                    weekEnd: $weekEnd,
                    totalAbsences: $totalAbsences,
                    justifiedCount: $totalJustified,
                    unjustifiedCount: $totalUnjustified,
                    groups: $groups,
                ));
                $sent++;
                $this->info("Sent report to {$teacherName}");
            } catch (\Throwable $e) {
                $this->error("Failed to send to {$teacherName}: " . $e->getMessage());
            }
        }

        $this->info("Weekly report sent to {$sent} teachers.");
        return self::SUCCESS;
    }
}
