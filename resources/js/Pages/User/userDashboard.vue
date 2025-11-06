<template>
  <div class="userDashboard-body">
    <!-- Welcome Header -->
    <header class="dashboard-header">
      <div class="user-welcome">
        <div class="user-avatar">
          <span class="avatar-icon">👤</span>
        </div>
        <div class="welcome-text">
          <h1 class="title">Welcome back, Traveler!</h1>
          <p class="subtitle">
            Ready to explore Surigao's hidden gems? Check out our latest recommendations below 🌊
          </p>
        </div>
      </div>
    
    </header>
   
    <!-- Blog / News Embed -->
    <section class="blog-section">
      <div class="section-header">
        <h2 class="blog-title">Latest from Our Blog</h2>
        <p class="blog-desc">
          Read featured news and stories about Surigao tourism and community events.
        </p>
      </div>

      <!-- iFrame showing sample blog/news -->
      <div class="iframe-container">
        <div class="iframe-header">
          <div class="iframe-tabs">
            <button 
              :class="['tab-button', { active: activeTab === 'blog' }]" 
              @click="activeTab = 'blog'"
            >
              Travel Blog
            </button>
            <button 
              :class="['tab-button', { active: activeTab === 'news' }]" 
              @click="activeTab = 'news'"
            >
              Local News
            </button>
          </div>
          <button class="refresh-button" @click="refreshContent">
            <span class="refresh-icon">🔄</span>
            Refresh
          </button>
        </div>
        <iframe
          :src="activeTab === 'blog' ? blogUrl : newsUrl"
          :title="activeTab === 'blog' ? 'Surigao Blog' : 'Surigao News'"
          frameborder="0"
          allowfullscreen
          ref="blogIframe"
        ></iframe>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// Reactive data
const activeTab = ref('blog')
const blogIframe = ref(null)

// URLs for iframe content
const blogUrl = 'https://www.choosephilippines.com/specials/travel/5485/surigao-hidden-paradise'
const newsUrl = 'https://surigaodelnorte.gov.ph/news/'

// Methods
const navigateTo = (destination) => {
  // In a real app, this would use Vue Router
  alert(`Navigating to ${destination} section`)
}

const refreshContent = () => {
  if (blogIframe.value) {
    blogIframe.value.src = activeTab.value === 'blog' ? blogUrl : newsUrl
  }
}
</script>

<style scoped>
.userDashboard-body {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  background: linear-gradient(to bottom, #e1f3f9, #f9fdfd);
  padding: 30px 60px;
  display: flex;
  flex-direction: column;
  gap: 30px;
}

/* Header */
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 10px;
}
.user-welcome {
  display: flex;
  align-items: center;
  gap: 15px;
}
.user-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2a7a7a, #213f3f);
  display: flex;
  align-items: center;
  justify-content: center;
}
.avatar-icon {
  font-size: 1.8rem;
}
.welcome-text {
  max-width: 500px;
}
.title {
  font-size: 2.2rem;
  font-weight: 800;
  color: #213f3f;
  margin-bottom: 5px;
}
.subtitle {
  color: #4b4b4b;
  font-size: 1.1rem;
  margin-top: 5px;
}
.weather-widget {
  display: flex;
  align-items: center;
  gap: 10px;
  background: white;
  border-radius: 15px;
  padding: 10px 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}
.weather-icon {
  font-size: 2rem;
}
.weather-temp {
  font-size: 1.3rem;
  font-weight: 700;
  color: #213f3f;
}
.weather-location {
  font-size: 0.8rem;
  color: #666;
}

/* Stats Section */
.stats-section {
  margin: 10px 0;
}
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}
.stat-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  align-items: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}
.stat-icon {
  font-size: 2.5rem;
  margin-right: 15px;
}
.stat-number {
  font-size: 1.8rem;
  font-weight: 700;
  color: #213f3f;
  margin: 0;
}
.stat-label {
  color: #666;
  margin: 0;
  font-size: 0.9rem;
}

/* Banner */
.featured-banner {
  background-image: url("/images/siargao-banner.jpg");
  background-size: cover;
  background-position: center;
  border-radius: 20px;
  position: relative;
  height: 300px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
.banner-overlay {
  background: linear-gradient(135deg, rgba(33, 63, 63, 0.75), rgba(42, 122, 122, 0.7));
  color: white;
  padding: 40px;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.banner-content {
  max-width: 600px;
}
.popular-badge {
  display: inline-flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50px;
  padding: 8px 15px;
  margin-bottom: 15px;
  backdrop-filter: blur(5px);
}
.popular-text {
  font-size: 0.9rem;
}
.banner-title {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 10px;
}
.banner-subtext {
  font-size: 1.1rem;
  margin-bottom: 20px;
  opacity: 0.9;
}
.banner-stats {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}
.banner-stat {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
}
.stat-label {
  font-size: 0.8rem;
  opacity: 0.8;
}
.cta-button {
  background: #ff7e5f;
  color: white;
  border: none;
  border-radius: 50px;
  padding: 12px 25px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(255, 126, 95, 0.3);
}
.cta-button:hover {
  background: #ff6b4a;
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(255, 126, 95, 0.4);
}

/* Actions Section */
.actions-section {
  margin: 20px 0;
}
.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #213f3f;
  margin-bottom: 20px;
}
.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}
.action-card {
  background: white;
  border-radius: 15px;
  padding: 25px 20px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}
.action-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  background: #f0f9ff;
}
.action-icon {
  font-size: 2.5rem;
  margin-bottom: 15px;
}
.action-card h3 {
  font-size: 1.2rem;
  color: #213f3f;
  margin-bottom: 10px;
}
.action-card p {
  color: #666;
  font-size: 0.9rem;
  margin: 0;
}

/* Recommendations Section */
.recommendations-section {
  margin: 20px 0;
}
.section-header {
  margin-bottom: 25px;
}
.section-desc {
  font-size: 1rem;
  color: #555;
  max-width: 600px;
  margin-top: 5px;
}
.recommendations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}
.recommendation-card {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.recommendation-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}
.rec-image {
  height: 160px;
  background-size: cover;
  background-position: center;
}
.rec-content {
  padding: 20px;
}
.rec-content h3 {
  font-size: 1.2rem;
  color: #213f3f;
  margin-bottom: 8px;
}
.rec-content p {
  color: #666;
  font-size: 0.9rem;
  margin-bottom: 15px;
}
.rec-meta {
  display: flex;
  justify-content: space-between;
  font-size: 0.8rem;
}
.rec-distance {
  color: #2a7a7a;
}
.rec-rating {
  color: #ff7e5f;
}

/* Events Section */
.events-section {
  margin: 20px 0;
}
.events-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}
.event-item {
  display: flex;
  align-items: center;
  background: white;
  border-radius: 15px;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.event-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}
.event-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #f0f9ff;
  border-radius: 10px;
  padding: 10px 15px;
  margin-right: 20px;
}
.date-day {
  font-size: 1.5rem;
  font-weight: 700;
  color: #213f3f;
}
.date-month {
  font-size: 0.8rem;
  color: #666;
}
.event-details {
  flex: 1;
}
.event-title {
  font-size: 1.1rem;
  color: #213f3f;
  margin-bottom: 5px;
}
.event-location {
  font-size: 0.9rem;
  color: #666;
}
.event-action {
  background: #2a7a7a;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 8px 15px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}
.event-action:hover {
  background: #213f3f;
}

/* Blog Section */
.blog-section {
  margin: 20px 0 40px;
}
.blog-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #213f3f;
  margin-bottom: 10px;
}
.blog-desc {
  font-size: 1rem;
  color: #555;
  max-width: 600px;
}

/* iFrame */
.iframe-container {
  width: 100%;
  max-width: 1000px;
  height: 500px;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  margin: 0 auto;
  background: white;
}
.iframe-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background: #f5f9fa;
  border-bottom: 1px solid #e1e8ea;
}
.iframe-tabs {
  display: flex;
  gap: 5px;
}
.tab-button {
  padding: 8px 16px;
  border: none;
  background: transparent;
  border-radius: 20px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}
.tab-button.active {
  background: #213f3f;
  color: white;
}
.tab-button:not(.active):hover {
  background: #e1f3f9;
}
.refresh-button {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #e1f3f9;
  border: none;
  border-radius: 20px;
  padding: 8px 15px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}
.refresh-button:hover {
  background: #cde9f2;
}
.refresh-icon {
  font-size: 0.9rem;
}
.iframe-container iframe {
  width: 100%;
  height: calc(100% - 60px);
  border: none;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .userDashboard-body {
    padding: 20px 30px;
  }
}

@media (max-width: 768px) {
  .userDashboard-body {
    padding: 15px;
  }
  
  .dashboard-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .title {
    font-size: 1.8rem;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .actions-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .featured-banner {
    height: 250px;
  }
  
  .banner-overlay {
    padding: 25px;
  }
  
  .banner-title {
    font-size: 1.6rem;
  }
  
  .recommendations-grid {
    grid-template-columns: 1fr;
  }
  
  .iframe-container {
    height: 400px;
  }
  
  .iframe-header {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .actions-grid {
    grid-template-columns: 1fr;
  }
  
  .featured-banner {
    height: 220px;
  }
  
  .banner-overlay {
    padding: 20px;
  }
  
  .banner-title {
    font-size: 1.4rem;
  }
  
  .event-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .event-date {
    flex-direction: row;
    gap: 10px;
  }
  
  .iframe-container {
    height: 350px;
  }
}
</style>