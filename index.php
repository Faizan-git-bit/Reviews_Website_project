<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ReviewSphere - Compare Product Reviews</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="css/index.css" />
</head>
<body>

  
  <?php  require('includes/nav.php'); ?>

  
  <header id="hero-section">
    <h1>ReviewSphere</h1>
    <p>Search any product and compare reviews across multiple sources</p>
  </header>

  <!-- SEARCH BAR -->
  <section class="search-section">
    <div class="search-bar">
      <input type="text" id="searchInput" placeholder="e.g. iPhone 14, Gaming Chair" onkeypress="handleKeyPress(event)" />
      <button onclick="searchProduct()">Search</button>
    </div>
  </section>

  <!-- SEARCH RESULTS -->
  <div id="comparison-results"></div>

  <!-- FEATURED PRODUCTS -->
  <section id="featured-products"></section>

  <!-- SCRIPT -->
  <script>
    const PEXELS_API_KEY = "AlWmpFRJKLOZRud2GGurcGPL6OeTS7aUqdM6g5jMtO6a2mKkZUA5tsUH";
    const fallbackImage = "https://via.placeholder.com/150x150?text=No+Image";

    const featuredNames = [
      "Bluetooth Speaker", "Running Shoes", "Laptop Stand", "Coffee Maker",
      "Wireless Earbuds", "Smartwatch", "Desk Lamp", "Backpack"
    ];

    function handleKeyPress(e) {
      if (e.key === "Enter") {
        searchProduct();
      }
    }

    function slugify(text) {
      return text.toLowerCase().trim().replace(/\s+/g, '+').replace(/[^\w\-+]+/g, '');
    }

    async function fetchImages(query, count = 4) {
      try {
        const response = await fetch(`https://api.pexels.com/v1/search?query=${encodeURIComponent(query)}&per_page=${count}`, {
          headers: { Authorization: PEXELS_API_KEY }
        });
        const data = await response.json();
        return data.photos.map(photo => photo.src.medium);
      } catch {
        return Array(count).fill(fallbackImage);
      }
    }

    async function searchProduct(productName = null) {
      const query = productName || document.getElementById("searchInput").value.trim();
      if (!query) return;

      // Clear input if user clicked a product
      if (productName) {
        document.getElementById("searchInput").value = "";
      }

      const container = document.getElementById("comparison-results");
      container.innerHTML = "";

      const slug = slugify(query);
      const imageUrls = await fetchImages(query, 4);

      const sources = {
        Amazon: `https://www.amazon.com/s?k=${slug}`,
        Daraz: `https://www.daraz.pk/catalog/?q=${slug}`,
        Temu: `https://www.temu.com/search?q=${slug}`,
        AliExpress: `https://www.aliexpress.com/wholesale?SearchText=${slug}`
      };

      let sourcesHTML = "";
      Object.keys(sources).forEach((source, index) => {
        const image = imageUrls[index] || fallbackImage;
        const fakeReview = `This ${query} from ${source} is rated ${(Math.random() * 1.5 + 3.5).toFixed(1)}/5.`;
        sourcesHTML += `
          <div class="source-review">
            <img src="${image}" alt="${source}">
            <div>
              <a href="${sources[source]}" target="_blank">${source}</a>
              <p>${fakeReview}</p>
            </div>
          </div>`;
      });

      container.innerHTML = `
        <div class="product-box">
          <h2>Search Results for "<em>${query}</em>"</h2>
          ${sourcesHTML}
        </div>`;
    }

    async function fetchFeaturedImage(name) {
      try {
        const res = await fetch(`https://api.pexels.com/v1/search?query=${name}&per_page=1`, {
          headers: { Authorization: PEXELS_API_KEY }
        });
        const data = await res.json();
        return data.photos[0]?.src.medium || fallbackImage;
      } catch {
        return fallbackImage;
      }
    }

    async function loadFeaturedProducts() {
      const container = document.getElementById("featured-products");
      for (let name of featuredNames) {
        const img = await fetchFeaturedImage(name);
        const card = document.createElement("div");
        card.className = "product-card";
        card.innerHTML = `<img src="${img}" alt="${name}"><h4>${name}</h4>`;
        card.onclick = () => searchProduct(name);
        container.appendChild(card);
      }
    }

    // Clear search input and results when navigating back
    window.addEventListener("pageshow", function () {
      document.getElementById("searchInput").value = "";
      document.getElementById("comparison-results").innerHTML = "";
    });

    loadFeaturedProducts();
  </script>
  <?php  require('includes/footer.php'); ?>



</body>
</html>
