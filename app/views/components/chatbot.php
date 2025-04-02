<div class="chatbot-container">
    <div class="chatbot-toggle">
        <i class='bx bxs-message-dots'></i>
    </div>
    
    <div class="chatbot-box">
        <div class="chatbot-header">
            <div class="chatbot-title">
                <div class="bot-avatar">
                    <i class='bx bxs-bot'></i>
                </div>
                <div class="bot-info">
                    <span class="bot-name">Sport Elite Assistant</span>
                    <span class="bot-status">
                        <i class='bx bxs-circle' style="color: #4ade80; font-size: 8px;"></i>
                        Online
                    </span>
                </div>
            </div>
            <button class="chatbot-close">
                <i class='bx bx-x'></i>
            </button>
        </div>
        
        <div class="chatbot-messages">
            <div class="message bot-message">
                <div class="message-avatar">
                    <i class='bx bxs-bot'></i>
                </div>
                <div class="message-bubble">
                    <div class="message-info">
                        <span class="message-sender">Sport Elite Bot</span>
                        <span class="message-time"><?php echo date('H:i'); ?></span>
                    </div>
                    <div class="message-content">
                        Xin chào! Tôi là trợ lý AI của Sport Elite. Tôi có thể giúp bạn tìm kiếm sản phẩm, 
                        tư vấn về giá cả và thông tin chi tiết về các sản phẩm thể thao. Bạn cần giúp gì?
                    </div>
                </div>
            </div>
        </div>
        
        <div class="chatbot-input">
            <input type="text" placeholder="Nhập tin nhắn..." id="chatInput">
            <button id="sendMessage">
                <i class='bx bxs-send'></i>
            </button>
        </div>
    </div>
</div>
