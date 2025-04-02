document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.querySelector('.chatbot-toggle');
    const chatBox = document.querySelector('.chatbot-box');
    const chatClose = document.querySelector('.chatbot-close');
    const chatMessages = document.querySelector('.chatbot-messages');
    const chatInput = document.querySelector('#chatInput');
    const sendButton = document.querySelector('#sendMessage');

    // Toggle chatbot
    chatToggle.addEventListener('click', () => {
        chatBox.classList.add('active');
    });

    chatClose.addEventListener('click', () => {
        chatBox.classList.remove('active');
    });

    // Send message function
    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        // Add user message
        addMessage(message, 'user');
        chatInput.value = '';

        // Show typing indicator
        showTypingIndicator();

        // Send to API
        fetch('/WebbandoTT/app/api/chat/chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Remove typing indicator
            removeTypingIndicator();
            
            if (data.success) {
                addMessage(data.message, 'bot');
            } else {
                addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
            }
        })
        .catch(() => {
            removeTypingIndicator();
            addMessage('Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.', 'bot');
        });
    }

    // Add message to chat
    function addMessage(message, type) {
        const now = new Date();
        const time = now.getHours().toString().padStart(2, '0') + ':' + 
                     now.getMinutes().toString().padStart(2, '0');
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type}-message`;
        
        const avatar = type === 'bot' ? 'bx bx-bot' : 'bx bx-user';
        const sender = type === 'bot' ? 'Sport Elite Bot' : 'Bạn';
        
        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class='${avatar}'></i>
            </div>
            <div class="message-bubble">
                <div class="message-info">
                    <span class="message-sender">${sender}</span>
                    <span class="message-time">${time}</span>
                </div>
                <div class="message-content">${message}</div>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Show typing indicator
    function showTypingIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'typing-indicator';
        indicator.innerHTML = '<span></span><span></span><span></span>';
        chatMessages.appendChild(indicator);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Remove typing indicator
    function removeTypingIndicator() {
        const indicator = document.querySelector('.typing-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    // Event listeners
    sendButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    // Add escape key listener to close chatbot
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && chatBox.classList.contains('active')) {
            chatBox.classList.remove('active');
        }
    });

    // Close chatbot when clicking outside
    document.addEventListener('click', (e) => {
        if (!chatBox.contains(e.target) && 
            !chatToggle.contains(e.target) && 
            chatBox.classList.contains('active')) {
            chatBox.classList.remove('active');
        }
    });
});
