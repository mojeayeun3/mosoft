/**
 * Portfolio Grid with Lightbox
 * Click on project cards to view all photos in a lightbox modal
 */

(function() {
    'use strict';

    // Project data - configure your projects here
    const projects = {
        1: {
            title: '[in progress]Coway Canada Website Redesign',
            photos: ['01_01_Coway_main_top_page.png',
                '01_02_Coway_main_bottom_page.png',
                '01_03_Coway_water_product_category_page.png',
                '01_04_Coway_product_price_information.png',
                '01_05_Coway_contactus_page.png',
                '01_06_Coway_faq_blog.png',
                '01_07_Coway_SEO_JSON_LD.png'
            ]
        },
        2: {
            title: '[in progress]David Renovation Website Optimization',
            photos: ['02_01_David_Reno_Spped_check.png',
                '02_02_David_Renovation_network_analysis.png',
                '02_03_David_Renovation_SEO_keyword.png',
                '02_04_David_Renovation_SEO_competitor_extract.png'
            ]
        },
        3: {
            title: 'AI Bitcoin Automated Trading System',
            photos: ['03_mosoft_bitcoin_treading_system.png']
        },
        4: {
            title: 'Uphere Worship Church Responsive Website',
            photos: ['04_uphereworship_church_homepage.png']
        },
        5: {
            title: 'LemonTree Sushi Restaurant Website',
            photos: ['05_01_LemonTree_S_S_homepage.png',
                '05_02_LemonTree_Requirement_Analysis.png'
            ]
        },
        6: {
            title: 'Gorudoya Sushi Restaurant Website',
            photos: ['06_01_Gorudoya_menu.png',
                '06_02_Gorudoya_test.png'
            ]
        },
        7: {
            title: 'Korean Food Delivery Ordering Platform',
            photos: ['07_Korea_order_system.png']
        },
        8: {
            title: 'Toronto Korean Realtor Information Platform',
            photos: ['08_infonetworks.png']
        },
        9: {
            title: 'Toronto Korean Business Connection Service',
            photos: ['09_Goohagi_app.png']
        },
        10: {
            title: 'Fanbattle Ice Hockey Betting System',
            photos: ['10_01_fanbattle_main.png',
                '10_02_fanbattle_setup.png'
            ]
        },
        11: {
            title: 'SOX Internal Control Web System',
            photos: ['11_01_KSOX.png']
        }
    };

    let currentProject = null;
    let currentPhotoIndex = 0;

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initPortfolioGrid();
    });

    function initPortfolioGrid() {
        const portfolioCards = document.querySelectorAll('.portfolio-card');
        const lightbox = document.getElementById('portfolio-lightbox');

        if (!lightbox) return;

        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxCounter = document.getElementById('lightbox-counter');
        const closeBtn = document.querySelector('.lightbox-close');
        const prevBtn = document.querySelector('.lightbox-prev');
        const nextBtn = document.querySelector('.lightbox-next');

        // Add click event to each portfolio card
        portfolioCards.forEach(function(card) {
            card.addEventListener('click', function() {
                const projectId = card.getAttribute('data-project');
                openLightbox(projectId);
            });
        });

        // Close lightbox
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeLightbox();
        });

        // Close on background click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                e.preventDefault();
                e.stopPropagation();
                closeLightbox();
            }
        });

        // Navigation buttons
        prevBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            showPrevPhoto();
        });

        nextBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            showNextPhoto();
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;

            if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                showPrevPhoto();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                showNextPhoto();
            }
        });

        function openLightbox(projectId) {
            currentProject = projects[projectId];
            if (!currentProject) return;

            currentPhotoIndex = 0;
            updateLightboxContent();
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
            currentProject = null;
            currentPhotoIndex = 0;
        }

        function showPrevPhoto() {
            if (!currentProject) return;
            currentPhotoIndex = (currentPhotoIndex - 1 + currentProject.photos.length) % currentProject.photos.length;
            updateLightboxContent();
        }

        function showNextPhoto() {
            if (!currentProject) return;
            currentPhotoIndex = (currentPhotoIndex + 1) % currentProject.photos.length;
            updateLightboxContent();
        }

        function updateLightboxContent() {
            if (!currentProject) return;

            const photoPath = '/images/portfolio/' + currentProject.photos[currentPhotoIndex];
            lightboxImg.src = photoPath;
            lightboxTitle.textContent = currentProject.title;

            if (currentProject.photos.length > 1) {
                lightboxCounter.textContent = `Photo ${currentPhotoIndex + 1} of ${currentProject.photos.length}`;
                prevBtn.style.display = 'flex';
                nextBtn.style.display = 'flex';
            } else {
                lightboxCounter.textContent = '';
                prevBtn.style.display = 'none';
                nextBtn.style.display = 'none';
            }
        }

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        lightbox.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        lightbox.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - show next
                    showNextPhoto();
                } else {
                    // Swipe right - show previous
                    showPrevPhoto();
                }
            }
        }
    }

})();