<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* GRADIENT BACKGROUND ANIMATION */
        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animated-bg {
            background: linear-gradient(-45deg, #dabd14ff, #f08724ff, #dd3229ff, #c8cacaff);
            background-size: 400% 400%;
            animation: gradient-animation 10s ease infinite;
        }

        /* FLOATING FORM ANIMATION */
        @keyframes float {
            0% {
                transform: translateY(0px);
                box-shadow: 0 5px 15px 0px rgba(0,0,0,0.2);
            }
            50% {
                transform: translateY(-20px); /* Moves up */
                box-shadow: 0 25px 15px 0px rgba(0,0,0,0.1); /* Shadow grows/fades to look realistic */
            }
            100% {
                transform: translateY(0px); /* Moves down */
                box-shadow: 0 5px 15px 0px rgba(0,0,0,0.2);
            }
        }

        .floating-card {
            animation: float 6s ease-in-out infinite; /* Smooth 6-second loop */
        }
    </style>
</head>

<body class="animated-bg flex items-center justify-center min-h-screen">

    <div class="floating-card w-full max-w-md p-8 space-y-6 bg-white rounded-lg"> 
        <div class="text-center">
            <h3 class="text-sm italic font-semibold text-red-600 tracking-wide uppercase mb-1">
                explore our fascinating system !
            </h3>

            <h2 class="text-2xl font-bold text-gray-900">
                Who Are you..?
            </h2>
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

        <form action="loginprocess.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit"
                    class="w-full px-4 py-2 font-medium text-white bg-red-600 rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Log In
                </button>
            </div>
        </form>
        <p class="text-sm text-center text-gray-600">
            Looks like you do have trouble logging in..? 
            <a href="register.php" class="font-medium text-red-600 hover:text-orange-500">Wanna Register ?</a>
        </p>
    </div>

</body>

</html>