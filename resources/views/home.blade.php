<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTU-DANAO ONLINE VOTING SYSTEM - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <form action="/login" method="POST">
            @csrf
            <div class="login-box">
                <img src="{{ URL('images/logo.png') }}" alt="Logo" class="logo" />
                <h2>CTU-DANAO ONLINE VOTING SYSTEM</h2>
        
                <div class="mode-toggle" id="modeToggle">
                <button class="toggle-btn active" data-mode="rfid">RFID</button>
                <button class="toggle-btn" data-mode="manual">MANUAL</button>
            </div>
        
                <div class="input-group">
                    <input name="student_id" class="hidden" placeholder="School ID" id="manualInput"/>

                    <input type="text" placeholder="Scan RFID" id="rfidInput" autofocus/>
                    <span class="icon">👤</span>
                </div>

                @if($errors->has('login_error'))
                <div class="text-red-500 text-sm mb-3 font-bold">
                    {{ $errors->first('login_error') }}
                </div>
            @endif
                @if($errors->has('student_id'))
                    <div class="text-red-500 text-sm mb-3 font-bold">
                        {{ $errors->first('student_id') }}
                    </div>
                @endif
                


                <button class="login-btn" type="submit">LOGIN</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js')}}"></script>
    <script>
        const manualBtn = document.querySelector('[data-mode="manual"]');
        const rfidBtn = document.querySelector('[data-mode="rfid"]');
        const manualInput = document.getElementById('manualInput');
        const rfidInput = document.getElementById('rfidInput');
        const form = document.querySelector('form');
    
        // Show RFID by default
        manualInput.classList.add('hidden');
        rfidInput.classList.remove('hidden');
        rfidInput.setAttribute('name', 'rfid');
        manualInput.removeAttribute('name');
    
        manualBtn.addEventListener('click', function (e) {
            e.preventDefault();
            manualInput.classList.remove('hidden');
            manualInput.setAttribute('name', 'student_id');
            rfidInput.classList.add('hidden');
            rfidInput.removeAttribute('name');
    
            manualBtn.classList.add('active');
            rfidBtn.classList.remove('active');
        });
    
        rfidBtn.addEventListener('click', function (e) {
            e.preventDefault();
            rfidInput.classList.remove('hidden');
            rfidInput.setAttribute('name', 'rfid');
            manualInput.classList.add('hidden');
            manualInput.removeAttribute('name');
    
            rfidBtn.classList.add('active');
            manualBtn.classList.remove('active');
        });

        form.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            form.submit();
        }
    });
    
    </script>
    
</body>
</html> 