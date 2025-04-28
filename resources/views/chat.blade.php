<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with {{ $user->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col">

    <!-- Header -->
    <div class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Chat with {{ $user->name }}</h1>
        <a href="/" class="text-blue-500 hover:underline">← Back to Users</a>
    </div>

    <!-- Chat area -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4" id="chat-box">
        {{-- Example messages --}}
        {{-- <div class="flex justify-start">
            <div class="bg-white p-3 rounded-lg shadow w-fit max-w-sm">
                <p class="text-sm text-gray-700">Hey! How can I help you?</p>
            </div>
        </div>
        <div class="flex justify-end">
            <div class="bg-blue-500 text-white p-3 rounded-lg shadow w-fit max-w-sm">
                <p class="text-sm">I need assistance with my account.</p>
            </div>
        </div> --}}
        <!-- Future messages go here -->
    </div>

    <!-- Input box -->
    <form class="p-4 bg-white flex gap-2 form">
        <input type="text" id="message" placeholder="Type your message..." class="flex-1 px-4 py-2 border rounded-full focus:outline-none focus:ring">
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition">Send</button>
    </form>
    
    <script>
        function sendMessage(e) {
            e.preventDefault();
            const msg = document.getElementById('message').value.trim();
            if (msg === '') return;

            const messageBubble = document.createElement('div');
            messageBubble.className = 'flex justify-end';
            messageBubble.innerHTML = `
                <div class="bg-blue-500 text-white p-3 rounded-lg shadow w-fit max-w-sm">
                    <p class="text-sm">${msg}</p>
                </div>
            `;
            document.getElementById('chat-box').appendChild(messageBubble);
            document.getElementById('message').value = '';
            const userId = document.getElementById('message').value = '';
            const adminId= document.getElementById('message').value = '';


            // Scroll to bottom
            document.getElementById('chat-box').scrollTop = document.getElementById('chat-box').scrollHeight;
        }
    </script>

<script>
    
    const supabaseUrl = "{{ config('services.supabase.url') }}";
const supabaseKey = "{{ config('services.supabase.anon_key') }}";
const client = supabase.createClient(supabaseUrl, supabaseKey);

    
    const currentUserId = {{ $admin->id }};
    const otherUserId = {{ $user->id }};
    

    const chatBox = document.getElementById('chat-box');
    function addMessageToUI(message) {
        const isMe = message.sender_id == currentUserId;
        const messageEl = document.createElement('div');
        messageEl.className = `flex ${isMe ? 'justify-end' : 'justify-start'}`;
        messageEl.innerHTML = `
            <div class="${isMe ? 'bg-blue-500 text-white' : 'bg-white text-gray-800'} p-3 rounded-lg shadow w-fit max-w-sm">
                <p class="text-sm">${message.content}</p>
            </div>
        `;
        chatBox.appendChild(messageEl);
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    (async function loadMessages() {
        
      const { data } = await client
        .from("messages")
        .update({ read: true })
        .eq("read", false); // Only update rows where read is false

    //   if (error) {
    //     console.error("Failed to update read status:", error);
    //   }
    
    const { data: messages, error } = await client
        .from('messages')
        .select()
        .or(
          `sender_id.eq.${currentUserId},receiver_id.eq.${otherUserId},sender_id.eq.${otherUserId}`
        )
        // .or(`and(sender_id.eq.${currentUserId},receiver_id.eq.${otherUserId}),and(sender_id.eq.${otherUserId},receiver_id.eq.${currentUserId})`)
        .order('created_at', { ascending: true });

    if (error) {
        console.error('Failed to load messages', error);
        return;
    }

    messages.forEach(addMessageToUI);
})();

    client.channel('chat')
        .on(
            'postgres_changes',
            { event: 'INSERT', schema: 'public', table: 'messages' },
            payload => {
                const message = payload.new;
                if (
                    (message.receiver_id == currentUserId && message.sender_id == otherUserId) ||
                    (message.sender_id == currentUserId && message.receiver_id == otherUserId)
                ) {
                    addMessageToUI(message);
                }
            }
        )
        .subscribe();

    // function addMessageToUI(message) {
    //     const isMe = message.sender_id == currentUserId;
    //     const messageEl = document.createElement('div');
    //     messageEl.className = `flex ${isMe ? 'justify-end' : 'justify-start'}`;
    //     messageEl.innerHTML = `
    //         <div class="${isMe ? 'bg-blue-500 text-white' : 'bg-white text-gray-800'} p-3 rounded-lg shadow w-fit max-w-sm">
    //             <p class="text-sm">${message.content}</p>
    //         </div>
    //     `;
    //     chatBox.appendChild(messageEl);
    //     chatBox.scrollTop = chatBox.scrollHeight;
    // }
    const sendForm = document.querySelector('form');
    sendForm.addEventListener('submit', async (e) => {
        e.preventDefault();
       
        
        const input = document.getElementById('message');
        const content = input.value.trim();
        if (!content) return;

        const { data, error } = await client
            .from('messages')
            .insert([{
                sender_id: currentUserId,
                receiver_id: otherUserId,
                content: content,
                sender:"admin"
                
                
            }]);
            if (!error) input.value = '';

    await fetch('http://localhost:8000/api/update-message', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json', // Tells the server you're sending JSON
    'Accept': 'application/json'        // Optional: tells server you want JSON back
  },
  body: JSON.stringify({
    userId: otherUserId,
    message: content
  })
});

          
           

       
    });
</script>

</body>
</html>
