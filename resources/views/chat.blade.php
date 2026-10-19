
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Groq Chatbot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-900 text-gray-100 h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-gray-800 border-b border-gray-700 p-4 text-center shadow-md">
        <h1 class="text-xl font-bold text-orange-400">
            Laravel Groq Chatbot
        </h1>
        <p class="text-xs text-gray-400">
            Powered by Groq AI
        </p>
    </header>

    <!-- Chat Container -->
    <div id="chat-container"
        class="flex-1 overflow-y-auto p-4 space-y-4 max-w-3xl w-full mx-auto">

        <div class="flex justify-start">
            <div class="bg-gray-800 rounded-lg p-3 max-w-lg text-sm text-gray-200 shadow">
                Halo! Ada yang bisa saya bantu hari ini?
            </div>
        </div>

    </div>

    <!-- Input Form -->
    <div class="bg-gray-800 border-t border-gray-700 p-4">
        <form id="chat-form" class="max-w-3xl mx-auto flex gap-2">
            @csrf

            <input
                type="text"
                id="message-input"
                placeholder="Ketik pesan Anda di sini..."
                required
                autocomplete="off"
                class="flex-1 min-w-0 bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500">

            <button
                type="submit"
                id="send-button"
                class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white px-6 py-2 rounded-lg font-medium transition">
                Kirim
            </button>
        </form>
    </div>

    <!-- JavaScript -->
    <script>
        const chatContainer = document.getElementById('chat-container');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');

        chatForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const text = messageInput.value.trim();

            if (!text) return;

            appendMessage(text, 'user');

            messageInput.value = '';
            messageInput.disabled = true;
            sendButton.disabled = true;

            const loadingId = appendLoading();

            try {
                const res = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: text
                    })
                });

                const raw = await res.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch {
                    data = {
                        error: raw || 'Respons server kosong.'
                    };
                }

                removeLoading(loadingId);

                if (res.ok) {
                    appendMessage(
                        data.reply || 'AI tidak memberikan jawaban.',
                        'bot',
                        data.model_used
                    );
                } else {
                    console.error('HTTP Error:', res.status, data);

                    appendMessage(
                        `Error ${res.status}: ${
                            data.message ||
                            data.error ||
                            'Permintaan gagal.'
                        }`,
                        'bot'
                    );
                }

            } catch (error) {
                removeLoading(loadingId);

                console.error('Fetch error:', error);

                appendMessage(
                    'Gagal terhubung ke server. Periksa koneksi Laravel.',
                    'bot'
                );

            } finally {
                messageInput.disabled = false;
                sendButton.disabled = false;
                messageInput.focus();
            }
        });

        function appendMessage(text, sender, model = null) {
            const isUser = sender === 'user';
            const wrapper = document.createElement('div');

            wrapper.className = `flex ${
                isUser ? 'justify-end' : 'justify-start'
            }`;

            const bubble = document.createElement('div');

            bubble.className = `${
                isUser
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-800 text-gray-200'
            } rounded-lg p-3 max-w-lg text-sm shadow whitespace-pre-wrap break-words`;

            // Tampilkan pesan sebagai teks biasa, bukan HTML.
            const messageText = document.createElement('div');
            messageText.textContent = text;
            bubble.appendChild(messageText);

            if (model) {
                const badge = document.createElement('div');

                badge.className = 'text-[10px] text-gray-400 mt-1';
                badge.textContent = `Model: ${model}`;

                bubble.appendChild(badge);
            }

            wrapper.appendChild(bubble);
            chatContainer.appendChild(wrapper);

            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function appendLoading() {
            const id = 'loading-' + Date.now();
            const wrapper = document.createElement('div');

            wrapper.id = id;
            wrapper.className = 'flex justify-start';

            const bubble = document.createElement('div');

            bubble.className =
                'bg-gray-800 text-gray-400 rounded-lg p-3 text-sm shadow animate-pulse';

            bubble.textContent = 'Sedang berpikir...';

            wrapper.appendChild(bubble);
            chatContainer.appendChild(wrapper);

            chatContainer.scrollTop = chatContainer.scrollHeight;

            return id;
        }

        function removeLoading(id) {
            const element = document.getElementById(id);

            if (element) {
                element.remove();
            }
        }
    </script>

</body>
</html>
