<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Category-Based Product Reviews</title>
  <link rel="stylesheet" href="css/category.css" />
</head>
<body>
  <?php  require('includes/nav.php'); ?>
  <header>
    <h1>Product Review Comparison</h1>
    <br/>
    <p>Select a category and search for a product to compare reviews from different sources.</p>
    <br/>
  </header>

  <main>
    <div class="category-selector">
      <label for="category">Choose Category:</label>
      <select id="category">
        <option value="">-- Select --</option>
        <option value="Electronics">Electronics</option>
        <option value="Home Appliances">Home Appliances</option>
        <option value="Others">Others</option>
      </select>
    </div>

    <div class="search-box">
      <input type="text" id="searchInput" placeholder="Search for a product..." />
      <button onclick="searchProduct()">Search</button>
    </div>

    <div id="results"></div>
  </main>

  <script>
    const PEXELS_API_KEY = "AlWmpFRJKLOZRud2GGurcGPL6OeTS7aUqdM6g5jMtO6a2mKkZUA5tsUH";

    function slugify(text) {
      return text.toLowerCase().trim().replace(/\s+/g, '+').replace(/[^\w\-+]+/g, '');
    }

    async function fetchImages(query, count = 4) {
      const response = await fetch(`https://api.pexels.com/v1/search?query=${encodeURIComponent(query)}&per_page=${count}`, {
        headers: {
          Authorization: PEXELS_API_KEY
        }
      });
      const data = await response.json();
      return data.photos.map(photo => photo.src.medium);
    }

    async function searchProduct() {
      const category = document.getElementById("category").value;
      const search = document.getElementById("searchInput").value.trim();
      const results = document.getElementById("results");
      results.innerHTML = "";

      if (!category || !search) {
        results.innerHTML = "<p>Please select a category and enter a product name to search.</p>";
        return;
      }

      const slug = slugify(search);
      let imageUrls = await fetchImages(search, 4);
      const fallbackImage = "https://via.placeholder.com/150x150?text=No+Image";

      const sources = {
        Amazon: `https://www.amazon.com/s?k=${slug}`,
        Daraz: `https://www.daraz.pk/catalog/?q=${slug}`,
        Temu: `https://www.temu.com/search?q=${slug}`,
        AliExpress: `https://www.aliexpress.com/wholesale?SearchText=${slug}`
      };

      const container = document.createElement("div");
      container.className = "product-box";

      let sourcesHTML = "";
      const sourceKeys = Object.keys(sources);
      sourceKeys.forEach((source, index) => {
        const url = sources[source];
        const image = imageUrls[index] || fallbackImage;
        const fakeReview = `This ${search} from ${source} is rated ${(Math.random() * 1.5 + 3.5).toFixed(1)}/5`;
        sourcesHTML += `
          <div class="source-review" style="border: 1px solid #ccc; padding: 10px; margin: 10px 0; display: flex; align-items: center;">
            <img src="${image}" alt="${source} image" style="width: 100px; height: 100px; object-fit: cover; margin-right: 20px;">
            <div>
              <a href="${url}" target="_blank" style="font-size: 18px; font-weight: bold;">${source}</a>
              <p>${fakeReview}</p>
            </div>
          </div>`;
      });

      container.innerHTML = `<h2>Search Results for "${search}"</h2>${sourcesHTML}`;
      results.appendChild(container);
    }
  </script>
 <?php  require('includes/footer.php'); ?>
</body>
</html>
