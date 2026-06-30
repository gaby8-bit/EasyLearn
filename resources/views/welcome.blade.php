<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easylearn</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-blue-600">Easylearn</h1>
        <div class="space-x-4">

            <a href="{{ route('contact') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Contact</a>
        </div>
    </nav>

    <!-- Hero section -->
    <section class="text-center py-20 bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
        <h2 class="text-4xl font-bold mb-4">Bun venit la Easylearn</h2>
        <p class="text-lg mb-6">Incarca documente, obtine rezumate si creeaza quiz-uri pentru a invata mai usor 📚</p>
        <a href="{{ route('register') }}"
            class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
            Creeaza-ti un cont
        </a>

        <a href="{{ route('login') }}" style="margin-left:20px;"
            class=" bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">Login</a>
    </section>

    <!-- Footer -->
    <footer class="bg-white shadow p-6 text-center text-gray-600">
        © 2025 Easylearn. Toate drepturile rezervate.
    </footer>

</body>

</html>