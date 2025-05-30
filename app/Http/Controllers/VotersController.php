<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Illuminate\Http\Request;

class VotersController extends Controller
{
    public function index(Request $request)
{
    $query = Voter::query();

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%$search%")
              ->orWhere('middle_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%")
              ->orWhere('student_id', 'like', "%$search%")
              ->orWhere('rfid', 'like', "%$search%");
        });
    }

    $voters = $query->orderBy('last_name')->paginate(20);

    return view('voter', compact('voters'));
}


    public function destroy($id){
        $voters = Voter::findOrFail($id);

        if ($voters->photo) {
        $photoPath = public_path('uploads/voters/' . $voters->photo);
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }

        $voters->delete();

        return redirect()->back()->with('success', 'Voter deleted successfully.');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'student_id' => 'required|string',
            'rfid' => 'required|string',
            'department' => 'required|string',
            'session' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/voters'), $filename);
            $validated['photo'] = $filename;
        }

        Voter::create($validated);

        return redirect()->back()->with('success', 'Voter Registered Successfully.');
    }

    public function update(Request $request, $id){
        $voters = Voter::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'student_id' => 'required|unique:voters,student_id,' . $id,
            'rfid' => 'required|unique:voters,rfid,' . $id,
            'department' => 'required|string',
            'session' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ],[
            'student_id.unique' => 'This Student ID is already registered.',
            'rfid.unique' => 'This RFID is already registered.',
        ]);

        if ($request->hasFile('photo')) {
        // Delete old photo
            if ($voters->photo && file_exists(public_path('uploads/voters/' . $voters->photo))) {
                unlink(public_path('uploads/voters/' . $voters->photo));
            }

            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/voters'), $filename);
            $validated['photo'] = $filename;
        }

        $voters->update($validated);

        return redirect()->back()->with('success', 'Voter updated successfully.');
    }
}
