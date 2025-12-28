<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* 1. Gradient Background Animation */
        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animated-bg {
            background: linear-gradient(-45deg, #535252ff, #dd3229ff, #f08724ff, #e8e7e6ff);
            background-size: 400% 400%;
            animation: gradient-animation 10s ease infinite;
        }

        /* 2. Floating Card Animation */
        @keyframes float {
            0% {
                transform: translateY(0px);
                box-shadow: 0 5px 15px 0px rgba(0,0,0,0.2);
            }
            50% {
                transform: translateY(-20px);
                box-shadow: 0 25px 15px 0px rgba(0,0,0,0.1);
            }
            100% {
                transform: translateY(0px);
                box-shadow: 0 5px 15px 0px rgba(0,0,0,0.2);
            }
        }

        .floating-card {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>

<body class="animated-bg flex items-center justify-center min-h-screen">

    <div class="floating-card w-full max-w-md p-8 space-y-6 bg-white rounded-lg">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900">Join Our Community !</h2>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="registerprocess.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" required
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <button type="submit"
                        class="w-full px-4 py-2 font-medium text-white bg-red-600 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Register
                </button>
            </div>
        </form>
        <p class="text-sm text-center text-gray-600">
            Already have an account?
            <a href="login.php" class="font-medium text-red-600 hover:text-orange-500">Let's Dive in !</a>
        </p>
    </div>

</body>
</html>