<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .scale-hover:hover {
            transform: scale(1.05);
        }

        .transition-all {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body style="
background: 
    linear-gradient(135deg, rgba(31, 41, 55, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%),
    url('{{ URL("images/logo.png") }}');
    background-size: cover; background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
">
<div class="fade-in-up" style="
        background-color: white;
        padding: 55px;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: auto;
        animation-delay: 0.1s;
    ">        
    <h1 class="fade-in-up delay-1" style="
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        ">Welcome, {{Auth::user()->first_name}} {{Auth::user()->last_name}}</h1>
    <p class="fade-in-up delay-2" style="
            margin: 8px 0 20px;
            color: #4b5563;
        ">What would you like to do?</p>

    <div style="
        display: flex;
        gap: 16px;
        justify-content: center;
    ">
        <div class="fade-in-up delay-1 scale-hover transition-all" style="
            background-color: #1e3a8a;
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            height: 150px;
            width: 150px;
            opacity: 0;
        " onmouseover="this.style.backgroundColor='#16307d'" onmouseout="this.style.backgroundColor='#1e3a8a'">
            <span style="font-size: 35px; color: white;">  
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" style="width: 50px; height: 40px; stroke: #ffffff;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87 M16 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </span>
            <a href="{{ route('admin') }}" style="margin-top: 8px; color: white;">Go to Admin Dashboard</a>
        </div>

        <div class="fade-in-up delay-2 scale-hover transition-all" style="
            background-color: #FFAF10;
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            height: 150px;
            width: 150px;
            opacity: 0;
        " onmouseover="this.style.backgroundColor='#e69b00'" onmouseout="this.style.backgroundColor='#FFAF10'">
            <span style="font-size: 35px; color: white;">  
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" style="width: 50px; height: 40px; stroke: #ffffff;">  
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m0 0c-4 0-7-2-7-4"/>
                </svg>
            </span>
            <a href="{{ route('voting') }}" style="margin-top: 8px; color: white;">Vote as a Student</a>
        </div>
    </div>
</div>
</body>
</html>
