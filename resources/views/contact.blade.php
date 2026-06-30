<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <style>
        :root {
            --bg-start: #0f172a;
            --bg-end: #1d4ed8;
            --card: rgba(255, 255, 255, 0.94);
            --text: #0f172a;
            --muted: #475569;
            --border: #dbe4f0;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --success: #15803d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.35), transparent 35%),
                linear-gradient(135deg, var(--bg-start), var(--bg-end));
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 32px 16px;
            gap: 18px;
        }

        .contact-box {
            display: flex;
            background: var(--card);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.28);
            overflow: hidden;
            width: min(100%, 980px);
            min-height: 560px;
        }

        .form-side {
            flex: 0 0 58%;
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 18px;
        }

        .form-side h1 {
            margin: 0;
            font-size: clamp(2rem, 2.8vw, 2.8rem);
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: var(--text);
        }

        .form-side label {
            display: inline-block;
            margin: 8px 0 8px;
            font-weight: 700;
            color: var(--muted);
        }

        .form-side input,
        .form-side textarea {
            width: 100%;
            padding: 14px 16px;
            margin: 0 0 10px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #f8fbff;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .form-side input:focus,
        .form-side textarea:focus {
            border-color: rgba(37, 99, 235, 0.65);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-side button {
            width: 100%;
            padding: 14px 18px;
            border: none;
            border-radius: 16px;
            color: #fff;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .form-side button:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 34px rgba(37, 99, 235, 0.3);
            filter: brightness(1.02);
        }

        .image-side {
            flex: 0 0 40%;
            background:
                linear-gradient(160deg, rgba(37, 99, 235, 0.08), rgba(59, 130, 246, 0.16)),
                #eff6ff;
            justify-content: center;
            align-items: center;
            display: flex;
            padding: 28px;
        }

        .image-side img {
            width: 100%;
            height: auto;
            max-width: 320px;
            filter: drop-shadow(0 18px 24px rgba(15, 23, 42, 0.12));
        }

        .success-message {
            margin: 0;
            padding: 12px 14px;
            border-radius: 14px;
            color: var(--success);
            background: rgba(21, 128, 61, 0.08);
            border: 1px solid rgba(21, 128, 61, 0.15);
        }

        .footer {
            width: min(100%, 980px);
            padding: 8px 12px;
            text-align: center;
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.95rem;
        }

        .back-home {
            width: min(100%, 980px);
        }

        .back-home form {
            margin: 0;
        }

        .back-home a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            padding: 12px 18px;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 700;
            text-decoration: none;
            backdrop-filter: blur(12px);
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .back-home a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        @media (max-width: 860px) {
            .contact-box {
                flex-direction: column;
            }

            .form-side,
            .image-side {
                flex: 1 1 auto;
                width: 100%;
            }

            .form-side {
                padding: 32px 24px;
            }

            .image-side img {
                max-width: 240px;
            }
        }
    </style>
</head>

<body>
    <div class="contact-box">
        <div class="form-side">
            <h1>Trimite o sesizare</h1>
            @if (session('success'))
                <p class="success-message">{{ session('success') }}</p>
            @endif
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <label for="email">Email:</label><br>
                <input type="email" name="email" required><br><br>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <label for="sesizare">Sesizare:</label><br>
                <textarea name="sesizare" rows="5" required></textarea><br><br>
                @error('sesizare')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <button type="submit">Trimite</button>
            </form>
        </div>


        <div class="image-side">
            <img src="{{ asset('plic.jpg') }}">
        </div>
    </div>
    <div class="back-home">
        <a href="{{ route('welcome') }}">Inapoi la home</a>
    </div>
    <footer class="footer">
        © 2025 Easylearn. Toate drepturile rezervate.
    </footer>
</body>

</html>