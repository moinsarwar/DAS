<!-- Floating AI Chatbot Widget -->
<div id="ai-chatbot-wrapper" class="position-fixed bottom-0 end-0 m-4" style="font-family: 'Inter', sans-serif; z-index: 9999 !important;">
    
    <!-- Chat Toggle Button -->
    <button id="ai-chat-toggle" class="btn btn-primary rounded-circle shadow-lg d-flex align-items-center justify-content-center border-0" 
            style="width: 65px; height: 65px; background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); outline: none; box-shadow: 0 8px 30px rgba(79, 70, 229, 0.35) !important;">
        <i class="bi bi-robot text-white fs-2 animate-pulse-custom"></i>
        <!-- Active Status Pulse Dot -->
        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-success border border-2 border-white rounded-circle animate-pulse" style="margin-left: -12px; margin-top: 10px; box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.4);"></span>
    </button>

    <!-- Chat Card Panel -->
    <div id="ai-chat-panel" class="card border-0 overflow-hidden" 
         style="width: calc(100vw - 32px); max-width: 400px; height: calc(100vh - 120px); max-height: 600px; bottom: 80px; right: 0; position: absolute; border-radius: 28px; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(25px) saturate(180%); -webkit-backdrop-filter: blur(25px) saturate(180%); box-shadow: 0 20px 50px rgba(15, 23, 42, 0.22); border: 1px solid rgba(255, 255, 255, 0.45); z-index: 99999;">
        
        <!-- Header -->
        <div class="card-header border-0 d-flex align-items-center justify-content-between px-4 py-3" 
             style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); color: white; box-shadow: 0 4px 20px rgba(79, 70, 229, 0.2); z-index: 10; position: relative;">
            <div class="d-flex align-items-center gap-3">
                <!-- Header Avatar with custom translucent background (Fixes solid white circle/icon visibility) -->
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
                    <i class="bi bi-robot fs-4 text-white"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold" style="font-size: 1rem; letter-spacing: 0.4px;">Cura AI</h6>
                    <div class="d-flex align-items-center gap-2 mt-0.5">
                        <span class="bg-success rounded-circle animate-pulse" style="width: 8px; height: 8px; display: inline-block;"></span>
                        <span class="text-white text-opacity-80 fw-medium" style="font-size: 0.75rem;">Medical Assistant</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Reset Chat -->
                <button id="ai-chat-reset" class="btn btn-link text-white text-opacity-80 p-1.5 hover-scale transition-all border-0 shadow-none" title="Reset Conversation" style="background: rgba(255, 255, 255, 0.1); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-arrow-counterclockwise fs-5"></i>
                </button>
                <!-- Close Button -->
                <button id="ai-chat-close" class="btn btn-link text-white text-opacity-80 p-1.5 hover-scale transition-all border-0 shadow-none" style="background: rgba(255, 255, 255, 0.1); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-x-lg fs-6"></i>
                </button>
            </div>
        </div>

        <!-- Chat History Area -->
        <div id="ai-chat-history" class="card-body overflow-y-auto px-3 py-3 d-flex flex-column gap-3" 
             style="background: rgba(248, 250, 252, 0.6); scrollbar-width: thin; scroll-behavior: smooth;">
            
            <!-- Welcome message -->
            <div class="message assistant-msg d-flex gap-2 align-items-start max-w-85 my-1 animate-slide-up">
                <div class="text-indigo-600 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 34px; height: 34px; background-color: #eef2ff; border: 1px solid #e0e7ff;">
                    <i class="bi bi-robot" style="font-size: 1rem;"></i>
                </div>
                <div class="ai-msg-bubble ai-msg-assistant">
                    <p class="mb-1 fw-bold text-indigo-700">Welcome! 👋</p>
                    <p class="mb-0 text-secondary">I am Cura, your AI medical assistant. I can help you list specialties, search for doctors, view schedules, and book appointments instantly.</p>
                </div>
            </div>

            <!-- Quick Suggestions -->
            <div id="ai-suggestions" class="d-flex flex-wrap gap-2 mt-1 px-4 mb-2 animate-slide-up" style="animation-delay: 0.1s;">
                <button class="btn btn-sm rounded-pill chip-btn d-flex align-items-center gap-2" data-text="Show specialties">🏥 <span>Specialties</span></button>
                <button class="btn btn-sm rounded-pill chip-btn d-flex align-items-center gap-2" data-text="Find a doctor">👨‍⚕️ <span>Search Doctor</span></button>
                <button class="btn btn-sm rounded-pill chip-btn d-flex align-items-center gap-2" data-text="Book appointment">📅 <span>Book Appointment</span></button>
            </div>
            
        </div>

        <!-- Footer Input Form -->
        <div class="card-footer border-top px-3 py-3" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
            <form id="ai-chat-form" class="d-flex gap-2 align-items-center">
                <input type="text" id="ai-chat-input" class="form-control px-3 shadow-none" 
                       placeholder="Type your message..." autocomplete="off" 
                       style="font-size: 0.9rem; height: 46px; border-radius: 24px; border: 1.5px solid #e2e8f0; transition: all 0.25s ease;">
                <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-md border-0 hover-pulse" 
                        style="width: 46px; height: 46px; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); flex-shrink: 0; transition: all 0.25s ease;">
                    <i class="bi bi-send-fill text-white fs-5" style="margin-left: 2px;"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<!-- Extra Styles for Interactive Premium UI -->
<style>
    /* Chat Panel Base State */
    #ai-chat-panel {
        opacity: 0;
        pointer-events: none;
        transform: translateY(30px) scale(0.92);
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Chat Panel Active State */
    #ai-chat-panel.active {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(0) scale(1);
    }

    #ai-chatbot-wrapper .animate-pulse-custom {
        animation: pulseCustom 2.5s infinite ease-in-out;
    }
    #ai-chat-toggle:hover {
        transform: scale(1.08) rotate(2deg);
        box-shadow: 0 12px 35px rgba(79, 70, 229, 0.5) !important;
    }
    #ai-chat-toggle:active {
        transform: scale(0.95);
    }

    /* Chat Message Bubble Classes (Fixes cramped padding/spacing in screenshot) */
    .ai-msg-bubble {
        padding: 10px 15px;
        border-radius: 20px;
        font-size: 0.88rem;
        line-height: 1.45;
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.04);
        max-width: 100%;
        word-break: break-word;
    }
    .ai-msg-assistant {
        background-color: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        color: #1e293b;
        border-bottom-left-radius: 4px;
    }
    .ai-msg-user {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #ffffff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }
    
    /* Sleek Suggestion Chips */
    #ai-chatbot-wrapper .chip-btn {
        font-size: 0.8rem;
        font-weight: 500;
        padding: 6px 14px;
        border: 1.5px solid rgba(79, 70, 229, 0.15);
        color: #4f46e5;
        background-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #ai-chatbot-wrapper .chip-btn:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }
    #ai-chatbot-wrapper .chip-btn:active {
        transform: translateY(0);
    }
    
    #ai-chatbot-wrapper .max-w-85 {
        max-width: 85%;
    }
    #ai-chatbot-wrapper .hover-scale:hover {
        transform: scale(1.1);
        background: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    #ai-chatbot-wrapper .hover-pulse:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35) !important;
    }
    #ai-chatbot-wrapper .transition-all {
        transition: all 0.2s ease;
    }

    /* Input Focus Glow */
    #ai-chat-input:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        background-color: white;
    }
    
    /* Custom Sleek Scrollbar */
    #ai-chat-history::-webkit-scrollbar {
        width: 6px;
    }
    #ai-chat-history::-webkit-scrollbar-track {
        background: transparent;
    }
    #ai-chat-history::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 10px;
    }
    #ai-chat-history::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.5);
    }

    /* Message Slide-Up Transition */
    .animate-slide-up {
        animation: slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    /* Typing indicator dots */
    .typing-dots span {
        width: 7px;
        height: 7px;
        background-color: #818cf8;
        border-radius: 50%;
        display: inline-block;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes pulseCustom {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(0.9); opacity: 0.85; }
    }
    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Script Logic -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("ai-chat-toggle");
    const chatPanel = document.getElementById("ai-chat-panel");
    const closeBtn = document.getElementById("ai-chat-close");
    const resetBtn = document.getElementById("ai-chat-reset");
    const chatForm = document.getElementById("ai-chat-form");
    const chatInput = document.getElementById("ai-chat-input");
    const chatHistory = document.getElementById("ai-chat-history");

    // Toggle panel with active class transitions
    toggleBtn.addEventListener("click", function () {
        chatPanel.classList.toggle("active");
        if (chatPanel.classList.contains("active")) {
            scrollToBottom();
            chatInput.focus();
        }
    });

    // Close panel
    closeBtn.addEventListener("click", function () {
        chatPanel.classList.remove("active");
    });

    // Handle suggestions click via event delegation
    document.addEventListener("click", function (e) {
        const chip = e.target.closest(".chip-btn");
        if (chip) {
            const text = chip.getAttribute("data-text");
            sendMessage(text);
        }
    });

    // Handle form submit
    chatForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (text) {
            sendMessage(text);
            chatInput.value = "";
        }
    });

    // Reset conversation
    resetBtn.addEventListener("click", function () {
        if (confirm("Are you sure you want to reset the conversation?")) {
            resetBtn.disabled = true;
            
            fetch("{{ route('ai.chat.reset') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                resetBtn.disabled = false;
                if (data.status === 'success') {
                    // Clear history except welcome message
                    chatHistory.innerHTML = `
                        <div class="message assistant-msg d-flex gap-2 align-items-start max-w-85 my-1 animate-slide-up">
                            <div class="text-indigo-600 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 34px; height: 34px; background-color: #eef2ff; border: 1px solid #e0e7ff;">
                                <i class="bi bi-robot" style="font-size: 1rem;"></i>
                            </div>
                            <div class="ai-msg-bubble ai-msg-assistant">
                                <p class="mb-1 fw-bold text-indigo-700">Welcome! 👋</p>
                                <p class="mb-0 text-secondary">I am Cura, your AI medical assistant. I can help you list specialties, search for doctors, view schedules, and book appointments instantly.</p>
                            </div>
                        </div>
                        <div id="ai-suggestions" class="d-flex flex-wrap gap-2 mt-1 px-4 mb-2 animate-slide-up">
                            <button class="btn btn-sm rounded-pill chip-btn d-flex align-items-center gap-2" data-text="Show specialties">🏥 <span>Specialties</span></button>
                            <button class="btn btn-sm rounded-pill chip-btn d-flex align-items-center gap-2" data-text="Find a doctor">👨‍⚕️ <span>Search Doctor</span></button>
                            <button class="btn btn-sm rounded-pill chip-btn d-flex align-items-center gap-2" data-text="Book appointment">📅 <span>Book Appointment</span></button>
                        </div>
                    `;
                    scrollToBottom();
                }
            })
            .catch(err => {
                resetBtn.disabled = false;
                console.error("Error resetting chat:", err);
            });
        }
    });

    function scrollToBottom() {
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    function appendUserMessage(text) {
        const msgDiv = document.createElement("div");
        msgDiv.className = "message user-msg d-flex align-self-end max-w-85 my-1 animate-slide-up";
        msgDiv.innerHTML = `
            <div class="ai-msg-bubble ai-msg-user">
                <p class="mb-0">${escapeHtml(text)}</p>
            </div>
        `;
        chatHistory.appendChild(msgDiv);
        scrollToBottom();
    }

    function appendAssistantMessage(text) {
        const msgDiv = document.createElement("div");
        msgDiv.className = "message assistant-msg d-flex gap-2 align-items-start max-w-85 my-1 animate-slide-up";
        
        let formattedText = formatMessageText(text);

        msgDiv.innerHTML = `
            <div class="text-indigo-600 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 34px; height: 34px; background-color: #eef2ff; border: 1px solid #e0e7ff;">
                <i class="bi bi-robot" style="font-size: 1rem;"></i>
            </div>
            <div class="ai-msg-bubble ai-msg-assistant">
                <p class="mb-0">${formattedText}</p>
            </div>
        `;
        chatHistory.appendChild(msgDiv);
        scrollToBottom();
    }

    function appendRegistrationCard(info) {
        const isExisting = info.is_existing || false;
        const cardTitle = isExisting ? "Patient Logged In!" : "Patient Registered!";
        const noteText = isExisting 
            ? "You have been successfully logged in with your MR Number. You can now access your dashboard."
            : "Please write down your <strong>MR Number</strong>. You will need it to log in next time!";
        const alertIconClass = isExisting ? "bi-check-circle-fill text-success" : "bi-info-circle-fill text-warning";
        const alertBgColor = isExisting ? "rgba(220, 252, 231, 0.7)" : "rgba(254, 243, 199, 0.85)";
        const alertBorderColor = isExisting ? "rgba(34, 197, 94, 0.2)" : "rgba(251, 191, 36, 0.2)";

        const cardDiv = document.createElement("div");
        cardDiv.className = "message assistant-msg d-flex gap-2.5 align-items-start max-w-85 my-2 animate-slide-up";
        cardDiv.innerHTML = `
            <div class="text-indigo-600 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 34px; height: 34px; background-color: #eef2ff; border: 1px solid #e0e7ff;">
                <i class="bi bi-robot" style="font-size: 1rem;"></i>
            </div>
            <div class="msg-content p-3.5 shadow-md text-dark w-100" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-radius: 20px; font-size: 0.88rem; border: 1.5px solid #bbf7d0; line-height: 1.5;">
                <h6 class="fw-bold text-success mb-2.5 d-flex align-items-center gap-1.5" style="font-size: 0.95rem;">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i> ${cardTitle}
                </h6>
                <div class="mb-1.5"><strong>Name:</strong> ${escapeHtml(info.name)}</div>
                <div class="mb-1.5"><strong>CNIC:</strong> ${escapeHtml(info.cnic)}</div>
                <div class="mb-3"><strong>MR Number:</strong> <span class="badge bg-success px-2.5 py-1.5 font-monospace rounded-pill fs-7">${escapeHtml(info.mr_number)}</span></div>
                
                <div class="alert p-2.5 mb-3 small text-dark border-0 shadow-none d-flex align-items-start gap-2" style="font-size: 0.8rem; background-color: ${alertBgColor}; border-radius: 12px; border: 1px solid ${alertBorderColor};">
                    <i class="bi ${alertIconClass} fs-5 flex-shrink-0" style="margin-top: 1px;"></i> 
                    <span>${noteText}</span>
                </div>
                <button onclick="window.location.reload();" class="btn btn-sm btn-success w-100 mt-1 rounded-pill fw-semibold py-2 d-flex align-items-center justify-content-center gap-1.5 shadow-sm hover-pulse" style="border: 0; background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);">
                    <i class="bi bi-arrow-clockwise fs-6"></i> Go to Dashboard
                </button>
            </div>
        `;
        chatHistory.appendChild(cardDiv);
        scrollToBottom();
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement("div");
        typingDiv.id = "ai-typing-bubble";
        typingDiv.className = "message assistant-msg d-flex gap-2 align-items-start max-w-85 my-1 animate-slide-up";
        typingDiv.innerHTML = `
            <div class="text-indigo-600 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 34px; height: 34px; background-color: #eef2ff; border: 1px solid #e0e7ff;">
                <i class="bi bi-robot" style="font-size: 1rem;"></i>
            </div>
            <div class="msg-content px-3.5 py-3 shadow-sm text-dark" style="background-color: white; border: 1px solid rgba(226, 232, 240, 0.8); border-radius: 20px 20px 20px 4px; font-size: 0.88rem; line-height: 1.5;">
                <div class="typing-dots d-flex gap-1.5 align-items-center">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
        chatHistory.appendChild(typingDiv);
        scrollToBottom();
    }

    function hideTypingIndicator() {
        const typingBubble = document.getElementById("ai-typing-bubble");
        if (typingBubble) {
            typingBubble.remove();
        }
    }

    function sendMessage(text) {
        appendUserMessage(text);

        // Hide suggestions on first interaction with a nice fadeout
        const suggestionBlock = document.getElementById("ai-suggestions");
        if (suggestionBlock) {
            suggestionBlock.style.transition = "all 0.25s ease";
            suggestionBlock.style.opacity = "0";
            suggestionBlock.style.transform = "translateY(-10px)";
            setTimeout(() => {
                suggestionBlock.remove();
            }, 250);
        }

        showTypingIndicator();

        // Lock form inputs
        chatInput.disabled = true;
        chatForm.querySelector("button[type='submit']").disabled = true;

        fetch("{{ route('ai.chat') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            hideTypingIndicator();

            chatInput.disabled = false;
            chatForm.querySelector("button[type='submit']").disabled = false;
            chatInput.focus();

            if (data.response) {
                appendAssistantMessage(data.response);
            }

            if (data.auto_logged_in && data.registration_info) {
                appendRegistrationCard(data.registration_info);
            }
        })
        .catch(err => {
            hideTypingIndicator();
            chatInput.disabled = false;
            chatForm.querySelector("button[type='submit']").disabled = false;
            appendAssistantMessage("Sorry, a connection or server error occurred. Please try again.");
            console.error("Chat error:", err);
        });
    }

    function escapeHtml(string) {
        return String(string).replace(/[&<>"']/g, function (s) {
            return {
                "&": "&amp;",
                "<": "&lt;",
                ">": "&gt;",
                '"': "&quot;",
                "'": "&#39;"
            }[s];
        });
    }

    // Bold formatting, lists to buttons, and newlines
    function formatMessageText(text) {
        let html = escapeHtml(text);
        
        // Bold formatting
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Convert lists (e.g. - item, * item, 1. item) to clickable buttons
        html = html.replace(/^\s*(?:[-*]|\d+\.)\s+(.*?)$/gm, function(match, p1) {
            return `<button type="button" class="btn btn-sm rounded-pill chip-btn d-inline-flex align-items-center gap-2 mt-1 mb-1" data-text="${p1}"><span>${p1}</span></button>`;
        });
        
        // Convert remaining newlines to <br>
        html = html.replace(/\n/g, '<br>');
        
        return html;
    }
});
</script>
