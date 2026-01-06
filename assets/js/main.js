/**
 * MTA:SA UCP - Main JavaScript
 */

// Sayfa yüklendiğinde animasyonlar
document.addEventListener('DOMContentLoaded', function() {
    // Fade in animasyonları
    const cards = document.querySelectorAll('.card-custom, .stat-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });

    // Dropdown menüleri için mobil destek
    initDropdownMenus();
});

// Dropdown menüleri için mobil destek
function initDropdownMenus() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    dropdownToggles.forEach(toggle => {
        // Sadece mobil için kendi davranışımızı kullan, desktop için Bootstrap'e bırak
        toggle.addEventListener('click', function(e) {
            // Desktop için (geniş ekran) tamamen Bootstrap'in çalışmasına izin ver
            if (window.innerWidth > 991.98) {
                return true; // Bootstrap'in çalışmasına izin ver
            }

            // Mobil için kendi davranışımızı kullan
            e.preventDefault();
            e.stopPropagation();

            const dropdown = this.parentElement;
            const menu = dropdown.querySelector('.dropdown-menu');

            // Diğer açık dropdown'ları kapat
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                if (openMenu !== menu) {
                    openMenu.classList.remove('show');
                    const otherToggle = openMenu.parentElement.querySelector('.dropdown-toggle');
                    if (otherToggle) {
                        otherToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Bu dropdown'u aç/kapat
            const isCurrentlyOpen = menu.classList.contains('show');
            menu.classList.toggle('show');

            // Aria-expanded attribute'unu güncelle
            this.setAttribute('aria-expanded', !isCurrentlyOpen);
        });

        // Mobil cihazlarda dokunma olayını da ekle
        toggle.addEventListener('touchstart', function(e) {
            if (window.innerWidth > 991.98) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            const dropdown = this.parentElement;
            const menu = dropdown.querySelector('.dropdown-menu');

            // Diğer açık dropdown'ları kapat
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                if (openMenu !== menu) {
                    openMenu.classList.remove('show');
                    const otherToggle = openMenu.parentElement.querySelector('.dropdown-toggle');
                    if (otherToggle) {
                        otherToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            });

            // Bu dropdown'u aç/kapat
            const isCurrentlyOpen = menu.classList.contains('show');
            menu.classList.toggle('show');

            this.setAttribute('aria-expanded', !isCurrentlyOpen);
        });
    });

    // Dropdown dışına tıklandığında kapat (sadece mobil)
    document.addEventListener('click', function(e) {
        if (window.innerWidth > 991.98) {
            return;
        }

        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });

            document.querySelectorAll('.dropdown-toggle[aria-expanded="true"]').forEach(toggle => {
                toggle.setAttribute('aria-expanded', 'false');
            });
        }
    });

    // Navbar toggler'a tıklandığında dropdown'ları kapat
    const navbarToggler = document.querySelector('.navbar-toggler');
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            // Navbar collapse açıldığında dropdown'ları kapat
            setTimeout(() => {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });

                document.querySelectorAll('.dropdown-toggle[aria-expanded="true"]').forEach(toggle => {
                    toggle.setAttribute('aria-expanded', 'false');
                });
            }, 100);
        });
    }

    // Navbar collapse kapanırken de dropdown'ları kapat
    const navbarCollapse = document.querySelector('.navbar-collapse');
    if (navbarCollapse) {
        // Bootstrap collapse event'lerini dinle
        navbarCollapse.addEventListener('hide.bs.collapse', function() {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });

            document.querySelectorAll('.dropdown-toggle[aria-expanded="true"]').forEach(toggle => {
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // ESC tuşu ile dropdown'ları kapat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });

            document.querySelectorAll('.dropdown-toggle[aria-expanded="true"]').forEach(toggle => {
                toggle.setAttribute('aria-expanded', 'false');
            });
        }
    });
}

// Form validasyonu
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Para formatı (güncellendi)
function formatMoney(amount, currency = '$') {
    return currency + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Tarih formatı
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('tr-TR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Alert otomatik kapanma
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Tooltip başlatma (Bootstrap)
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Sayı Animasyonu (Counter)
function animateCounter(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const current = Math.floor(progress * (end - start) + start);
        element.textContent = current.toLocaleString('tr-TR');
        if (progress < 1) {
            window.requestAnimationFrame(step);
        } else {
            element.textContent = end.toLocaleString('tr-TR');
        }
    };
    window.requestAnimationFrame(step);
}

// Sayfa yüklendiğinde sayıları animasyonlu göster
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-value, .counter');
    counters.forEach(counter => {
        const text = counter.textContent.replace(/[^0-9]/g, '');
        if (text && !isNaN(text)) {
            const finalValue = parseInt(text);
            counter.textContent = '0';
            setTimeout(() => {
                animateCounter(counter, 0, finalValue, 2000);
            }, 500);
        }
    });
});

// Toast Bildirimi
function showToast(message, type = 'info') {
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast-custom ${type}`;
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi ${getToastIcon(type)} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Otomatik kapanma
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}

function getToastIcon(type) {
    const icons = {
        'success': 'bi-check-circle-fill',
        'error': 'bi-x-circle-fill',
        'warning': 'bi-exclamation-triangle-fill',
        'info': 'bi-info-circle-fill'
    };
    return icons[type] || icons.info;
}

// CSS'e slideOutRight animasyonu ekle
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Para formatı iyileştirme
function formatMoney(amount, currency = '$') {
    return currency + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Loading Spinner
function showLoading(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (element) {
        element.innerHTML = '<div class="loading-spinner"></div>';
    }
}

function hideLoading(element, content) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (element) {
        element.innerHTML = content || '';
    }
}

// Smooth Scroll
function smoothScrollTo(element) {
    if (typeof element === 'string') {
        element = document.querySelector(element);
    }
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Parallax Effect (Basit)
document.addEventListener('DOMContentLoaded', function() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        parallaxElements.forEach(element => {
            const speed = element.dataset.speed || 0.5;
            element.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
});

// Form Submit Loading
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Yükleniyor...';
                
                // Eğer form geçersizse butonu geri yükle
                setTimeout(() => {
                    if (!form.checkValidity()) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 100);
            }
        });
    });
});

// Intersection Observer ile görünürlük animasyonları
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.fade-in, .card-custom, .stat-card');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});

