<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function index()
{
    $voter = Auth::guard('web')->user();    // default guard for voters
    $admin = Auth::guard('admin')->user();  // admin guard

    // Decide which user you want info from
    $user = $voter ?? $admin;

    if (!$user) {
        // Not authenticated, redirect or show error
        return redirect()->route('login');
    }

    // Then safely get department and session, check for null first
    $voterDept = $user->department ?? null;
    $voterSession = $user->session ?? null;

    // Now use $voterDept and $voterSession safely for representatives
    $representatives = Candidate::where('position', 'REP')
        ->when($voterDept, function ($query, $dept) {
            return $query->where('department', $dept);
        })
        ->when($voterSession, function ($query, $session) {
            return $query->where('session', $session);
        })
        ->get();

    // Other positions as before
    $presidents = Candidate::where('position', 'P')->get();
    $vicePresidents = Candidate::where('position', 'VP')->get();
    $generalSecretary = Candidate::where('position', 'GSEC')->get();
    $financeSecretary = Candidate::where('position', 'FSEC')->get();
    $auditor = Candidate::where('position', 'AUD')->get();

    return view('voting', compact(
        'presidents', 'vicePresidents', 'representatives',
        'generalSecretary', 'financeSecretary', 'auditor',
        'voterDept', 'voterSession'
    ));
}

    public function store(Request $request){
        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'position' => 'required|string',
            'department' => 'required|string',
            'session' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        if($request->hasFile('photo')){
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/candidates'), $filename);
            $validated['photo'] = $filename;
        }

        Candidate::create($validated);

        return redirect()->back()->with('success', 'Candidate Registered Successfully.');
    }

    public function present(){

        $fullPosition = [
        'P' => 'President',
        'VP' => 'Vice President',
        'GSEC' => 'General Secretary',
        'FSEC' => 'Finance Secretary',
        'AUD' => 'Auditor',
        'REP' => 'Representative'
    ];
        $positionOrder = ['P', 'VP', 'GSEC', 'FSEC', 'AUD', 'REP'];

        $candidates = Candidate::all()->map(function ($candidate) use($fullPosition){

            if($candidate->position === 'REP'){
                $dept = strtoupper($candidate->department);
                $sess = ucfirst($candidate->session);
                $candidate->position_full = "$dept Representative $sess";
            } else {
                $candidate->position_full = $fullPosition[$candidate->position];
            }
            return $candidate;
    });

    $sortedCandidates = $candidates->sort(function ($a, $b) use ($positionOrder) {
        $aPos = array_search($a->position, $positionOrder);
        $bPos = array_search($b->position, $positionOrder);

        if ($aPos !== $bPos) {
            return $aPos <=> $bPos;
        }

        // For Representatives only: sort by department then session
        if ($a->position === 'REP' && $b->position === 'REP') {
            $deptCompare = strcmp(strtoupper($a->department), strtoupper($b->department));
            if ($deptCompare !== 0) {
                return $deptCompare;
            }
            return strcmp(strtolower($a->session), strtolower($b->session));
        }

        return 0; // Same position and not REP
    });

    return view('candidates', ['candidates' => $sortedCandidates]);
}

    public function update(Request $request, $id){
        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'position' => 'required|string',
            'department' => 'required|string',
            'session' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
        // Delete old photo
            if ($candidate->photo && file_exists(public_path('uploads/candidates/' . $candidate->photo))) {
                unlink(public_path('uploads/candidates/' . $candidate->photo));
            }

            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/candidates'), $filename);
            $validated['photo'] = $filename;
        }

        $candidate->update($validated);

        return redirect()->back()->with('success', 'Candidate updated successfully.');
    }



    public function destroy($id){
        $candidate = Candidate::findOrFail($id);

        if ($candidate->photo) {
        $photoPath = public_path('uploads/candidates/' . $candidate->photo);
        if (file_exists($photoPath)) {
            unlink($photoPath);
        }
    }

        $candidate->delete();

        return redirect()->back()->with('success', 'Candidate deleted successfully.');
    }
}
