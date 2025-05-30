<script type="text/javascript">
    var gk_isXlsx = false;
    var gk_xlsxFileLookup = {};
    var gk_fileData = {};
    function filledCell(cell) {
      return cell !== '' && cell != null;
    }
    function loadFileData(filename) {
    if (gk_isXlsx && gk_xlsxFileLookup[filename]) {
        try {
            var workbook = XLSX.read(gk_fileData[filename], { type: 'base64' });
            var firstSheetName = workbook.SheetNames[0];
            var worksheet = workbook.Sheets[firstSheetName];

            // Convert sheet to JSON to filter blank rows
            var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1, blankrows: false, defval: '' });
            // Filter out blank rows (rows where all cells are empty, null, or undefined)
            var filteredData = jsonData.filter(row => row.some(filledCell));

            // Heuristic to find the header row by ignoring rows with fewer filled cells than the next row
            var headerRowIndex = filteredData.findIndex((row, index) =>
              row.filter(filledCell).length >= filteredData[index + 1]?.filter(filledCell).length
            );
            // Fallback
            if (headerRowIndex === -1 || headerRowIndex > 25) {
              headerRowIndex = 0;
            }

            // Convert filtered JSON back to CSV
            var csv = XLSX.utils.aoa_to_sheet(filteredData.slice(headerRowIndex)); // Create a new sheet from filtered array of arrays
            csv = XLSX.utils.sheet_to_csv(csv, { header: 1 });
            return csv;
        } catch (e) {
            console.error(e);
            return "";
        }
    }
    return gk_fileData[filename] || "";
    }
    </script>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTU-DANAO ONLINE VOTING SYSTEM - Voting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

* {
    font-family: "Inter", sans-serif;
    font-optical-sizing: auto;
    font-weight: 300;
  }

            .custom-border {
            border: 2px solid #1e40af;
            border-radius: 10px;
        }
        .candidate-button {
            background-color: #f3f4f6;
            border-radius: 20px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .candidate-button.selected {
            background-color: #3b82f6;
            color: white;
        }
        .candidate-button:hover {
            background-color: #e5e7eb;
        }
        .logout-button, .submit-button {
            background-color: #1e40af;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .logout-button:hover, .submit-button:hover {
            background-color: #1e3a8a;
        }
        .centered-title {
            text-align: center;
        }
.w-1\/6 {
    position: fixed; /* Changed from sticky to fixed */
    top: 0;
    left: 0;
    width: 16.666667%; /* Equivalent to w-1/6 */
    height: 100vh; /* Full viewport height */
    min-height: 0; /* Remove min-height to avoid conflicts */
}
.flex-1 {
    margin-left: 16.666667%; /* Match the sidebar's width (w-1/6) */
}


@media (max-width: 768px) {
    .flex.h-screen {
        flex-direction: column;
        height: auto;
    }
    .w-1\/6 {
        position: static !important;
        width: 100% !important;
        height: auto !important;
    }
    .flex-1 {
        margin-left: 0 !important;
    }
    .flex-1.p-4.flex.flex-col {
        padding: 1rem !important;
    }
    .grid-cols-3 {
        grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
    }
    .candidate-button {
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100% !important; /* Ensure full width for all candidate buttons */
        box-sizing: border-box; /* Ensure padding is included in width */
    }
    .flex.gap-4.justify-center {
        flex-direction: column;
        align-items: stretch; /* Stretch items to full width */
    }
    .logout-button, .submit-button {
        width: 100%;
        text-align: center;
    }
    .flex.justify-end.mt-4.pb-1 {
        justify-content: center !important;
    }
}
.candidate-button {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.5s ease, transform 0.5s ease;
}

/* When the class 'show' is added, animate to visible */
.candidate-button.show {
  opacity: 1;
  transform: translateY(0);
}

.logout-button, .submit-button {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.logout-button.show, .submit-button.show {
  opacity: 1;
  transform: translateY(0);
}
/* Enhance existing candidate button animation */
.candidate-button {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
  transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1),
              transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.candidate-button.show {
  opacity: 1;
  transform: translateY(0) scale(1);
}

/* Logout and submit buttons: fade in + scale up */
.logout-button, .submit-button {
  opacity: 0;
  transform: translateY(20px) scale(0.8);
  transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1),
              transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.logout-button.show, .submit-button.show {
  opacity: 1;
  transform: translateY(0) scale(1);
}

    </style>
</head>
<body>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-1/6 bg-gray-200 flex flex-col justify-between h-full">
            <div>
                <div class="flex items-center mb-4 pt-4 p-2">
                    <img src="{{ asset('uploads/voters/' . Auth::user()->photo) }}" alt="Profile" class="rounded-full w-10 h-10" style="margin-right:10px">
                    <div>
                        <p class="font-semibold">{{ Auth::user()->first_name }} {{ substr(Auth::user()->middle_name, 0, 1) }}. {{ Auth::user()->last_name }}</p>
                        <p style="font-size: 13px; class="text-gray-600">{{ Auth::user()->department_full }} - {{ Auth::user()->session === 'day' ? 'Day' : 'Night'}}</p>
                    </div>
                </div>
                <nav class="flex flex-col gap-2">
                    <a href="#" class="w-full px-4 py-2 flex items-center gap-2 bg-[#1e40af] text-white">
                      <i class="fa fa-tachometer-alt"></i>
                      Candidate Positions
                    </a>
                  </nav>
            </div>
            <form action="/logout" method="POST">
                @csrf
                <div class="p-2">
                    <button class="w-full logout-button">Logout</button>
                </div>
                
            </form>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-center mb-3">
                <img src="{{ URL('images/logo.png') }}" alt="Logo" class="mr-2 w-12 h-12">
                <h1 class="text-2xl font-bold text-blue-900">CTU-DANAO ONLINE VOTING SYSTEM</h1>
            </div>
            <div class="flex justify-center mb-3">
                <p>Cast your vote for the upcoming student officers.</p>
            </div>

            
            <div class="bg-white custom-border p-4 mb-4">
                <h2 class="text-xl font-bold text-blue-900 mb-2 centered-title">President</h2>
                <div class="flex gap-4 justify-center">
                
                @foreach($presidents as $candidate)
                    <div class="candidate-button" onclick="toggleSelection(this, 'President', {{ $candidate->id }})">
                        <img src="{{ asset('uploads/candidates/' . $candidate->photo) }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>{{ $candidate->first_name }} {{ substr($candidate->middle_name,0,1) }}. {{ $candidate->last_name }}</span>
                    </div>
                @endforeach
                </div>
            </div> 

            <div class="bg-white custom-border p-4 mb-4">
                <h2 class="text-xl font-bold text-blue-900 mb-2 centered-title">Vice President</h2>
                <div class="flex gap-4 justify-center">
                    @foreach($vicePresidents as $candidate)
                    <div class="candidate-button" onclick="toggleSelection(this, 'VP', {{ $candidate->id }})">
                        <img src="{{ asset('uploads/candidates/' . $candidate->photo) }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>{{ $candidate->first_name }} {{ substr($candidate->middle_name,0,1) }}. {{ $candidate->last_name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white custom-border p-4 mb-4">
                <h2 class="text-xl font-bold text-blue-900 mb-2 centered-title">Officers</h2>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">General Secretary</h3>
                        @if ($generalSecretary->isNotEmpty())
                        @foreach($generalSecretary as $candidate)
                    <div class="candidate-button mt-2" onclick="toggleSelection(this, 'General Secretary', {{ $candidate->id }})">
                        <img src="{{ asset('uploads/candidates/' . $candidate->photo) }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>{{ $candidate->first_name }} {{ substr($candidate->middle_name,0,1) }}. {{ $candidate->last_name }}</span>
                    </div>
                    @endforeach
                    @else
                    <p>No General Secretary available.</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Finance Secretary</h3>
                        @if ($financeSecretary->isNotEmpty())
                        @foreach($financeSecretary as $candidate)
                    <div class="candidate-button mt-2" onclick="toggleSelection(this, 'Finance Secretary', {{ $candidate->id }})">
                        <img src="{{ asset('uploads/candidates/' . $candidate->photo) }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>{{ $candidate->first_name }} {{ substr($candidate->middle_name,0,1) }}. {{ $candidate->last_name }}</span>
                    </div>
                    @endforeach
                    @else
                <p>No Finance Secretary available.</p>
                    @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Auditor</h3>
                        @if ($auditor->isNotEmpty())
                        @foreach($auditor as $candidate)
                    <div class="candidate-button mt-2" onclick="toggleSelection(this, 'Auditor', {{ $candidate->id }})">
                        <img src="{{ asset('uploads/candidates/' . $candidate->photo) }}" alt="Profile" class="rounded-full w-8 h-8">
                        <span>{{ $candidate->first_name }} {{ substr($candidate->middle_name,0,1) }}. {{ $candidate->last_name }}</span>
                    </div>
                    @endforeach
                    @else
                <p>No Auditor available.</p>
                @endif
                    </div>
                </div>
            </div>

            <div class="bg-white custom-border p-4 mb-4">
                <h2 class="text-xl font-bold text-blue-900 mb-2 centered-title">Representative</h2>
                <div class="flex items-center justify-center gap-4 flex-wrap">

                    <div>
                    @if ($representatives->isNotEmpty())
                                            <h3 class="text-lg font-semibold text-blue-900 mb-2">
                            {{ Auth::user()->department_full }} Representative ({{ $candidate->session === 'day' ? 'Day' : 'Night' }})
                        </h3>    
                    @foreach($representatives as $candidate)                 
                        <div class="candidate-button mt-2" onclick="toggleSelection(this, 'Representative', {{ $candidate->id }})">
                            <img src="{{ asset('uploads/candidates/' . $candidate->photo) }}" alt="Profile" class="rounded-full w-8 h-8">
                            <span>{{ $candidate->first_name }} {{ substr($candidate->middle_name,0,1) }}. {{ $candidate->last_name }}</span>
                    </div>
                    @endforeach
                @else
                <p>No representatives available.</p>
                    @endif
                </div>
            </div>
            </div>


            <!-- Submit Vote Button -->
            <div class="flex justify-end pb-1">
                <button class="submit-button">Submit Vote</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedCandidates = {};

        function toggleSelection(element, position, candidateId) {
            const buttons = document.querySelectorAll(`.candidate-button[onclick*="${position}"]`);
            
            const candidateName = element.querySelector('span').innerText;

            if (selectedCandidates[position] === candidateId) {
                delete selectedCandidates[position];
                element.classList.remove('selected');
            } else {
                buttons.forEach(btn => btn.classList.remove('selected'));
                selectedCandidates[position] = {
                        id: candidateId,
                        name: candidateName
                    };
                element.classList.add('selected');
            }
        }


document.querySelector('.submit-button').addEventListener('click', function () {
    if (Object.keys(selectedCandidates).length === 0) {
        Swal.fire('Error', 'Please select at least one candidate.', 'error');
        return;
    }

    let summaryHtml = '';
    for (let [position, data] of Object.entries(selectedCandidates)) {
        summaryHtml += `<p><strong>${position}:</strong> ${data.name}</p>`;
    }

    Swal.fire({
        title: 'Confirm Your Vote',
        html: summaryHtml,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Submit Vote',
        cancelButtonText: 'Review'
    }).then((result) => {
        if (result.isConfirmed) {
            submitVote(); // Call actual vote submission function
        }
    });
});

function submitVote() {
    fetch('/vote', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            selections: Object.fromEntries(
                Object.entries(selectedCandidates).map(([position, data]) => [position, data.id])
            )
        })
    })
    .then(res => {
        if (res.status === 403) {
            Swal.fire('Error', 'You have already voted.', 'error').then(() => {
                window.location.href = '/login';
            });
        } else if (res.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Voted successfully!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                // After showing success message, logout the user
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';

                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            });
        } else {
            Swal.fire('Error', 'Something went wrong.', 'error');
        }
    });
}


window.addEventListener('DOMContentLoaded', () => {
  // Animate candidate buttons
  const buttons = document.querySelectorAll('.candidate-button');
  buttons.forEach((btn, i) => {
    setTimeout(() => {
      btn.classList.add('show');
    }, i * 1);
  });

  // Animate logout and submit buttons after a short delay
  setTimeout(() => {
    document.querySelector('.logout-button').classList.add('show');
    document.querySelector('.submit-button').classList.add('show');
  }, buttons.length * 25 + 50);
});

window.addEventListener('DOMContentLoaded', () => {
  // Animate candidate buttons with stagger and easing
  const buttons = document.querySelectorAll('.candidate-button');
  buttons.forEach((btn, i) => {
    setTimeout(() => {
      btn.classList.add('show');
    }, i * 100); // increased delay for visible stagger
  });

  // Animate logout and submit buttons with separate delay and scale effect
  setTimeout(() => {
    document.querySelector('.logout-button').classList.add('show');
  }, buttons.length * 100 + 100);

  setTimeout(() => {
    document.querySelector('.submit-button').classList.add('show');
  }, buttons.length * 100 + 300);
});
    </script>
</body>
</html>