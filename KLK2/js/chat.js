document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const chatForm = document.getElementById("chat-form");
    const messageInput = document.getElementById("message");
    const chatTitle = document.getElementById("chat-title");

    const currentUserId = chatTitle.dataset.currentId;
    const otherUserId = chatTitle.dataset.otherId;

    function loadMessages() {
        fetch(`../backend/get_messages.php?user1=${currentUserId}&user2=${otherUserId}`)
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data)) {
                    console.error("Respuesta no es un array:", data);
                    chatBox.innerHTML = "<p>No se pudieron cargar mensajes.</p>";
                    return;
                }
    
                chatBox.innerHTML = "";
                data.forEach(msg => {
                    const p = document.createElement("p");
                    p.className = msg.sender_id == currentUserId ? "you" : "other";
    
                    const hora = msg.created_at_formatted || "";
                    const check = msg.sender_id == currentUserId
                        ? (msg.status === "read" ? "&#10003;&#10003;" : "&#10003;")
                        : "";
    
                    p.innerHTML = `
                        ${msg.sender_id == currentUserId ? "You" : msg.sender_name}: ${msg.content}
                        <span style="font-size: 0.75em; color: #555;">${hora} ${check}</span>
                    `;
                    chatBox.appendChild(p);
                });
    
                // Scroll automático justo después de cargar todo
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(err => console.error("Error al cargar mensajes:", err));
    }
    

    function sendMessage(content) {
        fetch("../backend/send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ receiver: otherUserId, content })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                messageInput.value = "";
                loadMessages();
            } else {
                console.error("Error al enviar:", data);
                alert(data.error || "No se pudo enviar el mensaje");
            }
        })
        .catch(err => console.error("Error al enviar mensaje:", err));
    }

    chatForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const content = messageInput.value.trim();
        if (content !== "") {
            sendMessage(content);
        }
    });

    loadMessages();
        setTimeout(() => {
            chatBox.scrollTop = chatBox.scrollHeight;
        }, 100); // pequeño delay para esperar el DOM

       
});
