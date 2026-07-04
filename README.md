# Learning Hub - Platformă Inteligentă de Procesare Documente și Evaluare

## 📋 Descriere

**Learning Hub** este o platformă web care integrează Inteligență Artificială Generativă pentru procesarea și evaluarea automată de documente educaționale.

**Funcționalități:**

- Încărcare documente (PDF, DOCX, TXT)
- Generare rezumate automate (Google Gemini AI)
- Chestionare interactive cu 10 întrebări
- Audio text-to-speech (limba română)
- Istoric și statistici rezultate

**Tech:** Laravel 11 | Tailwind CSS | Google Gemini API | MySQL 8.0

---

## 📦 Livrabilele Proiectului

```
LEARNING HUB/
├── Backend (Laravel 11)
│   ├── app/Http/Controllers/LearningHubController.php (Toate controllerele se ocupa de logica aplicatiei)
│   ├── app/Models/ (User, QuizAttempt, Contact)
│   ├── app/Providers/ (AppServiceProvider)
│   ├── routes/ (web.php, auth.php)
│   ├── database/migrations/ (schema MySQL)
│   └── config/ (app, auth, database, session)
│
├── Frontend (Tailwind + Vanilla JS)
│   ├── resources/views/learning-hub/index.blade.php
│   ├── resources/views/auth/ (login, register, verify)
│   ├── resources/css/app.css (Tailwind styles)
│   ├── resources/js/app.js (JavaScript interactiv)
│   └── resources/layouts/ (Blade layouts)
│
├── Bază de Date (MySQL 8.0)
│   ├── users (utilizatori înregistrați)
│   ├── quiz_attempts (rezultate teste)
│   ├── contacts (mesaje feedback)
│   ├── sessions (sesiuni active)
│   └── password_reset_tokens (recovery)
│
├── Stocare
│   ├── storage/app/public/learning-hub-documents/
│   └── storage/app/public/learning-hub-audio/
│
└── Documentație
    ├── README.md (descriere livrabile - acest fișier)
    ├── Instructiuni_rulare.txt (setup și instalare detaliată)
    ├── .env (variabile de mediu)
    └── composer.json + package.json (dependențe)
```

---
