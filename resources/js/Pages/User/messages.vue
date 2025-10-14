<template>
  <div class="messages-body">
    <div class="messages-layout" :class="{ 'info-panel-active': showInfoPanel }">
      <aside class="conversations-sidebar" :class="{ 'mobile-hidden': isMobileView && selectedConversation }">
        <div class="sidebar-header">
            <h1>Messages</h1>
            <button class="new-message-btn icon-btn">
                <i class="material-icons">edit</i>
            </button>
        </div>
        <div class="sidebar-controls">
          <div class="search-wrapper">
            <i class="material-icons">search</i>
            <input 
              type="text" 
              placeholder="Search conversations..." 
              v-model="searchQuery"
              class="search-input"
            />
          </div>
          <div class="filter-tabs">
            <button 
              class="tab-btn" 
              :class="{ active: activeTab === 'all' }"
              @click="activeTab = 'all'"
            >
              All
            </button>
            <button 
              class="tab-btn" 
              :class="{ active: activeTab === 'unread' }"
              @click="activeTab = 'unread'"
            >
              Unread ({{ unreadCount }})
            </button>
          </div>
        </div>

        <div class="conversations-list">
          <div 
            v-for="conversation in filteredConversations" 
            :key="conversation.id"
            class="conversation-item"
            :class="{ 
              active: selectedConversation?.id === conversation.id,
              unread: conversation.unread 
            }"
            @click="selectConversation(conversation)"
          >
            <div class="conversation-avatar">
              <img v-if="conversation.avatar" :src="conversation.avatar" :alt="conversation.name" />
              <div v-else class="avatar-placeholder">
                  <i class="material-icons">{{ conversation.icon }}</i>
              </div>
              <span v-if="conversation.online" class="online-indicator"></span>
            </div>
            <div class="conversation-info">
              <div class="conversation-header">
                <h4>{{ conversation.name }}</h4>
                <span class="time">{{ conversation.time }}</span>
              </div>
              <p class="last-message">
                <i v-if="conversation.sent" class="material-icons sent-icon">done_all</i>
                <span v-if="conversation.lastMessageType === 'image'"><i class="material-icons-outlined attachment-icon">photo</i> Image</span>
                <span v-else>{{ conversation.lastMessage }}</span>
              </p>
            </div>
            <div v-if="conversation.unread && conversation.unreadCount > 0" class="unread-badge">
              {{ conversation.unreadCount }}
            </div>
          </div>
          <div v-if="filteredConversations.length === 0" class="empty-state">
            <i class="material-icons">chat_bubble_outline</i>
            <p>No conversations found</p>
          </div>
        </div>
      </aside>

      <main class="chat-area" :class="{ 'mobile-active': isMobileView && selectedConversation }">
        <div v-if="selectedConversation" class="chat-content">
          <div class="chat-header">
            <button v-if="isMobileView" class="icon-btn back-btn" @click="goBack">
                <i class="material-icons">arrow_back</i>
            </button>
            <div class="chat-user-info">
              <div class="chat-avatar">
                <img v-if="selectedConversation.avatar" :src="selectedConversation.avatar" :alt="selectedConversation.name" />
                 <div v-else class="avatar-placeholder small">
                  <i class="material-icons">{{ selectedConversation.icon }}</i>
                </div>
                <span v-if="selectedConversation.online" class="online-indicator"></span>
              </div>
              <div class="chat-user-details">
                <h3>{{ selectedConversation.name }}</h3>
                <span v-if="selectedConversation.online" class="status online">Active now</span>
                <span v-else class="status offline">Offline</span>
              </div>
            </div>
            <div class="chat-actions">
              <button class="icon-btn" @click="toggleInfoPanel">
                <i class="material-icons">info_outline</i>
              </button>
            </div>
          </div>

          <div class="messages-area">
            <div class="messages-wrapper">
              <div 
                v-for="message in selectedConversation.messages" 
                :key="message.id"
                class="message"
                :class="{ sent: message.sent, received: !message.sent }"
              >
                <div v-if="!message.sent && selectedConversation.avatar" class="message-avatar">
                  <img :src="selectedConversation.avatar" :alt="selectedConversation.name" />
                </div>
                <div class="message-content">
                  <div v-if="!message.type || message.type === 'text'" class="message-bubble">
                    {{ message.text }}
                  </div>
                  <div v-else-if="message.type === 'image'" class="message-bubble image-bubble">
                    <img :src="message.url" alt="Shared image" />
                  </div>
                  <div v-else-if="message.type === 'booking'" class="message-bubble booking-bubble">
                      <h4>{{ message.details.title }}</h4>
                      <div class="booking-details">
                          <p><strong>Date:</strong> {{ message.details.date }}</p>
                          <p><strong>Guests:</strong> {{ message.details.guests }}</p>
                      </div>
                      <button class="btn-view-booking">View Booking</button>
                  </div>
                  <span class="message-time">{{ message.time }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="message-input-area">
            <button class="icon-btn"><i class="material-icons">add_circle_outline</i></button>
            <div class="input-wrapper">
              <input 
                type="text" 
                placeholder="Type a message..." 
                v-model="newMessage"
                @keyup.enter="sendMessage"
              />
              <button class="icon-btn emoji-btn"><i class="material-icons">sentiment_satisfied_alt</i></button>
            </div>
            <button class="send-btn" @click="sendMessage">
              <i class="material-icons">send</i>
            </button>
          </div>
        </div>
        <div v-else class="chat-empty-state">
          <i class="material-icons">forum</i>
          <h3>Select a conversation</h3>
          <p>Choose a conversation from the list to start messaging.</p>
        </div>
      </main>

      <aside v-if="showInfoPanel && selectedConversation" class="info-panel">
         <div class="info-panel-header">
            <h3>Details</h3>
            <button class="icon-btn" @click="toggleInfoPanel">
                <i class="material-icons">close</i>
            </button>
         </div>
         <div class="info-panel-body">
            <div class="contact-details">
                <img v-if="selectedConversation.avatar" :src="selectedConversation.avatar" :alt="selectedConversation.name" class="contact-avatar" />
                <div v-else class="avatar-placeholder large">
                  <i class="material-icons">{{ selectedConversation.icon }}</i>
                </div>
                <h4>{{ selectedConversation.name }}</h4>
                <p>{{ selectedConversation.online ? 'Active now' : 'Offline' }}</p>
            </div>
            <div v-if="selectedConversation.email" class="info-section">
                <h5>Contact Information</h5>
                <div class="contact-info-item">
                    <i class="material-icons-outlined">email</i>
                    <span>{{ selectedConversation.email }}</span>
                </div>
                 <div class="contact-info-item">
                    <i class="material-icons-outlined">phone</i>
                    <span>{{ selectedConversation.phone }}</span>
                </div>
            </div>
            <div class="info-section">
                <h5>Shared Media</h5>
                <div class="media-grid">
                    <img src="https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?w=200" alt="Shared media thumbnail">
                    <img src="https://images.unsplash.com/photo-1530789253388-582c481c54b0?w=200" alt="Shared media thumbnail">
                    <div class="media-placeholder">+5 more</div>
                </div>
            </div>
         </div>
      </aside>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const searchQuery = ref('');
const activeTab = ref('all');
const selectedConversation = ref(null);
const newMessage = ref('');
const showInfoPanel = ref(false);
const isMobileView = ref(window.innerWidth <= 768);

const conversations = ref([
    {
        id: 1,
        name: 'Island Hopping Tours',
        avatar: 'https://i.pravatar.cc/150?img=5',
        email: 'contact@islandtours.com',
        phone: '+63 917 123 4567',
        lastMessage: 'Here are the photos from your trip!',
        lastMessageType: 'image', // New property
        time: '2m ago',
        unread: true,
        unreadCount: 1,
        online: true,
        sent: false,
        messages: [
            { id: 1, text: 'Hello! I\'d like to inquire about your Siargao package.', type: 'text', sent: true, time: '10:30 AM' },
            { 
              id: 2, 
              type: 'booking', 
              sent: false, 
              time: '10:32 AM',
              details: {
                  title: 'Siargao Island Hopping',
                  date: 'Oct 25, 2025',
                  guests: '2 Adults'
              }
            },
            { id: 3, text: 'Looks great! We are confirming your booking now.', type: 'text', sent: false, time: '10:33 AM' },
            { 
                id: 4, 
                type: 'image', 
                url: 'https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?w=400', 
                sent: false, 
                time: '10:35 AM' 
            },
        ]
    },
    {
        id: 2,
        name: 'Maria Santos',
        avatar: 'https://i.pravatar.cc/150?img=9',
        email: 'm.santos@email.com',
        phone: '+63 918 987 6543',
        lastMessage: 'See you at the resort tomorrow!',
        lastMessageType: 'text',
        time: '1h ago',
        unread: false,
        online: true,
        sent: true,
        messages: [
            { id: 1, text: 'Your booking is confirmed. Is there anything else I can help with?', sent: false, time: '9:00 AM' },
            { id: 2, text: 'No, that\'s all. Thanks!', sent: true, time: '9:15 AM' },
            { id: 3, text: 'See you at the resort tomorrow!', sent: true, time: '9:16 AM' },
        ]
    },
     {
        id: 3,
        name: 'System Notification',
        avatar: null, // No avatar for system
        icon: 'notifications', // Use icon instead
        lastMessage: 'Your password was changed successfully.',
        lastMessageType: 'text',
        time: '3h ago',
        unread: false,
        online: false,
        sent: false,
        messages: [
            { id: 1, text: 'Your password was changed successfully on Oct 15, 2025.', type: 'text', sent: false, time: 'Yesterday' },
        ]
    },
    {
        id: 4,
        name: 'John Traveler',
        avatar: 'https://i.pravatar.cc/150?img=3',
        email: 'john.t@email.com',
        phone: '+63 920 111 2222',
        lastMessage: 'Thanks for the recommendation!',
        lastMessageType: 'text',
        time: 'Yesterday',
        unread: true,
        unreadCount: 2,
        online: false,
        sent: false,
        messages: [
            { id: 1, text: 'Can you recommend a good spot for diving?', sent: false, time: 'Yesterday' },
            { id: 2, text: 'I recommend Sohoton Cove! It\'s amazing.', sent: true, time: 'Yesterday' },
            { id: 3, text: 'Thanks for the recommendation!', sent: false, time: 'Yesterday' },
        ]
    },
]);

// --- Computed Properties ---
const unreadCount = computed(() => conversations.value.filter(c => c.unread).length);

const filteredConversations = computed(() => {
  let filtered = conversations.value;
  if (activeTab.value === 'unread') {
    filtered = filtered.filter(c => c.unread);
  }
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(c => c.name.toLowerCase().includes(query));
  }
  return filtered;
});

// --- Methods ---
const selectConversation = (conversation) => {
  selectedConversation.value = conversation;
  if (conversation.unread) {
    conversation.unread = false;
    conversation.unreadCount = 0;
  }
};

const sendMessage = () => {
  if (!newMessage.value.trim() || !selectedConversation.value) return;
  selectedConversation.value.messages.push({
    id: Date.now(),
    text: newMessage.value,
    sent: true,
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
  });
  newMessage.value = '';
};

const toggleInfoPanel = () => {
    showInfoPanel.value = !showInfoPanel.value;
};

const goBack = () => {
    selectedConversation.value = null;
};

// --- Lifecycle for Responsive Handling ---
const handleResize = () => {
    isMobileView.value = window.innerWidth <= 768;
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
    // Initialize with a default selected conversation on desktop for better presentation
    if (!isMobileView.value) {
        selectConversation(conversations.value[0]);
    }
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
/* ===== GLOBAL & LAYOUT ===== */
.messages-body {
  width: 100%; height: 100vh; background-color: #f1f5f9;
  display: flex; align-items: center; justify-content: center;
  padding: 24px; overflow: hidden;
}
.messages-layout {
  width: 100%; height: 100%; max-width: 1600px;
  background: white; border-radius: 16px; border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05); display: grid;
  grid-template-columns: 320px 1fr; overflow: hidden;
  transition: grid-template-columns 0.3s ease;
}
.messages-layout.info-panel-active {
  grid-template-columns: 320px 1fr 300px;
}

/* ===== CONVERSATIONS SIDEBAR ===== */
.conversations-sidebar {
  border-right: 1px solid #e2e8f0; display: flex;
  flex-direction: column; height: 100%; background: #f8fafc;
}
.sidebar-header {
  padding: 16px 24px; display: flex; justify-content: space-between;
  align-items: center; border-bottom: 1px solid #e2e8f0; background: white;
}
.sidebar-header h1 { font-size: 1.5rem; color: #1e293b; margin: 0; }
.sidebar-controls { padding: 16px 24px; border-bottom: 1px solid #e2e8f0; }
.search-wrapper { position: relative; margin-bottom: 16px; }
.search-wrapper .material-icons { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
.search-input {
  width: 100%; padding: 10px 16px 10px 40px; border: 1px solid #e2e8f0;
  border-radius: 8px; background: white; outline: none; transition: all 0.2s ease;
}
.search-input:focus { border-color: #0D9488; box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1); }
.filter-tabs { display: flex; gap: 8px; }
.tab-btn {
  flex: 1; padding: 8px 12px; border: 1px solid #e2e8f0; background: white;
  border-radius: 8px; cursor: pointer; font-weight: 600; color: #334155;
  transition: all 0.2s ease;
}
.tab-btn.active { background: #0D9488; color: white; border-color: #0D9488; }
.conversations-list { flex: 1; overflow-y: auto; padding: 8px; }
.conversation-item {
  display: flex; align-items: center; padding: 12px; cursor: pointer;
  transition: all 0.2s ease; border-radius: 8px; position: relative;
}
.conversation-item:hover { background: #f1f5f9; }
.conversation-item.active { background: #ccfbf1; }
.conversation-item.unread h4 { font-weight: 700; color: #1e293b; }
.conversation-avatar { position: relative; margin-right: 12px; }
.conversation-avatar img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
.avatar-placeholder {
    width: 48px; height: 48px; border-radius: 50%; background-color: #e2e8f0;
    display: flex; align-items: center; justify-content: center; color: #64748b;
}
.online-indicator {
  position: absolute; bottom: 1px; right: 1px; width: 12px; height: 12px;
  background: #22c55e; border: 2px solid white; border-radius: 50%;
}
.conversation-info { flex: 1; min-width: 0; }
.conversation-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
.conversation-header h4 { margin: 0; font-size: 0.95rem; font-weight: 600; color: #334155; }
.time { font-size: 0.75rem; color: #94a3b8; }
.last-message {
  margin: 0; font-size: 0.85rem; color: #64748b; overflow: hidden;
  text-overflow: ellipsis; white-space: nowrap; display: flex; align-items: center; gap: 4px;
}
.attachment-icon { font-size: 16px; vertical-align: middle; }
.sent-icon { font-size: 16px; color: #0D9488; }
.unread-badge {
  background: #0D9488; color: white; font-size: 0.7rem; font-weight: 700;
  padding: 2px 7px; border-radius: 12px; text-align: center;
}

/* ===== CHAT AREA ===== */
.chat-area { display: flex; flex-direction: column; height: 100%; background: white; }
.chat-content { flex: 1; display: flex; flex-direction: column; height: 100%; }
.chat-header {
  background: white; padding: 12px 24px; border-bottom: 1px solid #e2e8f0;
  display: flex; justify-content: space-between; align-items: center;
}
.chat-user-info { display: flex; align-items: center; gap: 12px; }
.chat-avatar { position: relative; }
.chat-avatar img { width: 40px; height: 40px; border-radius: 50%; }
.avatar-placeholder.small { width: 40px; height: 40px; }
.chat-user-details h3 { margin: 0; font-size: 1.1rem; color: #1e293b; }
.status { font-size: 0.8rem; }
.status.online { color: #16a34a; }
.status.offline { color: #64748b; }
.chat-actions { display: flex; gap: 8px; }
.messages-area { flex: 1; overflow-y: auto; padding: 24px; background: #f8fafc; }
.messages-wrapper { display: flex; flex-direction: column; gap: 20px; }
.message { display: flex; gap: 12px; align-items: flex-end; }
.message.sent { flex-direction: row-reverse; }
.message-avatar img { width: 32px; height: 32px; border-radius: 50%; }
.message-content { display: flex; flex-direction: column; max-width: 65%; }
.message.sent .message-content { align-items: flex-end; }
.message-bubble { padding: 10px 16px; border-radius: 18px; line-height: 1.5; }
.message.received .message-bubble { background: #e2e8f0; color: #1e293b; border-bottom-left-radius: 4px; }
.message.sent .message-bubble { background: #0D9488; color: white; border-bottom-right-radius: 4px; }
.message-time { font-size: 0.75rem; color: #94a3b8; margin-top: 6px; padding: 0 5px; }
.message-input-area {
  background: white; padding: 12px 24px; border-top: 1px solid #e2e8f0;
  display: flex; align-items: center; gap: 12px;
}
.input-wrapper { flex: 1; position: relative; }
.input-wrapper input {
  width: 100%; padding: 12px 48px 12px 20px; border: none;
  border-radius: 24px; outline: none; background: #f1f5f9; transition: all 0.2s ease;
}
.input-wrapper input:focus { box-shadow: 0 0 0 2px #0D9488; }
.emoji-btn { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); }
.send-btn {
  background: #0D9488; color: white; border: none; width: 44px; height: 44px;
  border-radius: 50%; cursor: pointer; display: flex; align-items: center;
  justify-content: center; transition: all 0.2s ease;
}
.send-btn:hover { background: #0f766e; }

/* --- Rich Message Bubbles --- */
.image-bubble { padding: 4px; }
.image-bubble img { max-width: 250px; border-radius: 16px; display: block; }
.booking-bubble {
    background: white; border: 1px solid #e2e8f0;
    border-radius: 12px; width: 280px;
}
.booking-bubble h4 { margin: 0 0 8px 0; color: #1e293b; }
.booking-details p { margin: 4px 0; font-size: 0.85rem; color: #334155; }
.btn-view-booking {
    width: 100%; border: none; background: #0D9488; color: white;
    padding: 8px; border-radius: 8px; margin-top: 12px; cursor: pointer;
    font-weight: 600; transition: background-color 0.2s;
}
.btn-view-booking:hover { background: #0f766e; }

/* ===== INFO PANEL ===== */
.info-panel {
  border-left: 1px solid #e2e8f0; display: flex; flex-direction: column; background: #f8fafc;
}
.info-panel-header { padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; background: white;}
.info-panel-header h3 { margin: 0; font-size: 1.1rem; color: #1e293b; }
.info-panel-body { padding: 24px; overflow-y: auto; }
.contact-details { text-align: center; margin-bottom: 24px; }
.contact-avatar, .avatar-placeholder.large { width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 12px auto; }
.contact-details h4 { margin: 0 0 4px 0; }
.contact-details p { margin: 0; color: #64748b; font-size: 0.9rem; }
.info-section { margin-bottom: 24px; }
.info-section h5 { margin: 0 0 12px 0; font-size: 0.8rem; font-weight: 600; color: #334155; text-transform: uppercase; }
.contact-info-item { display: flex; align-items: center; gap: 8px; color: #334155; margin-bottom: 8px; font-size: 0.9rem; }
.contact-info-item .material-icons-outlined { font-size: 20px; color: #94a3b8; }
.media-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.media-grid img { width: 100%; height: 70px; object-fit: cover; border-radius: 8px; }
.media-placeholder {
    width: 100%; height: 70px; background: #f1f5f9; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 600; color: #64748b;
}

/* ===== SHARED & EMPTY STATES ===== */
.icon-btn {
  background: transparent; border: none; width: 40px; height: 40px;
  border-radius: 50%; cursor: pointer; display: flex; align-items: center;
  justify-content: center; transition: all 0.2s ease; color: #64748b;
}
.icon-btn:hover { background: #f1f5f9; color: #1e293b; }
.empty-state, .chat-empty-state {
  text-align: center; padding: 40px 20px; color: #94a3b8;
}
.empty-state i, .chat-empty-state i { font-size: 48px; margin-bottom: 10px; }
.chat-empty-state { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; background: white; }
.chat-empty-state h3 { color: #334155; margin: 0 0 8px 0; }
.back-btn { display: none; }

/* ===== RESPONSIVE ===== */
@media (max-width: 1200px) {
    .messages-layout.info-panel-active { grid-template-columns: 320px 1fr; }
    .info-panel {
        position: absolute; top: 0; right: 0; width: 300px; height: 100%;
        background: white; z-index: 10;
        transform: translateX(100%); transition: transform 0.3s ease;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
    }
    .messages-layout.info-panel-active .info-panel {
        transform: translateX(0);
    }
}
@media (max-width: 768px) {
    .messages-body { padding: 0; }
    .messages-layout { grid-template-columns: 1fr; border-radius: 0; }
    .conversations-sidebar { transition: transform 0.3s ease; transform: translateX(0); width: 100%; position: absolute; }
    .conversations-sidebar.mobile-hidden { transform: translateX(-100%); }
    .chat-area {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        transition: transform 0.3s ease; transform: translateX(100%);
    }
    .chat-area.mobile-active { transform: translateX(0); }
    .back-btn { display: flex; }
    .info-panel { width: 280px; }
}
</style>