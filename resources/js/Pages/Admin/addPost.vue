<template>
  <div class="add-post-body">
    <div class="header-section">
      <h2>Add New Post</h2>
      <button @click="goBackToManagePosts" class="back-btn">
        <i class="material-icons">arrow_back</i> Back to Manage Posts
      </button>
    </div>

    <div class="form-container">
      <form @submit.prevent="submitPost">
        <div class="form-group">
          <label for="userId">User ID</label>
          <input
            type="number"
            id="userId"
            v-model="post.userId"
            placeholder="Enter User ID"
            required
          />
        </div>
        <div class="form-group">
          <label for="title">Title</label>
          <input
            type="text"
            id="title"
            v-model="post.title"
            placeholder="Enter Post Title"
            required
          />
        </div>
        <div class="form-group">
          <label for="url">URL</label>
          <input
            type="url"
            id="url"
            v-model="post.url"
            placeholder="Enter Post URL"
            required
          />
        </div>
        <div class="form-actions">
          <button type="submit" class="submit-btn">
            <i class="material-icons">save</i> Save Post
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// Define emits to communicate with parent component
const emit = defineEmits(['selectPage']);

const post = ref({
  userId: null,
  title: '',
  url: '',
});

const submitPost = () => {
  // Later, you can add API call here, e.g.:
  // import axios from 'axios';
  // await axios.post('/api/posts', post.value);
  console.log('Submitting post:', post.value);
  
  // Reset form
  post.value = { userId: null, title: '', url: '' };
  
  // Navigate back to Manage Posts using emit
  goBackToManagePosts();
};

const goBackToManagePosts = () => {
  emit('selectPage', 'managePost');
};
</script>

<style scoped>
.add-post-body {
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, #E8F4F8, #FFFFFF);
  padding: 20px;
  overflow-y: auto;
}

/* ===== HEADER SECTION ===== */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.header-section h2 {
  color: #004C5E;
  font-weight: 700;
}

.back-btn {
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
  text-decoration: none;
  transition: 0.2s;
}

.back-btn:hover {
  background-color: #5a6268;
}

/* ===== FORM ===== */
.form-container {
  max-width: 600px;
  margin: 0 auto;
  background: white;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  color: #004C5E;
  font-weight: 500;
  margin-bottom: 5px;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
}

.form-group input:focus {
  outline: none;
  border-color: #007A8C;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
}

.submit-btn {
  background-color: #007A8C;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
  transition: 0.2s;
}

.submit-btn:hover {
  background-color: #009FB7;
}
</style>