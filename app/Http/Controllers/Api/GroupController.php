<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\StudentResource;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /** GET /api/groups */
    public function index()
    {
        $user = request()->user();

        if ($user->role === 'teacher') {
            $groups = $user->teacher->groups()->withCount(['students', 'teachers'])->get();
        } elseif ($user->role === 'student') {
            $groups = Group::withCount(['students', 'teachers'])->where('id', $user->student->group_id)->get();
        } else {
            $groups = Group::withCount(['students', 'teachers'])->get();
        }

        return GroupResource::collection($groups);
    }

    /** POST /api/groups */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:50|unique:groups,name',
            'level' => 'required|string|max:50',
        ]);

        $group = Group::create($data);
        return new GroupResource($group);
    }

    /** PUT /api/groups/{id} */
    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name'  => 'sometimes|string|max:50|unique:groups,name,' . $group->id,
            'level' => 'sometimes|string|max:50',
        ]);

        $group->update($data);
        return new GroupResource($group);
    }

    /** DELETE /api/groups/{id} */
    public function destroy(Group $group)
    {
        $group->delete();
        return response()->noContent();
    }

    /** GET /api/groups/{id}/students */
    public function students(Group $group)
    {
        $user = request()->user();
        if ($user->role === 'teacher' && !$user->teacher->groups->contains($group->id)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'student' && $user->student->group_id !== $group->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $students = $group->students()->with(['user', 'group'])->get();
        return StudentResource::collection($students);
    }
}
