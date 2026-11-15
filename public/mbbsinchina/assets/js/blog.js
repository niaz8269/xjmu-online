// Blog Data
const blogPosts = [
    {
        id: 1,
        title: "Complete Guide to MBBS Admission Process in China 2023",
        excerpt: "Learn everything about the MBBS admission process in Chinese universities, including eligibility criteria, required documents, application timeline, and tips for a successful application.",
        category: "Admission Guide",
        tags: ["MBBS", "Admission", "China", "Application", "Process"],
        image: "https://images.unsplash.com/photo-1586773860418-d37222d8fce3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80",
        date: "June 15, 2023",
        author: "Dr. Zhang Wei"
    },
    {
        id: 2,
        title: "A Day in the Life of an MBBS Student in China",
        excerpt: "Follow a typical day of an international MBBS student in China - from morning lectures to clinical rotations and cultural experiences.",
        category: "Student Life",
        tags: ["Student Life", "MBBS", "China", "Experience", "Campus"],
        image: "https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80",
        date: "June 8, 2023",
        author: "Maria Rodriguez"
    },
    {
        id: 3,
        title: "Understanding CAS and Student Visa Process for China",
        excerpt: "Everything you need to know about Confirmation of Acceptance for Studies (CAS) and the student visa application process for China.",
        category: "Visa Information",
        tags: ["Visa", "CAS", "China", "Student Visa", "Process"],
        image: "https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80",
        date: "May 28, 2023",
        author: "Wang Li"
    },
    // Add more blog posts as needed...
];

// Pagination variables
const postsPerPage = 4;
let currentPage = 1;
let filteredPosts = [...blogPosts];
let activeCategory = 'all';
let activeTag = 'all';
let searchTerm = '';

// Initialize the blog
document.addEventListener('DOMContentLoaded', function() {
    renderBlogPosts();
    renderCategories();
    renderRecentPosts();
    renderTags();
    renderPagination();
    
    // Search functionality
    document.getElementById('search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        searchTerm = document.getElementById('search-input').value.toLowerCase();
        filterPosts();
    });

    // Newsletter subscription
    document.getElementById('newsletter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('newsletter-email').value;
        if (email.trim() !== '') {
            alert(`Thank you for subscribing with: ${email}`);
            this.reset();
        }
    });
});

// Render blog posts
function renderBlogPosts() {
    const container = document.getElementById('blog-posts-container');
    const noResults = document.getElementById('no-results');
    
    container.innerHTML = '';
    
    const startIndex = (currentPage - 1) * postsPerPage;
    const endIndex = startIndex + postsPerPage;
    const postsToShow = filteredPosts.slice(startIndex, endIndex);
    
    if (postsToShow.length === 0) {
        noResults.style.display = 'block';
        container.style.display = 'none';
    } else {
        noResults.style.display = 'none';
        container.style.display = 'grid';
        
        postsToShow.forEach(post => {
            const card = document.createElement('article');
            card.className = 'blog-card scale-on-hover';
            card.innerHTML = `
                <div class="blog-card-img" style="background-image: url('${post.image}')">
                    <div class="blog-card-category">${post.category}</div>
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span><i class="far fa-calendar"></i> ${post.date}</span>
                        <span><i class="far fa-user"></i> ${post.author}</span>
                    </div>
                    <h3><a href="#">${post.title}</a></h3>
                    <p>${post.excerpt}</p>
                    <div class="blog-card-actions">
                        <a href="#" class="btn btn-outline">Read More</a>
                        <a href="https://wa.me/923421580046?text=Hello!%20I%20have%20a%20question%20about:%20${encodeURIComponent(post.title)}" class="btn btn-whatsapp" target="_blank">
                            <i class="fab fa-whatsapp"></i> Ask on WhatsApp
                        </a>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }
}

// Render categories
function renderCategories() {
    const categoriesList = document.getElementById('categories-list');
    const categories = [...new Set(blogPosts.map(post => post.category))];
    
    // Add "All Categories" option
    categoriesList.innerHTML = `
        <li>
            <a href="#" class="${activeCategory === 'all' ? 'active' : ''}" data-category="all">
                All Categories <span>${blogPosts.length}</span>
            </a>
        </li>
    `;
    
    categories.forEach(category => {
        const count = blogPosts.filter(post => post.category === category).length;
        const listItem = document.createElement('li');
        listItem.innerHTML = `
            <a href="#" class="${activeCategory === category ? 'active' : ''}" data-category="${category}">
                ${category} <span>${count}</span>
            </a>
        `;
        categoriesList.appendChild(listItem);
    });
    
    // Add event listeners to category links
    document.querySelectorAll('#categories-list a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            activeCategory = this.getAttribute('data-category');
            filterPosts();
            
            // Update active state
            document.querySelectorAll('#categories-list a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

// Render recent posts
function renderRecentPosts() {
    const container = document.getElementById('recent-posts-container');
    const recentPosts = [...blogPosts].sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 3);
    
    container.innerHTML = '';
    
    recentPosts.forEach(post => {
        const recentPost = document.createElement('div');
        recentPost.className = 'recent-post';
        recentPost.innerHTML = `
            <div class="recent-post-img" style="background-image: url('${post.image}')"></div>
            <div class="recent-post-content">
                <h4><a href="#">${post.title}</a></h4>
                <span>${post.date}</span>
            </div>
        `;
        container.appendChild(recentPost);
    });
}

// Render tags
function renderTags() {
    const tagsList = document.getElementById('tags-list');
    const allTags = blogPosts.flatMap(post => post.tags);
    const uniqueTags = [...new Set(allTags)];
    
    // Add "All Tags" option
    tagsList.innerHTML = `<a href="#" class="${activeTag === 'all' ? 'active' : ''}" data-tag="all">All Tags</a>`;
    
    uniqueTags.forEach(tag => {
        const tagLink = document.createElement('a');
        tagLink.href = '#';
        tagLink.textContent = tag;
        tagLink.setAttribute('data-tag', tag);
        if (activeTag === tag) {
            tagLink.classList.add('active');
        }
        tagsList.appendChild(tagLink);
    });
    
    // Add event listeners to tag links
    document.querySelectorAll('#tags-list a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            activeTag = this.getAttribute('data-tag');
            filterPosts();
            
            // Update active state
            document.querySelectorAll('#tags-list a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

// Render pagination
function renderPagination() {
    const paginationContainer = document.getElementById('blog-pagination');
    const totalPages = Math.ceil(filteredPosts.length / postsPerPage);
    
    paginationContainer.innerHTML = '';
    
    if (totalPages <= 1) return;
    
    // Previous button
    const prevButton = document.createElement('a');
    prevButton.href = '#';
    prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
    if (currentPage === 1) {
        prevButton.style.opacity = '0.5';
        prevButton.style.pointerEvents = 'none';
    }
    prevButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage > 1) {
            currentPage--;
            renderBlogPosts();
            renderPagination();
        }
    });
    paginationContainer.appendChild(prevButton);
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        const pageLink = document.createElement('a');
        pageLink.href = '#';
        pageLink.textContent = i;
        if (i === currentPage) {
            pageLink.classList.add('active');
        }
        pageLink.addEventListener('click', (e) => {
            e.preventDefault();
            currentPage = i;
            renderBlogPosts();
            renderPagination();
        });
        paginationContainer.appendChild(pageLink);
    }
    
    // Next button
    const nextButton = document.createElement('a');
    nextButton.href = '#';
    nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
    if (currentPage === totalPages) {
        nextButton.style.opacity = '0.5';
        nextButton.style.pointerEvents = 'none';
    }
    nextButton.addEventListener('click', (e) => {
        e.preventDefault();
        if (currentPage < totalPages) {
            currentPage++;
            renderBlogPosts();
            renderPagination();
        }
    });
    paginationContainer.appendChild(nextButton);
}

// Filter posts based on category, tag, and search term
function filterPosts() {
    filteredPosts = blogPosts.filter(post => {
        // Category filter
        const matchesCategory = activeCategory === 'all' || post.category === activeCategory;
        
        // Tag filter
        const matchesTag = activeTag === 'all' || post.tags.includes(activeTag);
        
        // Search filter
        const matchesSearch = searchTerm === '' || 
            post.title.toLowerCase().includes(searchTerm) ||
            post.excerpt.toLowerCase().includes(searchTerm) ||
            post.tags.some(tag => tag.toLowerCase().includes(searchTerm));
        
        return matchesCategory && matchesTag && matchesSearch;
    });
    
    currentPage = 1;
    renderBlogPosts();
    renderPagination();
}