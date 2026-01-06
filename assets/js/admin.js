// Ultra Modern Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Dark Mode Toggle Functionality
    const themeToggle = document.createElement('div');
    themeToggle.className = 'theme-toggle';
    themeToggle.innerHTML = '<i class="fas fa-moon theme-toggle-icon"></i>';
    themeToggle.title = 'Dark Mode Toggle';
    document.body.appendChild(themeToggle);

    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);

    // Update toggle icon based on current theme
    const updateToggleIcon = () => {
        const icon = themeToggle.querySelector('.theme-toggle-icon');
        if (document.documentElement.getAttribute('data-theme') === 'dark') {
            icon.className = 'fas fa-sun theme-toggle-icon';
        } else {
            icon.className = 'fas fa-moon theme-toggle-icon';
        }
    };

    updateToggleIcon();

    // Toggle theme function
    const toggleTheme = () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateToggleIcon();

        // Add smooth transition effect
        document.body.style.transition = 'background-color 0.5s ease, color 0.5s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 500);
    };

    // Add click event to toggle button
    themeToggle.addEventListener('click', toggleTheme);

    // Loading Animation
    const loadingOverlay = document.querySelector('.loading-overlay');
    if (loadingOverlay) {
        setTimeout(() => {
            loadingOverlay.classList.add('hidden');
        }, 1000);
    }

    // Smooth Scrolling Enhancement
    const smoothScroll = (target, duration = 800) => {
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - startPosition;
        let startTime = null;

        const animation = (currentTime) => {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        };

        const ease = (t, b, c, d) => {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        };

        requestAnimationFrame(animation);
    };

    // Enhanced Scroll Animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    // Observe all cards and containers
    document.querySelectorAll('.stat-card-modern, .quick-action-card, .chart-container, .activity-feed, .system-health').forEach(card => {
        observer.observe(card);
    });

    // Mouse Follow Effect
    let mouseX = 0, mouseY = 0;
    let ballX = 0, ballY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    const updateBall = () => {
        ballX += (mouseX - ballX) * 0.1;
        ballY += (mouseY - ballY) * 0.1;

        const ball = document.querySelector('.mouse-ball');
        if (ball) {
            ball.style.left = ballX + 'px';
            ball.style.top = ballY + 'px';
        }

        requestAnimationFrame(updateBall);
    };

    // Create mouse follow ball
    const mouseBall = document.createElement('div');
    mouseBall.className = 'mouse-ball';
    mouseBall.style.cssText = `
        position: fixed;
        width: 20px;
        height: 20px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        transition: transform 0.1s ease;
    `;
    document.body.appendChild(mouseBall);
    updateBall();

    // Enhanced Hover Effects
    document.querySelectorAll('.quick-action-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.03)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Progress Bar Animation
    const animateProgressBars = () => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 500);
        });
    };

    animateProgressBars();

    // Chart.js Integration with Enhanced Styling
    const createChart = () => {
        const ctx = document.getElementById('userRegistrationChart');
        if (!ctx) return;

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(102, 126, 234, 0.8)');
        gradient.addColorStop(1, 'rgba(118, 75, 162, 0.8)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
                datasets: [{
                    label: 'Kullanıcı Kayıtları',
                    data: [12, 19, 3, 5, 2, 3, 9, 15, 8, 12, 6, 10],
                    borderColor: 'rgba(102, 126, 234, 1)',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(102, 126, 234, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: 'rgba(102, 126, 234, 1)',
                    pointHoverBorderColor: 'rgba(255, 255, 255, 1)',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#2c3e50',
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#2c3e50',
                        bodyColor: '#2c3e50',
                        borderColor: 'rgba(102, 126, 234, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' kullanıcı';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            color: '#7f8c8d',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            color: '#7f8c8d',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    };

    // Initialize chart when DOM is ready
    createChart();
    createSystemPerformanceChart();

    // System Performance Chart
    function createSystemPerformanceChart() {
        const ctx = document.getElementById('systemPerformanceChart');
        if (!ctx) return;

        const gradient1 = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient1.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradient1.addColorStop(1, 'rgba(5, 150, 105, 0.8)');

        const gradient2 = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient2.addColorStop(0, 'rgba(245, 158, 11, 0.8)');
        gradient2.addColorStop(1, 'rgba(217, 119, 6, 0.8)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                datasets: [{
                    label: 'CPU Kullanımı (%)',
                    data: [45, 32, 67, 89, 43, 56],
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: gradient1,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(16, 185, 129, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'RAM Kullanımı (%)',
                    data: [78, 65, 82, 95, 71, 83],
                    borderColor: 'rgba(245, 158, 11, 1)',
                    backgroundColor: gradient2,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(245, 158, 11, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: 'var(--text-primary)',
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                        titleColor: 'var(--text-primary)',
                        bodyColor: 'var(--text-secondary)',
                        borderColor: 'rgba(102, 126, 234, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '% ' + context.dataset.label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            color: 'var(--text-secondary)',
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            color: 'var(--text-secondary)',
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Live Updates Function
    function updateLiveStats() {
        // Simulate live data updates
        const stats = [
            { id: 'totalUsers', min: 1200, max: 1250 },
            { id: 'activeUsers', min: 450, max: 500 },
            { id: 'totalTickets', min: 850, max: 900 },
            { id: 'pendingTickets', min: 120, max: 150 },
            { id: 'systemLoad', min: 45, max: 85 },
            { id: 'serverUptime', min: 98, max: 100 }
        ];

        stats.forEach(stat => {
            const element = document.getElementById(stat.id);
            if (element) {
                const currentValue = parseInt(element.textContent.replace(/[^\d]/g, ''));
                const newValue = Math.floor(Math.random() * (stat.max - stat.min + 1)) + stat.min;

                if (currentValue !== newValue) {
                    element.classList.add('updating');
                    setTimeout(() => {
                        element.textContent = stat.id === 'serverUptime' ? newValue + '%' : newValue.toLocaleString();
                        element.classList.remove('updating');
                    }, 300);
                }
            }
        });

        // Update notifications feed
        updateNotificationsFeed();
    }

    // Update notifications feed with new activities
    function updateNotificationsFeed() {
        const feed = document.getElementById('notificationsFeed');
        if (!feed) return;

        const activities = [
            'Yeni kullanıcı kaydı yapıldı',
            'Destek talebi çözüldü',
            'Sistem güncellemesi tamamlandı',
            'Yeni araç eklendi',
            'Profil güncellemesi yapıldı'
        ];

        const randomActivity = activities[Math.floor(Math.random() * activities.length)];
        const time = new Date().toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });

        const newNotification = document.createElement('div');
        newNotification.className = 'notification-item new';
        newNotification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notification-content">
                <p>${randomActivity}</p>
                <span class="notification-time">${time}</span>
            </div>
        `;

        feed.insertBefore(newNotification, feed.firstChild);

        // Remove old notifications if more than 10
        while (feed.children.length > 10) {
            feed.removeChild(feed.lastChild);
        }

        // Remove 'new' class after animation
        setTimeout(() => {
            newNotification.classList.remove('new');
        }, 1000);
    }

    // Start live updates every 30 seconds
    setInterval(updateLiveStats, 30000);
    createSystemPerformanceChart();

    // System Performance Chart
    function createSystemPerformanceChart() {
        const ctx = document.getElementById('systemPerformanceChart');
        if (!ctx) return;

        const gradient1 = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient1.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        gradient1.addColorStop(1, 'rgba(5, 150, 105, 0.8)');

        const gradient2 = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient2.addColorStop(0, 'rgba(245, 158, 11, 0.8)');
        gradient2.addColorStop(1, 'rgba(217, 119, 6, 0.8)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                datasets: [{
                    label: 'CPU Kullanımı (%)',
                    data: [45, 32, 67, 89, 43, 56],
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: gradient1,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(16, 185, 129, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'RAM Kullanımı (%)',
                    data: [78, 65, 82, 95, 71, 83],
                    borderColor: 'rgba(245, 158, 11, 1)',
                    backgroundColor: gradient2,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                    pointBorderColor: 'rgba(245, 158, 11, 1)',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: 'var(--text-primary)',
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                        titleColor: 'var(--text-primary)',
                        bodyColor: 'var(--text-secondary)',
                        borderColor: 'rgba(102, 126, 234, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '% ' + context.dataset.label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            color: 'var(--text-secondary)',
                            font: {
                                size: 11,
                                weight: '500'
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            borderColor: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            color: 'var(--text-secondary)',
                            font: {
                                size: 11,
                                weight: '500'
                            }
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Activity Feed Auto Scroll
    const activityFeed = document.querySelector('.activity-feed');
    if (activityFeed) {
        let scrollInterval = setInterval(() => {
            if (activityFeed.scrollTop + activityFeed.clientHeight >= activityFeed.scrollHeight) {
                activityFeed.scrollTop = 0;
            } else {
                activityFeed.scrollTop += 1;
            }
        }, 50);

        activityFeed.addEventListener('mouseenter', () => {
            clearInterval(scrollInterval);
        });

        activityFeed.addEventListener('mouseleave', () => {
            scrollInterval = setInterval(() => {
                if (activityFeed.scrollTop + activityFeed.clientHeight >= activityFeed.scrollHeight) {
                    activityFeed.scrollTop = 0;
                } else {
                    activityFeed.scrollTop += 1;
                }
            }, 50);
        });
    }

    // System Health Pulse Animation
    const healthIndicators = document.querySelectorAll('.health-indicator');
    healthIndicators.forEach((indicator, index) => {
        setTimeout(() => {
            indicator.classList.add('pulse-glow');
        }, index * 200);
    });

    // Enhanced Button Interactions
    document.querySelectorAll('.btn-custom').forEach(btn => {
        btn.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(-1px) scale(0.98)';
        });

        btn.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Parallax Background Effect
    let parallaxElements = document.querySelectorAll('.admin-dashboard::before');
    if (parallaxElements.length > 0) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            parallaxElements.forEach(el => {
                el.style.transform = `translateY(${rate}px)`;
            });
        });
    }

    // Dynamic Time Update
    const updateTime = () => {
        const now = new Date();
        const timeString = now.toLocaleTimeString('tr-TR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });

        const timeElements = document.querySelectorAll('.current-time');
        timeElements.forEach(el => {
            el.textContent = timeString;
        });
    };

    updateTime();
    setInterval(updateTime, 1000);

    // Enhanced Statistics Counter Animation
    const animateCounters = () => {
        document.querySelectorAll('.stat-value-modern').forEach(counter => {
            const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
            if (isNaN(target)) return;

            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current).toLocaleString();
            }, 20);
        });
    };

    // Trigger counter animation when stats are visible
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                statsObserver.unobserve(entry.target);
            }
        });
    });

    document.querySelectorAll('.stat-card-modern').forEach(card => {
        statsObserver.observe(card);
    });

    // Keyboard Navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Close any open modals or menus
            const activeElements = document.querySelectorAll('.active, .open');
            activeElements.forEach(el => {
                el.classList.remove('active', 'open');
            });
        }
    });

    // Touch/Swipe Support for Mobile
    let touchStartX = 0;
    let touchEndX = 0;

    document.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });

    document.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    const handleSwipe = () => {
        const swipeThreshold = 50;
        if (touchEndX < touchStartX - swipeThreshold) {
            // Swipe left - next section
            smoothScrollToNext();
        }
        if (touchEndX > touchStartX + swipeThreshold) {
            // Swipe right - previous section
            smoothScrollToPrevious();
        }
    };

    const smoothScrollToNext = () => {
        const sections = document.querySelectorAll('.fade-in');
        const currentScroll = window.pageYOffset;
        for (let section of sections) {
            const sectionTop = section.offsetTop;
            if (sectionTop > currentScroll + 100) {
                smoothScroll(section);
                break;
            }
        }
    };

    const smoothScrollToPrevious = () => {
        const sections = Array.from(document.querySelectorAll('.fade-in')).reverse();
        const currentScroll = window.pageYOffset;
        for (let section of sections) {
            const sectionTop = section.offsetTop;
            if (sectionTop < currentScroll - 100) {
                smoothScroll(section);
                break;
            }
        }
    };

    // Performance Optimization - Debounce scroll events
    const debounce = (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };

    // Enhanced scroll performance
    const handleScroll = debounce(() => {
        // Add scroll-based effects here if needed
    }, 16);

    window.addEventListener('scroll', handleScroll);

    // Accessibility Improvements
    document.querySelectorAll('.quick-action-card').forEach(card => {
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-label', card.querySelector('.action-title').textContent);
    });

    // Focus management
    document.querySelectorAll('.quick-action-card').forEach(card => {
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                card.click();
            }
        });
    });

    console.log('Ultra Modern Admin Panel JavaScript loaded successfully!');
});