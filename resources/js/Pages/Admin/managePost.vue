<template>
  <div class="manage-post-body">
    <div class="header-section">
      <h2>Manage Posts</h2>
      <button @click="goToAddPost" class="add-btn">
        <i class="material-icons">add</i> Add New Post
      </button>
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Title</th>
            <th>URL</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts" :key="post.id">
            <td>{{ post.id }}</td>
            <td>{{ post.userId }}</td>
            <td>{{ post.title }}</td>
            <td><a :href="post.url" target="_blank">{{ post.url }}</a></td>
            <td>{{ post.createdAt }}</td>
            <td>{{ post.updatedAt }}</td>
            <td class="action-buttons">
              <button class="edit-btn" @click="editPost(post)">
                <i class="material-icons">edit</i>
              </button>
              <button class="delete-btn" @click="deletePost(post.id)">
                <i class="material-icons">delete</i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// Define emits to communicate with parent component
const emit = defineEmits(['selectPage']);

// Sample data for posts
const posts = ref([
  {
    id: 1,
    userId: 12,
    title: 'Beautiful Surigao Beach',
    url: 'https://example.com/post1',
    createdAt: '2025-10-10',
    updatedAt: '2025-10-10',
  },
  {
    id: 2,
    userId: 15,
    title: 'Top 5 Hidden Spots',
    url: 'https://example.com/post2',
    createdAt: '2025-10-08',
    updatedAt: '2025-10-09',
  },
]);

// Navigate to Add Post page
const goToAddPost = () => {
  emit('selectPage', 'addPost');
};

// Edit post function
const editPost = (post) => {
  console.log('Editing post:', post);
  // You can emit an event with post data or navigate to edit page
  // emit('selectPage', 'editPost');
};

// Delete post function
const deletePost = (id) => {
  if (confirm('Are you sure you want to delete this post?')) {
    const index = posts.value.findIndex(post => post.id === id);
    if (index !== -1) {
      posts.value.splice(index, 1);
    }
    console.log('Deleted post with ID:', id);
    // Later, add API call here:
    // await axios.delete(`/api/posts/${id}`);
  }
};

// Later, you can replace the sample data with an API call using axios, e.g.:
// import axios from 'axios';
// onMounted(async () => {
//   const response = await axios.get('/api/posts');
//   posts.value = response.data;
// });
</script>

<style scoped>
.manage-post-body {
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

.add-btn {
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
  text-decoration: none;
  transition: 0.2s;
}

.add-btn:hover {
  background-color: #009FB7;
}

/* ===== TABLE ===== */
.table-container {
  width: 100%;
  background: white;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background-color: #007A8C;
  color: white;
}

th, td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tbody tr:hover {
  background-color: #f2f9fa;
}

a {
  color: #007A8C;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
  display: flex;
  gap: 8px;
}

.edit-btn,
.delete-btn {
  border: none;
  cursor: pointer;
  border-radius: 5px;
  padding: 6px;
  transition: 0.2s;
}

.edit-btn {
  background-color: #F1C40F;
  color: white;
}

.edit-btn:hover {
  background-color: #D4AC0D;
}

.delete-btn {
  background-color: #E74C3C;
  color: white;
}

.delete-btn:hover {
  background-color: #C0392B;
}
</style>