<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Real-Time Chat</title>

    @vite(['resources/js/app.js'])
</head>

<body>
    <div class="chat-page">
        <div class="chat-container">
            <div class="chat-header">
                <h1>Galih post message</h1>
                <p>message - pesan real-time</p>
            </div>

            <div class="chat-body">
                <div class="chat-sidebar">
                    <div class="form-group">
                        <label for="sender">Login sebagai</label>
                        <select id="sender">
                            <option value="">Pilih user</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="receiver">Kirim ke</label>
                        <select id="receiver">
                            <option value="">Pilih penerima</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <p class="status">
                        Buka halaman ini di dua browser berbeda. Pilih User 1 di browser pertama dan User 2 di browser kedua.
                    </p>
                </div>

                <div class="chat-main">
                    <div id="chatBox" class="chat-box">
                        <p class="status">Pilih user dan penerima untuk mulai chat.</p>
                    </div>

                    <form id="chatForm" class="chat-form">
                        @csrf
                        <input type="text" id="messageInput" placeholder="Tulis pesan..." autocomplete="off">
                        <button type="submit">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const senderSelect = document.getElementById('sender');
        const receiverSelect = document.getElementById('receiver');
        const chatBox = document.getElementById('chatBox');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        function getSelectedSenderId() {
            return senderSelect.value;
        }

        function getSelectedReceiverId() {
            return receiverSelect.value;
        }

        function appendMessage(message) {
            const currentSenderId = getSelectedSenderId();

            const row = document.createElement('div');
            row.classList.add('message-row');

            if (message.sender_id == currentSenderId) {
                row.classList.add('me');
            } else {
                row.classList.add('other');
            }

            row.innerHTML = `
                <div class="message-bubble">
                    <div class="message-name">${message.sender.name}</div>
                    <div>${message.message}</div>
                </div>
            `;

            chatBox.appendChild(row);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function loadMessages() {
            const senderId = getSelectedSenderId();
            const receiverId = getSelectedReceiverId();

            if (!senderId || !receiverId) {
                return;
            }

            if (senderId === receiverId) {
                chatBox.innerHTML = '<p class="status">User pengirim dan penerima tidak boleh sama.</p>';
                return;
            }

            fetch(`/messages?sender_id=${senderId}&receiver_id=${receiverId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengambil pesan dari server.');
                    }

                    return response.json();
                })
                .then(messages => {
                    chatBox.innerHTML = '';

                    if (messages.length === 0) {
                        chatBox.innerHTML = '<p class="status">Belum ada pesan. Mulai chat sekarang.</p>';
                        return;
                    }

                    messages.forEach(message => {
                        appendMessage(message);
                    });
                })
                .catch(error => {
                    console.error(error);
                    chatBox.innerHTML = '<p class="status">Gagal memuat pesan. Cek terminal Laravel.</p>';
                });
        }

        senderSelect.addEventListener('change', loadMessages);
        receiverSelect.addEventListener('change', loadMessages);

        chatForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const senderId = getSelectedSenderId();
            const receiverId = getSelectedReceiverId();
            const message = messageInput.value.trim();

            if (!senderId || !receiverId) {
                alert('Pilih user pengirim dan penerima dulu.');
                return;
            }

            if (senderId === receiverId) {
                alert('User pengirim dan penerima tidak boleh sama.');
                return;
            }

            if (message === '') {
                return;
            }

            fetch('/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({
                        sender_id: senderId,
                        receiver_id: receiverId,
                        message: message,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal mengirim pesan ke server.');
                    }

                    return response.json();
                })
                .then(data => {
                    if (chatBox.querySelector('.status')) {
                        chatBox.innerHTML = '';
                    }

                    appendMessage(data);
                    messageInput.value = '';
                })
                .catch(error => {
                    console.error(error);
                    alert('Pesan gagal dikirim. Cek terminal Laravel dan Console browser.');
                });
        });

        window.addEventListener('load', function() {
            if (!window.Echo) {
                console.error('Laravel Echo belum siap. Pastikan npm run dev sedang jalan.');
                return;
            }

            window.Echo.channel('chat')
                .listen('.message.sent', function(event) {
                    const senderId = getSelectedSenderId();
                    const receiverId = getSelectedReceiverId();

                    if (!senderId || !receiverId) {
                        return;
                    }

                    const incomingMessage = event.message;

                    const isCurrentChat =
                        (incomingMessage.sender_id == senderId && incomingMessage.receiver_id == receiverId) ||
                        (incomingMessage.sender_id == receiverId && incomingMessage.receiver_id == senderId);

                    const isNotOwnMessage = incomingMessage.sender_id != senderId;

                    if (isCurrentChat && isNotOwnMessage) {
                        if (chatBox.querySelector('.status')) {
                            chatBox.innerHTML = '';
                        }

                        appendMessage(incomingMessage);
                    }
                });
        });
    </script>
</body>

</html>