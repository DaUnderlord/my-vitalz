@extends('pharmacy.layout')

@section('title', 'Messages')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Pharmacy /</span> Messages
</h4>

<div class="card">
    <div class="card-body">
        @if(request()->query('banner') === 'new_partner')
        <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
            <div>
                <i class="bx bx-check-circle me-2"></i>
                New network partner added
                @if(request()->query('partner_name'))
                <strong>{{ request()->query('partner_name') }}</strong>
                @endif
                . You can start chatting below.
            </div>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="openBannerThread()">
                <i class="bx bx-message-rounded-dots"></i> Open Chat
            </button>
        </div>
        @endif
        <div class="row g-3">
            <!-- Threads List -->
            <div class="col-md-4">
                <input class="form-control mb-3" id="threadSearch" placeholder="Search threads..." oninput="filterThreads()">
                <div id="threadsList" class="list-group" style="max-height: 500px; overflow-y: auto;">
                    @forelse($threads as $thread)
                    <a href="javascript:void(0)" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center thread-item {{ $loop->first ? 'active' : '' }}" 
                       data-partner-id="{{ $thread->partner_id }}"
                       data-partner-type="{{ $thread->partner_type }}"
                       onclick="openThread({{ $thread->partner_id }}, '{{ $thread->partner_type }}', '{{ $thread->partner_name }}')">
                        <div>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ substr($thread->partner_name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <strong>{{ $thread->partner_name }}</strong>
                                    <span class="badge bg-label-{{ $thread->partner_type == 'doctor' ? 'primary' : ($thread->partner_type == 'hospital' ? 'info' : 'secondary') }} ms-1">
                                        {{ ucfirst($thread->partner_type) }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ date('M d, H:i', strtotime($thread->last_message_time)) }}</small>
                                </div>
                            </div>
                        </div>
                        @if($thread->unread_count > 0)
                        <span class="badge bg-primary rounded-pill">{{ $thread->unread_count }}</span>
                        @endif
                    </a>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="bx bx-message-rounded-dots fs-1"></i>
                        <p class="mb-0">No messages yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Chat Pane -->
            <div class="col-md-8 d-flex flex-column" style="height: 500px;">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <h6 id="chatTitle" class="mb-0 text-muted">Select a conversation</h6>
                    <div id="chatActions" style="display: none;">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewPartnerProfile()">
                            <i class="bx bx-user"></i> View Profile
                        </button>
                    </div>
                </div>
                
                <div id="chatMessages" class="flex-grow-1 overflow-auto mb-3 p-3 bg-light rounded" style="min-height: 350px;">
                    <div class="text-center text-muted py-5">
                        <i class="bx bx-message-square-dots fs-1"></i>
                        <p class="mb-0">No conversation selected</p>
                    </div>
                </div>
                
                <div class="input-group">
                    <input type="text" id="messageInput" class="form-control" placeholder="Type a message..." onkeydown="if(event.key==='Enter'){sendMessage()}" disabled>
                    <button class="btn btn-primary" onclick="sendMessage()" id="sendBtn" disabled>
                        <i class="bx bx-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-message {
    margin-bottom: 15px;
    display: flex;
}

.chat-message.sent {
    justify-content: flex-end;
}

.chat-message.received {
    justify-content: flex-start;
}

.chat-bubble {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 15px;
    position: relative;
}

.chat-message.sent .chat-bubble {
    background: #5a5fc7;
    color: white;
    border-bottom-right-radius: 5px;
}

.chat-message.received .chat-bubble {
    background: #f1f3f5;
    color: #343a40;
    border-bottom-left-radius: 5px;
}

.chat-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 5px;
}

.thread-item.active {
    background-color: rgba(90, 95, 199, 0.08);
    border-left: 3px solid #5a5fc7;
}
</style>
@endsection

@section('scripts')
<script>
let currentPartnerId = null;
let currentPartnerType = null;
let currentPartnerName = null;

function filterThreads() {
    const search = document.getElementById('threadSearch').value.toLowerCase();
    const threads = document.querySelectorAll('.thread-item');
    
    threads.forEach(thread => {
        const text = thread.textContent.toLowerCase();
        thread.style.display = text.includes(search) ? '' : 'none';
    });
}

function openThread(partnerId, partnerType, partnerName) {
    currentPartnerId = partnerId;
    currentPartnerType = partnerType;
    currentPartnerName = partnerName;
    
    // Update active state
    document.querySelectorAll('.thread-item').forEach(item => {
        item.classList.remove('active');
    });
    event.currentTarget.classList.add('active');
    
    // Update chat title
    document.getElementById('chatTitle').textContent = `${partnerName} - ${partnerType}`;
    document.getElementById('chatActions').style.display = 'block';
    
    // Enable input
    document.getElementById('messageInput').disabled = false;
    document.getElementById('sendBtn').disabled = false;
    
    // Load messages
    loadMessages(partnerId, partnerType);
}

function loadMessages(partnerId, partnerType) {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
    
    fetch(`/api/pharmacy/messages/thread/${partnerId}/${partnerType}?uid=${getCookie('uid')}&auth=${getCookie('authen')}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderMessages(data.messages || []);
            } else {
                chatMessages.innerHTML = '<div class="text-center text-muted py-4">Failed to load messages</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            chatMessages.innerHTML = '<div class="text-center text-muted py-4">Error loading messages</div>';
        });
}

function renderMessages(messages) {
    const chatMessages = document.getElementById('chatMessages');
    
    if (messages.length === 0) {
        chatMessages.innerHTML = '<div class="text-center text-muted py-4">No messages yet. Start the conversation!</div>';
        return;
    }
    
    chatMessages.innerHTML = '';
    messages.forEach(msg => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${msg.sender_type === 'pharmacy' ? 'sent' : 'received'}`;
        
        messageDiv.innerHTML = `
            <div class="chat-bubble">
                <div>${msg.message}</div>
                <div class="chat-time">${formatTime(msg.created_at)}</div>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
    });
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function sendMessage() {
    const input = document.getElementById('messageInput');
    const message = input.value.trim();
    
    if (!message || !currentPartnerId) return;
    
    fetch('/api/pharmacy/message/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            uid: getCookie('uid'),
            auth: getCookie('authen'),
            partner_id: currentPartnerId,
            partner_type: currentPartnerType,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            input.value = '';
            loadMessages(currentPartnerId, currentPartnerType);
        } else {
            alert(data.message || 'Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function viewPartnerProfile() {
    if (currentPartnerId && currentPartnerType) {
        window.location.href = `/dashboard-pharmacy?pg=network&view=${currentPartnerId}`;
    }
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + ' min ago';
    if (diff < 86400000) return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function getCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : '';
}

// Auto-load first thread on page load
document.addEventListener('DOMContentLoaded', function() {
    const firstThread = document.querySelector('.thread-item');
    if (firstThread) {
        const partnerId = firstThread.dataset.partnerId;
        const partnerType = firstThread.dataset.partnerType;
        const partnerName = firstThread.textContent.trim().split('\n')[0];
        openThread(partnerId, partnerType, partnerName);
    }
    // If banner has partner_id, open that thread
    const url = new URL(window.location.href);
    const pId = url.searchParams.get('partner_id');
    const pType = url.searchParams.get('partner_type') || 'pharmacy';
    const pName = url.searchParams.get('partner_name') || 'Partner';
    if (pId) {
        openThread(pId, pType, pName);
    }
});

// Auto-refresh messages every 10 seconds
setInterval(function() {
    if (currentPartnerId && currentPartnerType) {
        loadMessages(currentPartnerId, currentPartnerType);
    }
}, 10000);

function openBannerThread(){
  const url = new URL(window.location.href);
  const pId = url.searchParams.get('partner_id');
  const pType = url.searchParams.get('partner_type') || 'pharmacy';
  const pName = url.searchParams.get('partner_name') || 'Partner';
  if(pId){ openThread(pId, pType, pName); }
}
</script>
@endsection
