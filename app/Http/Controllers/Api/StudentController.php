<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /** GET /api/students */
    public function index()
    {
        if (request()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $students = Student::with(['user', 'group'])->get();
        return StudentResource::collection($students);
    }

    /** POST /api/students */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'cne'        => 'required|string|max:20|unique:students,cne',
            'phone'      => 'nullable|string|max:20',
            'group_id'   => 'required|exists:groups,id',
        ]);

        return DB::transaction(function () use ($data) {
            $email = $this->generateSchoolEmail();
            $defaultPassword = 'password123';

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $email,
                'password'   => Hash::make($defaultPassword), // default password
                'role'       => 'student',
                'must_change_password' => true,
            ]);

            $student = Student::create([
                'user_id'  => $user->id,
                'cne'      => $data['cne'],
                'phone'    => $data['phone'] ?? null,
                'group_id' => $data['group_id'],
            ]);

            $student->load(['user', 'group']);
            return response()->json([
                'data' => new StudentResource($student),
                'credentials' => [
                    'email' => $email,
                    'password' => $defaultPassword,
                ],
            ]);
        });
    }

    /** PUT /api/students/{id} */
    public function update(Request $request, Student $student)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name'  => 'sometimes|string|max:100',
            'cne'        => 'sometimes|string|max:20|unique:students,cne,' . $student->id,
            'phone'      => 'nullable|string|max:20',
            'group_id'   => 'sometimes|exists:groups,id',
        ]);

        DB::transaction(function () use ($student, $data) {
            $student->user->update(collect($data)->only(['first_name', 'last_name'])->toArray());
            $student->update(collect($data)->only(['cne', 'phone', 'group_id'])->toArray());
        });

        $student->load(['user', 'group']);
        return new StudentResource($student);
    }

    /** DELETE /api/students/{id} */
    public function destroy(Student $student)
    {
        if (request()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $student->user->delete(); // cascades to student
        return response()->noContent();
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
