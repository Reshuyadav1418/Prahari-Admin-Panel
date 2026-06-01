<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Prahari App</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
:root{
    --primary:#1e293b; /* Dark Slate Blue for text/icons */
    --accent: #6366f1;
}

body{
    margin:0;
    height:100vh;
    font-family:'Inter',sans-serif;
    background:#ffffff;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    position: relative;
}

/* REGISTER BUTTON */
.register-btn{
    position:fixed;
    top:20px;
    right:40px;
    padding:10px 22px;
    border-radius:999px;
    font-weight:600;
    text-decoration:none;
    background:#e1bb80;
    color:#fff;
    display:flex;
    align-items:center;
    gap:6px;
    box-shadow:0 10px 25px rgba(99,102,241,0.3);
    z-index: 100;
}

/* MAIN CONTENT */
.content-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.logo img {
    width: 140px;
    margin-bottom: 20px;
}

h1 {
    color: var(--primary);
    font-weight: 800;
    font-size: 28px;
    letter-spacing: 1px;
    margin: 0;
    text-transform: uppercase;
}

p.tagline {
    color: #475569;
    font-weight: 500;
    font-size: 15px;
    margin-top: 10px;
}

/* CAROUSEL DOTS */
.dots {
    position: absolute;
    bottom: 50px;
    display: flex;
    gap: 8px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #cbd5e1;
}

.dot.active {
    background-color: var(--primary);
}
</style>
</head>

<body>

<!-- REGISTER BUTTON -->
<a href="{{ route('signin') }}" class="register-btn">
    Login Admin <i class="ph ph-arrow-right"></i>
</a>

<!-- MAIN CONTENT -->
<div class="content-wrapper">
    <div class="logo">
        <img src="/images/prahari-logo.png" alt="Prahari Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
        <i class="ph-fill ph-shield-check" style="font-size: 120px; color: #1e293b; display: none;"></i>
    </div>
    <h1>Prahari</h1>
    <p class="tagline">Safer Society, Stronger Tomorrow</p>
</div>

<!-- CAROUSEL DOTS -->
<div class="dots">
    <div class="dot active"></div>
    <div class="dot"></div>
    <div class="dot"></div>
</div>

</body>
</html>