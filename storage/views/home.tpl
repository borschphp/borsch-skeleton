<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Borsch-Skeleton</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" type="image/png" href="/images/logo.png" />
    </head>
    <body class="bg-slate-50">
        <div class="grid h-screen place-items-center">
            <div class="w-2/3 max-w-7xl">
                <img src="/images/logo.png"  class="h-48 mx-auto" alt="Logo" />
                <div class="bg-white py-8 rounded drop-shadow-md">
                    <div class="text-center">
                        <div class="max-w-none">
                            <div class="border-gray border-b-2 pb-8 px-8">
                                {% if ($name == 'World'): }
                                    <h1 class="text-5xl font-bold mb-2">Hello there.</h1>
                                    <form method="GET">
                                        <input
                                                type="text"
                                                name="name"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="What's your name ?"
                                                autocomplete="false"
                                        />
                                    </form>
                                {% else: }
                                    <h1 class="text-5xl font-bold">Hello, {$name} !</h1>
                                {% endif; }
                            </div>
                            <p class="p-8">
                                <span class="underline">Simple and efficient</span> PSR-15 micro framework.
                            </p>
                            <a
                                    href="https://borsch-documentation.onrender.com"
                                    target="_blank"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded"
                            >
                                Documentation
                            </a>
                        </div>
                    </div>
                </div>
                <div class="flex text-muted text-slate-400 mt-4">
                    <div class="w-1/2">
                        <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="inline align-sub"
                        >
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                        <a href="https://github.com/borschphp/borsch-skeleton" target="_blank">
                            Github
                        </a>
                    </div>
                    <div class="w-1/2 text-right">
                        App {env('APP_NAME')} {env('APP_VERSION')} (PHP v{phpversion()})<br>
                        Rendered in {round(microtime(true) - __START_TIME__, 5)} seconds
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>