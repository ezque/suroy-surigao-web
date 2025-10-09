<template>
  <div class="userDashboard-body">
    <!-- Welcome Header -->
    <header class="dashboard-header">
      <h1 class="title">Welcome to Suroy Surigao!</h1>
      <p class="subtitle">
        Discover amazing tourist spots, explore tour packages, and stay updated with Surigao’s latest stories 🌊
      </p>
    </header>

    <!-- Charter Day Banner -->
    <section class="featured-banner">
      <div class="banner-overlay">
        <h2 class="banner-title">55th Charter Day Celebration</h2>
        <p class="banner-subtext">
          Celebrating our journey and shaping a progressive, resilient Surigao.
        </p>
      </div>
    </section>

    <!-- Blog / News Embed -->
    <section class="blog-section">
      <h2 class="blog-title">Latest from Our Blog</h2>
      <p class="blog-desc">
        Read featured news and stories about Surigao tourism and community events.
      </p>

      <!-- iFrame showing sample blog/news -->
      <div class="iframe-container">
        <iframe
          src="https://www.choosephilippines.com/specials/travel/5485/surigao-hidden-paradise"
          title="Surigao Blog"
          frameborder="0"
          allowfullscreen
        ></iframe>
      </div>
    </section>
  </div>
</template>

<script setup>
// No blog logic needed — just static embed for user dashboard
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
  text-align: center;
  margin-top: 10px;
}
.title {
  font-size: 2rem;
  font-weight: 700;
  color: #213f3f;
}
.subtitle {
  color: #4b4b4b;
  font-size: 1rem;
  margin-top: 5px;
}

/* Banner */
.featured-banner {
  background-image: url("/images/charterday-banner.jpg");
  background-size: cover;
  background-position: center;
  border-radius: 20px;
  position: relative;
  height: 250px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
.banner-overlay {
  background: rgba(33, 63, 63, 0.55);
  color: white;
  padding: 40px;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.banner-title {
  font-size: 1.8rem;
  font-weight: bold;
}
.banner-subtext {
  font-size: 1rem;
  max-width: 600px;
  margin-top: 10px;
}

/* Blog Section */
.blog-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}
.blog-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #213f3f;
}
.blog-desc {
  font-size: 1rem;
  color: #555;
  margin-bottom: 15px;
}

/* iFrame */
.iframe-container {
  width: 100%;
  max-width: 1000px;
  height: 500px;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.iframe-container iframe {
  width: 100%;
  height: 100%;
  border: none;
}

@media (max-width: 768px) {
  .userDashboard-body {
    padding: 20px;
  }

  .iframe-container {
    height: 400px;
  }
}
</style>
