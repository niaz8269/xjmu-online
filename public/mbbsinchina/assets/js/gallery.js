// Gallery Data - This can be extended or loaded from an API
const galleryData = {
    'index': [
        {
            id: 1,
            title: 'Modern Campus Facilities',
            description: 'State-of-the-art infrastructure and learning environments at Peking University',
            category: 'campus',
            image: 'images/gallery/campus-1.jpg',
            thumbnail: 'images/gallery/campus-1-thumb.jpg',
            date: '2024-01-15'
        },
        {
            id: 2,
            title: 'Clinical Training Sessions',
            description: 'Hands-on experience in affiliated hospitals with expert supervision',
            category: 'clinical',
            image: 'images/gallery/clinical-1.jpg',
            thumbnail: 'images/gallery/clinical-1-thumb.jpg',
            date: '2024-01-10'
        },
        {
            id: 3,
            title: 'Student Accommodation',
            description: 'Comfortable and secure dormitories for international students',
            category: 'student-life',
            image: 'images/gallery/accommodation-1.jpg',
            thumbnail: 'images/gallery/accommodation-1-thumb.jpg',
            date: '2024-01-08'
        },
        {
            id: 4,
            title: 'Advanced Laboratories',
            description: 'Well-equipped labs for practical medical training and research',
            category: 'facilities',
            image: 'images/gallery/lab-1.jpg',
            thumbnail: 'images/gallery/lab-1-thumb.jpg',
            date: '2024-01-05'
        },
        {
            id: 5,
            title: 'Library Resources',
            description: 'Extensive medical literature and digital resources available 24/7',
            category: 'facilities',
            image: 'images/gallery/library-1.jpg',
            thumbnail: 'images/gallery/library-1-thumb.jpg',
            date: '2024-01-03'
        },
        {
            id: 6,
            title: 'International Student Community',
            description: 'Diverse cultural exchange and student support activities',
            category: 'student-life',
            image: 'images/gallery/community-1.jpg',
            thumbnail: 'images/gallery/community-1-thumb.jpg',
            date: '2024-01-01'
        }
    ],
    'admissions': [
        // Additional gallery items specific to admissions page
        {
            id: 7,
            title: 'Admission Counseling',
            description: 'Personalized guidance for university selection and application process',
            category: 'student-life',
            image: 'images/gallery/counseling-1.jpg',
            thumbnail: 'images/gallery/counseling-1-thumb.jpg',
            date: '2024-02-01'
        }
    ],
    'blog': [
        // Additional gallery items specific to blog page
        {
            id: 8,
            title: 'Campus Events',
            description: 'Regular seminars, workshops, and cultural events for students',
            category: 'student-life',
            image: 'images/gallery/events-1.jpg',
            thumbnail: 'images/gallery/events-1-thumb.jpg',
            date: '2024-02-05'
        }
    ]
};

// Gallery Manager Class
class GalleryManager {
    constructor() {
        this.currentPage = this.getCurrentPage();
        this.currentItems = [];
        this.filteredItems = [];
        this.currentFilter = 'all';
        this.currentSearch = '';
        this.itemsPerPage = 6;
        this.currentPageIndex = 0;
        this.currentModalIndex = 0;
        
        this.initializeGallery();
    }
    
    getCurrentPage() {
        const path = window.location.pathname;
        const page = path.split('/').pop() || 'index.html';
        
        if (page.includes('admissions')) return 'admissions';
        if (page.includes('blog')) return 'blog';
        return 'index';
    }
    
    initializeGallery() {
        // Check if gallery section exists on the page
        const gallerySection = document.getElementById('gallery');
        if (!gallerySection) {
            console.log('Gallery section not found on this page');
            return;
        }
        
        this.loadGalleryData();
        this.renderGallery();
        this.attachEventListeners();
        
        console.log(`Gallery initialized for ${this.currentPage} page`);
    }
    
    loadGalleryData() {
        // Get page-specific data or fallback to index data
        this.currentItems = galleryData[this.currentPage] || galleryData['index'];
        this.filteredItems = [...this.currentItems];
    }
    
    renderGallery() {
        const galleryGrid = document.getElementById('galleryGrid');
        const loadMoreSection = document.getElementById('galleryLoadMore');
        
        if (!galleryGrid) return;
        
        // Clear existing content
        galleryGrid.innerHTML = '';
        
        // Calculate items to show
        const itemsToShow = this.filteredItems.slice(0, (this.currentPageIndex + 1) * this.itemsPerPage);
        
        if (itemsToShow.length === 0) {
            this.showEmptyState();
            loadMoreSection.style.display = 'none';
            return;
        }
        
        // Render gallery items
        itemsToShow.forEach((item, index) => {
            const galleryItem = this.createGalleryItem(item, index);
            galleryGrid.appendChild(galleryItem);
        });
        
        // Show/hide load more button
        if (this.filteredItems.length > itemsToShow.length) {
            loadMoreSection.style.display = 'block';
        } else {
            loadMoreSection.style.display = 'none';
        }
    }
    
    createGalleryItem(item, index) {
        const itemElement = document.createElement('div');
        itemElement.className = 'gallery-item';
        itemElement.setAttribute('data-category', item.category);
        itemElement.setAttribute('data-index', index);
        
        // Use thumbnail if available, otherwise use main image
        const imageSrc = item.thumbnail || item.image;
        
        itemElement.innerHTML = `
            <img src="${imageSrc}" alt="${item.title}" loading="lazy" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIyNSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlIG5vdCBhdmFpbGFibGU8L3RleHQ+PC9zdmc+'">
            <div class="gallery-item-overlay">
                <span class="gallery-item-category">${this.formatCategory(item.category)}</span>
                <h3 class="gallery-item-title">${item.title}</h3>
                <p class="gallery-item-description">${item.description}</p>
            </div>
        `;
        
        // Add click event to open modal
        itemElement.addEventListener('click', () => {
            this.openModal(index);
        });
        
        return itemElement;
    }
    
    formatCategory(category) {
        return category.split('-').map(word => 
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }
    
    showEmptyState() {
        const galleryGrid = document.getElementById('galleryGrid');
        if (!galleryGrid) return;
        
        galleryGrid.innerHTML = `
            <div class="gallery-empty">
                <i class="fas fa-images"></i>
                <h3>No images found</h3>
                <p>Try adjusting your filters or search terms</p>
            </div>
        `;
    }
    
    attachEventListeners() {
        // Filter buttons
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleFilterChange(e.target);
            });
        });
        
        // Search input
        const searchInput = document.getElementById('gallerySearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });
        }
        
        // Load more button
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                this.loadMore();
            });
        }
        
        // Modal events
        this.attachModalEvents();
    }
    
    handleFilterChange(button) {
        // Update active filter button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
        
        // Update filter and re-render
        this.currentFilter = button.getAttribute('data-filter');
        this.currentPageIndex = 0;
        this.applyFilters();
    }
    
    handleSearch(searchTerm) {
        this.currentSearch = searchTerm.toLowerCase();
        this.currentPageIndex = 0;
        this.applyFilters();
    }
    
    applyFilters() {
        this.filteredItems = this.currentItems.filter(item => {
            // Apply category filter
            const categoryMatch = this.currentFilter === 'all' || item.category === this.currentFilter;
            
            // Apply search filter
            const searchMatch = !this.currentSearch || 
                item.title.toLowerCase().includes(this.currentSearch) ||
                item.description.toLowerCase().includes(this.currentSearch) ||
                item.category.toLowerCase().includes(this.currentSearch);
            
            return categoryMatch && searchMatch;
        });
        
        this.renderGallery();
    }
    
    loadMore() {
        this.currentPageIndex++;
        this.renderGallery();
        
        // Smooth scroll to new items
        setTimeout(() => {
            const galleryGrid = document.getElementById('galleryGrid');
            if (galleryGrid) {
                const lastItem = galleryGrid.lastElementChild;
                if (lastItem) {
                    lastItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        }, 100);
    }
    
    // Modal functionality
    attachModalEvents() {
        const modal = document.getElementById('galleryModal');
        const closeBtn = document.getElementById('galleryModalClose');
        const overlay = document.getElementById('galleryModalOverlay');
        const prevBtn = document.getElementById('galleryModalPrev');
        const nextBtn = document.getElementById('galleryModalNext');
        
        if (!modal) return;
        
        // Close modal events
        [closeBtn, overlay].forEach(element => {
            if (element) {
                element.addEventListener('click', () => {
                    this.closeModal();
                });
            }
        });
        
        // Navigation events
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                this.navigateModal(-1);
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                this.navigateModal(1);
            });
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!modal.classList.contains('active')) return;
            
            switch(e.key) {
                case 'Escape':
                    this.closeModal();
                    break;
                case 'ArrowLeft':
                    this.navigateModal(-1);
                    break;
                case 'ArrowRight':
                    this.navigateModal(1);
                    break;
            }
        });
    }
    
    openModal(index) {
        this.currentModalIndex = index;
        this.updateModal();
        
        const modal = document.getElementById('galleryModal');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    closeModal() {
        const modal = document.getElementById('galleryModal');
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    navigateModal(direction) {
        const newIndex = this.currentModalIndex + direction;
        
        // Circular navigation
        if (newIndex < 0) {
            this.currentModalIndex = this.filteredItems.length - 1;
        } else if (newIndex >= this.filteredItems.length) {
            this.currentModalIndex = 0;
        } else {
            this.currentModalIndex = newIndex;
        }
        
        this.updateModal();
    }
    
    updateModal() {
        const modalImage = document.getElementById('galleryModalImage');
        const modalTitle = document.getElementById('galleryModalTitle');
        const modalDescription = document.getElementById('galleryModalDescription');
        const modalCategory = document.getElementById('galleryModalCategory');
        
        if (!this.filteredItems[this.currentModalIndex]) return;
        
        const currentItem = this.filteredItems[this.currentModalIndex];
        
        if (modalImage) {
            modalImage.src = currentItem.image;
            modalImage.alt = currentItem.title;
        }
        
        if (modalTitle) modalTitle.textContent = currentItem.title;
        if (modalDescription) modalDescription.textContent = currentItem.description;
        if (modalCategory) modalCategory.textContent = this.formatCategory(currentItem.category);
    }
    
    // Public method to add new gallery items dynamically
    addGalleryItems(newItems) {
        this.currentItems.push(...newItems);
        this.applyFilters();
    }
    
    // Public method to refresh gallery with new data
    refreshGallery(newData) {
        this.currentItems = newData || galleryData[this.currentPage] || galleryData['index'];
        this.currentPageIndex = 0;
        this.applyFilters();
    }
}

// Initialize gallery when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.galleryManager = new GalleryManager();
});

// Export for global access if needed
window.GalleryManager = GalleryManager;