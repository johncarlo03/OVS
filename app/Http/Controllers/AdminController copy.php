<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Admin;
use App\Models\Voter;
use App\Models\Candidate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $totalVoters = Voter::count(); // This counts all rows in the 'voters' table
        $studentsVoted = Vote::distinct('voter_id')->count();
        $candidatesNo = Candidate::count();

        $fullPosition = [
            'P' => 'President',
            'VP' => 'Vice President',
            'GSEC' => 'General Secretary',
            'FSEC' => 'Finance Secretary',
            'AUD' => 'Auditor',
            'REP' => 'Representative'
        ];
        
        $candidates = Candidate::select('candidates.id', 'candidates.last_name', 'candidates.first_name', 'candidates.middle_name', 'candidates.position', 'candidates.department', 'candidates.session')
        ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
        ->selectRaw('COUNT(votes.id) as total_votes')
        ->groupBy('candidates.id', 'candidates.last_name', 'candidates.first_name', 'candidates.middle_name', 'candidates.position', 'candidates.department', 'candidates.session')
        ->orderBy('candidates.position')
        ->get()
        ->map(function ($c) use ($fullPosition) {
            if ($c->position === 'REP') {
                $dept = strtoupper($c->department ?? 'Unknown Department');
                $sess = ucfirst($c->session ?? 'Unknown Session');
                $c->position_full = "$dept Representative $sess";
            } else {
                $c->position_full = $fullPosition[$c->position];
            }
            return $c; 
        })
        ->groupBy('position') // Group candidates by their position
        ->map(function ($group) {
            return $group->sortByDesc('total_votes'); // Sort each group by votes in descending order
        })
        ->flatten();

        return view('admin', compact('totalVoters', 'studentsVoted', 'candidatesNo', 'candidates'));
    }

    public function present()
{
    $admins = Admin::all(); // Fetch all admins from the database
    return view('accounts', compact('admins')); // Replace with actual view name
}

public function store(Request $request){
        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'admin_id' => 'required|string',
            'rfid' => 'required|string|unique:admins,rfid',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        if (\DB::table('voters')->where('student_id', $validated['admin_id'])->exists()) {
        return back()->withErrors(['admin_id' => 'This ID is already used by a Voter.'])->withInput();
    }   

        if (\DB::table('voters')->where('rfid', $validated['rfid'])->exists()) {
        return back()->withErrors(['rfid' => 'This RFID is already used by a Voter.'])->withInput();
    }

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/candidates'), $filename);
            $validated['photo'] = $filename;
        }

        Admin::create($validated);

        return redirect()->back()->with('success', 'Administrator Registered Successfully.');
    }

    public function destroy($id){
        $admin = Admin::findOrFail($id);

        if ($admin->photo) {
        $photoPath = public_path('uploads/admin/' . $admin->photo);
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }

        $admin->delete();

        return redirect()->back()->with('success', 'Administrator deleted successfully.');
    }

    public function update(Request $request, $id){
        $admins = Admin::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'admin_id' => 'required|unique:admins,admin_id,' . $id,
            'rfid' => 'required|unique:admins,rfid,' . $id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ],[
            'admin_id.unique' => 'This Student ID is already registered.',
            'rfid.unique' => 'This RFID is already registered.',
        ]);

        if (\DB::table('voters')->where('student_id', $validated['admin_id'])->exists()) {
        return back()->withErrors(['admin_id' => 'This ID is already used by a voter.'])->withInput();
        }

        if (\DB::table('voters')->where('rfid', $validated['rfid'])->exists()) {
        return back()->withErrors(['rfid' => 'This RFID is already used by a voter.'])->withInput();
        }

        if ($request->hasFile('photo')) {
        // Delete old photo
            if ($admins->photo && file_exists(public_path('uploads/admin/' . $admins->photo))) {
                unlink(public_path('uploads/admin/' . $admins->photo));
            }

            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/admin'), $filename);
            $validated['photo'] = $filename;
        }

        $admins->update($validated);

        return redirect()->back()->with('success', 'Administrator updated successfully.');
    }

    public function choose()
{
    return view('admin.choose');
}

}