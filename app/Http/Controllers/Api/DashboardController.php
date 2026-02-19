<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Absence;
use App\Models\Justification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /** GET /api/dashboard/admin */
    public function admin()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalAbsenceHours = Absence::sum('hours');
        $totalPossibleHours = $totalStudents * 40; // approximate
        $absenceRate = $totalPossibleHours > 0
            ? round(($totalAbsenceHours / $totalPossibleHours) * 100, 1)
            : 0;

        return response()->json([
            'data' => [
                'total_students'  => $totalStudents,
                'total_teachers'  => $totalTeachers,
                'absence_rate'    => $absenceRate,
                'pending_reviews' => Justification::where('status', 'pending')->count(),
            ],
        ]);
    }

    /** GET /api/dashboard/teacher */
    public function teacher(Request $request)
    {
        $teacher = $request->user()->teacher;

        return response()->json([
            'data' => [
                'my_groups'       => $teacher->groups()->count(),
                'my_students'     => Student::whereIn('group_id', $teacher->groups()->pluck('groups.id'))->count(),
                'today_absences'  => Absence::where('teacher_id', $teacher->id)
                                        ->whereDate('date', today())->count(),
            ],
        ]);
    }

    /** GET /api/dashboard/student */
    public function student(Request $request)
    {
        $student = $request->user()->student;
        $absences = $student->absences;

        $totalHours = $absences->sum('hours');
        $justifiedHours = $absences->where('status', 'justified')->sum('hours');
        $unjustifiedHours = $absences->where('status', 'unjustified')->sum('hours');
        $pendingHours = $absences->where('status', 'pending')->sum('hours');

        // Approximate attendance rate (assuming 600 total hours per year)
        $totalPossible = 600;
        $attendanceRate = $totalPossible > 0
            ? round((1 - $totalHours / $totalPossible) * 100, 1)
            : 100;

        return response()->json([
            'data' => [
                'absence_hours'         => (float) $totalHours,
                'justified_hours'       => (float) $justifiedHours,
                'unjustified_hours'     => (float) $unjustifiedHours,
                'pending_hours'         => (float) $pendingHours,
                'pending_justifications'=> Justification::whereIn('absence_id', $absences->pluck('id'))
                                              ->where('status', 'pending')->count(),
                'unjustified_count'     => $absences->where('status', 'unjustified')->count(),
                'attendance_rate'       => $attendanceRate,
            ],
        ]);
    }

    /** GET /api/dashboard/chart — monthly attendance/absence data */
    public function chart()
    {
        // Get absences grouped by month for the current academic year
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        // Academic year: Sep–Jun. If we're in Sep+ use current year, else previous.
        $startYear = $currentMonth >= 9 ? $currentYear : $currentYear - 1;

        $months = [];
        // Sep through current month (up to Jun next year)
        for ($m = 9; $m <= 12; $m++) {
            $months[] = Carbon::create($startYear, $m, 1);
        }
        for ($m = 1; $m <= min($currentMonth, 6); $m++) {
            $months[] = Carbon::create($startYear + 1, $m, 1);
        }

        $totalStudents = max(Student::count(), 1);

        // Get monthly absence hours via one query
        $rows = Absence::select(
                DB::raw('YEAR(date) as y'),
                DB::raw('MONTH(date) as m'),
                DB::raw('SUM(hours) as total_hours'),
                DB::raw('COUNT(DISTINCT student_id) as absent_students')
            )
            ->where(function ($q) use ($startYear) {
                $q->where(function ($q2) use ($startYear) {
                    $q2->whereYear('date', $startYear)->whereMonth('date', '>=', 9);
                })->orWhere(function ($q2) use ($startYear) {
                    $q2->whereYear('date', $startYear + 1)->whereMonth('date', '<=', 6);
                });
            })
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->get()
            ->keyBy(fn ($r) => $r->y . '-' . $r->m);

        $data = [];
        foreach ($months as $dt) {
            $key = $dt->year . '-' . $dt->month;
            $row = $rows->get($key);
            $absenceHours = $row ? (float) $row->total_hours : 0;

            // Approximate: assume 160h possible per student per month (40h/week × 4)
            $totalPossible = $totalStudents * 160;
            $absencePct = $totalPossible > 0 ? round(($absenceHours / $totalPossible) * 100, 1) : 0;
            $attendancePct = round(100 - $absencePct, 1);

            $data[] = [
                'month'      => $dt->format('M'),
                'attendance' => $attendancePct,
                'absences'   => $absencePct,
            ];
        }

        return response()->json(['data' => $data]);
    }

    /** GET /api/dashboard/heatmap — weekly absence heatmap (4 weeks × 5 days) */
    public function heatmap()
    {
        $today = Carbon::today();
        // Go back 4 full weeks from today (Mon–Fri)
        $startOfCurrentWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
        $start = $startOfCurrentWeek->copy()->subWeeks(3);

        $rows = Absence::select(
                DB::raw('date'),
                DB::raw('SUM(hours) as total_hours')
            )
            ->where('date', '>=', $start->toDateString())
            ->where('date', '<=', $today->toDateString())
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $weeks = [];
        for ($w = 0; $w < 4; $w++) {
            $weekStart = $start->copy()->addWeeks($w);
            $weekLabel = 'W' . $weekStart->weekOfYear;
            $days = [];
            for ($d = 0; $d < 5; $d++) { // Mon-Fri
                $dt = $weekStart->copy()->addDays($d);
                $dateStr = $dt->toDateString();
                $hours = isset($rows[$dateStr]) ? (float) $rows[$dateStr]->total_hours : 0;
                $days[] = round($hours, 1);
            }
            $weeks[] = ['week' => $weekLabel, 'days' => $days];
        }

        return response()->json(['data' => $weeks]);
    }
}
