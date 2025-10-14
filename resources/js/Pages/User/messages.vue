<template>
  <div class="messages-body">
    <!-- Header Section -->
    <div class="messages-header">
      <div class="header-content">
        <h1>Messages</h1>
        <p class="subtitle">Connect with travelers and agencies</p>
      </div>
      <button class="new-message-btn">
        <i class="material-icons">edit</i>
        <span>New Message</span>
      </button>
    </div>

    <!-- Messages Container -->
    <div class="messages-container">
      <!-- Sidebar: Conversations List -->
      <div class="conversations-sidebar">
        <div class="sidebar-header">
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
              <img :src="conversation.avatar" :alt="conversation.name" />
              <span 
                v-if="conversation.online" 
                class="online-indicator"
              ></span>
            </div>
            <div class="conversation-info">
              <div class="conversation-header">
                <h4>{{ conversation.name }}</h4>
                <span class="time">{{ conversation.time }}</span>
              </div>
              <p class="last-message">
                <i v-if="conversation.sent" class="material-icons sent-icon">done_all</i>
                {{ conversation.lastMessage }}
              </p>
            </div>
            <div v-if="conversation.unread" class="unread-badge">
              {{ conversation.unreadCount }}
            </div>
          </div>

          <div v-if="filteredConversations.length === 0" class="empty-state">
            <i class="material-icons">chat_bubble_outline</i>
            <p>No conversations found</p>
          </div>
        </div>
      </div>

      <!-- Main Chat Area -->
      <div class="chat-area">
        <div v-if="selectedConversation" class="chat-content">
          <!-- Chat Header -->
          <div class="chat-header">
            <div class="chat-user-info">
              <div class="chat-avatar">
                <img :src="selectedConversation.avatar" :alt="selectedConversation.name" />
                <span 
                  v-if="selectedConversation.online" 
                  class="online-indicator"
                ></span>
              </div>
              <div class="chat-user-details">
                <h3>{{ selectedConversation.name }}</h3>
                <span class="status">
                  {{ selectedConversation.online ? 'Active now' : 'Last seen recently' }}
                </span>
              </div>
            </div>
            <div class="chat-actions">
              <button class="icon-btn">
                <i class="material-icons">call</i>
              </button>
              <button class="icon-btn">
                <i class="material-icons">videocam</i>
              </button>
              <button class="icon-btn">
                <i class="material-icons">info</i>
              </button>
            </div>
          </div>

          <!-- Messages Area -->
          <div class="messages-area">
            <div class="messages-wrapper">
              <div 
                v-for="message in selectedConversation.messages" 
                :key="message.id"
                class="message"
                :class="{ sent: message.sent, received: !message.sent }"
              >
                <div v-if="!message.sent" class="message-avatar">
                  <img :src="selectedConversation.avatar" :alt="selectedConversation.name" />
                </div>
                <div class="message-content">
                  <div class="message-bubble">
                    {{ message.text }}
                  </div>
                  <span class="message-time">{{ message.time }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Message Input -->
          <div class="message-input-area">
            <button class="icon-btn">
              <i class="material-icons">add</i>
            </button>
            <button class="icon-btn">
              <i class="material-icons">image</i>
            </button>
            <div class="input-wrapper">
              <input 
                type="text" 
                placeholder="Type a message..." 
                v-model="newMessage"
                @keyup.enter="sendMessage"
              />
            </div>
            <button class="send-btn" @click="sendMessage">
              <i class="material-icons">send</i>
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="chat-empty-state">
          <i class="material-icons">forum</i>
          <h3>Select a conversation</h3>
          <p>Choose a conversation from the list to start messaging</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const searchQuery = ref('');
const activeTab = ref('all');
const selectedConversation = ref(null);
const newMessage = ref('');

// Sample conversations data
const conversations = ref([
  {
    id: 1,
    name: 'Beach Resort Agency',
    avatar: 'https://i.pravatar.cc/150?img=1',
    lastMessage: 'Thank you for your interest in our packages!',
    time: '2m ago',
    unread: true,
    unreadCount: 3,
    online: true,
    sent: false,
    messages: [
      { id: 1, text: 'Hello! Do you have available packages for this weekend?', sent: true, time: '10:30 AM' },
      { id: 2, text: 'Yes, we have several packages available!', sent: false, time: '10:32 AM' },
      { id: 3, text: 'Thank you for your interest in our packages!', sent: false, time: '10:33 AM' },
    ]
  },
  {
    id: 2,
    name: 'John Traveler',
    avatar: 'https://i.pravatar.cc/150?img=3',
    lastMessage: 'See you at the resort tomorrow!',
    time: '1h ago',
    unread: false,
    online: true,
    sent: true,
    messages: [
      { id: 1, text: 'Are you coming to the resort this weekend?', sent: false, time: '9:00 AM' },
      { id: 2, text: 'Yes! I already booked my package', sent: true, time: '9:15 AM' },
      { id: 3, text: 'See you at the resort tomorrow!', sent: true, time: '9:16 AM' },
    ]
  },
  {
    id: 3,
    name: 'Island Hopping Tours',
    avatar: 'https://i.pravatar.cc/150?img=5',
    lastMessage: 'Our boat will depart at 8 AM sharp',
    time: '3h ago',
    unread: true,
    unreadCount: 1,
    online: false,
    sent: false,
    messages: [
      { id: 1, text: 'What time does the island hopping start?', sent: true, time: '8:00 AM' },
      { id: 2, text: 'Our boat will depart at 8 AM sharp', sent: false, time: '8:30 AM' },
    ]
  },
  {
    id: 4,
    name: 'Maria Santos',
    avatar: 'https://i.pravatar.cc/150?img=9',
    lastMessage: 'Thanks for the recommendation!',
    time: 'Yesterday',
    unread: false,
    online: false,
    sent: false,
    messages: [
      { id: 1, text: 'Can you recommend a good spot for diving?', sent: false, time: 'Yesterday' },
      { id: 2, text: 'I recommend Sohoton Cove!', sent: true, time: 'Yesterday' },
      { id: 3, text: 'Thanks for the recommendation!', sent: false, time: 'Yesterday' },
    ]
  },
]);

// Computed properties
const unreadCount = computed(() => {
  return conversations.value.filter(c => c.unread).length;
});

const filteredConversations = computed(() => {
  let filtered = conversations.value;
  
  if (activeTab.value === 'unread') {
    filtered = filtered.filter(c => c.unread);
  }
  
  if (searchQuery.value) {
    filtered = filtered.filter(c => 
      c.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }
  
  return filtered;
});

// Methods
const selectConversation = (conversation) => {
  selectedConversation.value = conversation;
  conversation.unread = false;
  conversation.unreadCount = 0;
};

const sendMessage = () => {
  if (!newMessage.value.trim() || !selectedConversation.value) return;
  
  selectedConversation.value.messages.push({
    id: Date.now(),
    text: newMessage.value,
    sent: true,
    time: 'Just now'
  });
  
  newMessage.value = '';
};
</script>

<style scoped>
.messages-body {
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, #E8F4F8, #FFFFFF);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* ===== HEADER ===== */
.messages-header {
  padding: 20px 30px;
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-content h1 {
  color: #004C5E;
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0 0 5px 0;
}

.subtitle {
  color: #666;
  font-size: 0.9rem;
  margin: 0;
}

.new-message-btn {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 25px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 180, 219, 0.3);
}

.new-message-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 180, 219, 0.4);
}

/* ===== MESSAGES CONTAINER ===== */
.messages-container {
  flex: 1;
  display: flex;
  overflow: hidden;
}

/* ===== CONVERSATIONS SIDEBAR ===== */
.conversations-sidebar {
  width: 350px;
  background: white;
  border-right: 1px solid #e8e8e8;
  display: flex;
  flex-direction: column;
}

.sidebar-header {
  padding: 20px;
  border-bottom: 1px solid #e8e8e8;
}

.search-wrapper {
  position: relative;
  margin-bottom: 15px;
}

.search-wrapper i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #999;
  font-size: 20px;
}

.search-input {
  width: 100%;
  padding: 10px 10px 10px 45px;
  border: 1px solid #e8e8e8;
  border-radius: 25px;
  background: #f8f9fa;
  outline: none;
  transition: all 0.3s ease;
}

.search-input:focus {
  background: white;
  border-color: #00b4db;
  box-shadow: 0 0 0 3px rgba(0, 180, 219, 0.1);
}

.filter-tabs {
  display: flex;
  gap: 10px;
}

.tab-btn {
  flex: 1;
  padding: 8px 15px;
  border: none;
  background: #f8f9fa;
  border-radius: 20px;
  cursor: pointer;
  font-weight: 500;
  color: #666;
  transition: all 0.2s ease;
}

.tab-btn.active {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
}

/* ===== CONVERSATIONS LIST ===== */
.conversations-list {
  flex: 1;
  overflow-y: auto;
}

.conversation-item {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  cursor: pointer;
  transition: all 0.2s ease;
  border-bottom: 1px solid #f5f5f5;
  position: relative;
}

.conversation-item:hover {
  background: #f8f9fa;
}

.conversation-item.active {
  background: #e8f7fb;
  border-left: 3px solid #00b4db;
}

.conversation-item.unread {
  background: #f0f9ff;
}

.conversation-avatar {
  position: relative;
  margin-right: 15px;
}

.conversation-avatar img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
}

.online-indicator {
  position: absolute;
  bottom: 2px;
  right: 2px;
  width: 12px;
  height: 12px;
  background: #4caf50;
  border: 2px solid white;
  border-radius: 50%;
}

.conversation-info {
  flex: 1;
  min-width: 0;
}

.conversation-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 5px;
}

.conversation-header h4 {
  margin: 0;
  font-size: 0.95rem;
  font-weight: 600;
  color: #333;
}

.time {
  font-size: 0.75rem;
  color: #999;
}

.last-message {
  margin: 0;
  font-size: 0.85rem;
  color: #666;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  display: flex;
  align-items: center;
  gap: 5px;
}

.sent-icon {
  font-size: 16px;
  color: #00b4db;
}

.unread-badge {
  background: #00b4db;
  color: white;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 12px;
  min-width: 20px;
  text-align: center;
}

/* ===== CHAT AREA ===== */
.chat-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #f8f9fa;
}

.chat-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100%;
}

/* Chat Header */
.chat-header {
  background: white;
  padding: 15px 25px;
  border-bottom: 1px solid #e8e8e8;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.chat-user-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.chat-avatar {
  position: relative;
}

.chat-avatar img {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  object-fit: cover;
}

.chat-user-details h3 {
  margin: 0 0 5px 0;
  font-size: 1.1rem;
  color: #333;
}

.status {
  font-size: 0.85rem;
  color: #4caf50;
}

.chat-actions {
  display: flex;
  gap: 10px;
}

.icon-btn {
  background: #f8f9fa;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  color: #666;
}

.icon-btn:hover {
  background: #00b4db;
  color: white;
}

/* Messages Area */
.messages-area {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
}

.messages-wrapper {
  max-width: 900px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.message {
  display: flex;
  gap: 10px;
  align-items: flex-end;
}

.message.sent {
  flex-direction: row-reverse;
}

.message-avatar img {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  object-fit: cover;
}

.message-content {
  display: flex;
  flex-direction: column;
  max-width: 60%;
}

.message.sent .message-content {
  align-items: flex-end;
}

.message-bubble {
  padding: 12px 18px;
  border-radius: 20px;
  word-wrap: break-word;
}

.message.received .message-bubble {
  background: white;
  color: #333;
  border-bottom-left-radius: 5px;
}

.message.sent .message-bubble {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
  border-bottom-right-radius: 5px;
}

.message-time {
  font-size: 0.75rem;
  color: #999;
  margin-top: 5px;
  padding: 0 5px;
}

/* Message Input */
.message-input-area {
  background: white;
  padding: 15px 25px;
  border-top: 1px solid #e8e8e8;
  display: flex;
  align-items: center;
  gap: 10px;
}

.input-wrapper {
  flex: 1;
}

.input-wrapper input {
  width: 100%;
  padding: 12px 20px;
  border: 1px solid #e8e8e8;
  border-radius: 25px;
  outline: none;
  background: #f8f9fa;
  transition: all 0.3s ease;
}

.input-wrapper input:focus {
  background: white;
  border-color: #00b4db;
  box-shadow: 0 0 0 3px rgba(0, 180, 219, 0.1);
}

.send-btn {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
  border: none;
  width: 45px;
  height: 45px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 2px 10px rgba(0, 180, 219, 0.3);
}

.send-btn:hover {
  transform: scale(1.1);
  box-shadow: 0 4px 15px rgba(0, 180, 219, 0.4);
}

/* Empty States */
.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #999;
}

.empty-state i {
  font-size: 48px;
  color: #ddd;
  margin-bottom: 10px;
}

.chat-empty-state {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #999;
}

.chat-empty-state i {
  font-size: 80px;
  color: #ddd;
  margin-bottom: 20px;
}

.chat-empty-state h3 {
  color: #666;
  margin: 0 0 10px 0;
}

/* Responsive */
@media (max-width: 768px) {
  .conversations-sidebar {
    width: 100%;
  }
  
  .chat-area {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
  }
}
</style>