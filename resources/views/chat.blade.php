<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Groq Chatbot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 text-gray-100 h-screen flex flex-col justify-between">

    <!-- Header -->
    <header class="bg-gray-800 border-b border-gray-700 p-4 text-center shadow-md">
        <h1 class="text-xl font-bold text-orange-400">Laravel Groq Chatbot</h1>
<p class="text-xs text-gray-400">Powered by Groq AI (Llama 3)</p>
    </header>

    <!-- Chat Container -->
    <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 max-w-3xl w-full mx-auto">
        <div class="flex justify-start">
            <div class="bg-gray-800 rounded-lg p-3 max-w-lg text-sm text-gray-200 shadow">
                Halo Bil! Ada yang bisa saya bantu hari ini?
            </div>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-gray-800 border-t border-gray-700 p-4">
        <form id="chat-form" class="max-w-3xl mx-auto flex gap-2">
            @csrf
            <input type="text" id="message-input" placeholder="Ketik pesan Anda di sini..." required
                   class="flex-1 bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500">
            <button type="submit" id="send-button"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Kirim
            </button>
        </form>
    </div>

    <!-- JavaScript Chat Logic -->
    <script>
        const chatContainer = document.getElementById('chat-container');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            appendMessage(text, 'user');
            messageInput.value = '';
            messageInput.disabled = true;
            sendButton.disabled = true;

            const loadingId = appendLoading();

            try {
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: text })
                });

                const data = await response.json();
                removeLoading(loadingId);

                if (response.ok) {
                    appendMessage(data.reply, 'bot', data.model_used);
                } else {
                    appendMessage(data.error || 'Terjadi kesalahan pada server.', 'bot');
                }
            } catch (error) {
                removeLoading(loadingId);
                appendMessage('Gagal terhubung ke server.', 'bot');
            } finally {
                messageInput.disabled = false;
                sendButton.disabled = false;
                messageInput.focus();
            }
        });

        function appendMessage(text, sender, model = null) {
            const isUser = sender === 'user';
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${isUser ? 'justify-end' : 'justify-start'}`;

            let modelBadge = model ? `<div class="text-[10px] text-gray-400 mt-1 text-right">Model: ${model}</div>` : '';

            wrapper.innerHTML = `
                <div class="${isUser ? 'bg-blue-600 text-white' : 'bg-gray-800 text-gray-200'} rounded-lg p-3 max-w-lg text-sm shadow">
                    <div>${escapeHtml(text)}</div>
                    ${modelBadge}
                </div>
            `;
            chatContainer.appendChild(wrapper);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function appendLoading() {
            const id = 'loading-' + Date.now();
            const wrapper = document.createElement('div');
            wrapper.id = id;
            wrapper.className = 'flex justify-start';
            wrapper.innerHTML = `
                <div class="bg-gray-800 text-gray-400 rounded-lg p-3 text-sm shadow animate-pulse">
                    Sedang berpikir...
                </div>
            `;
            chatContainer.appendChild(wrapper);
            chatContainer.scrollTop = chatContainer.scrollHeight;
            return id;
        }

        function removeLoading(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }

        function escapeHtml(text) {
            return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }
    </script>
</body>
</html>
