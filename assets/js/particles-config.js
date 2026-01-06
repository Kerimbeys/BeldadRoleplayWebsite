/**
 * MTA:SA UCP - Particles.js Yapılandırması
 * Arka plan particles efekti
 */

// Particles.js'in yüklendiğinden emin ol
function initParticles() {
    if (typeof particlesJS === 'undefined') {
        console.warn('Particles.js yüklenemedi!');
        return;
    }
    
    // Particles.js yapılandırması
    particlesJS('particles-js', {
        particles: {
            number: {
                value: 80, // Particle sayısı - Daha fazla için artırın (100-150), daha az için azaltın (50-60)
                density: {
                    enable: true,
                    value_area: 800
                }
            },
            color: {
                value: '#00d4ff' // Ana renk - Burayı değiştirerek particles rengini ayarlayabilirsiniz
            },
            shape: {
                type: 'circle',
                stroke: {
                    width: 0,
                    color: '#000000'
                },
                polygon: {
                    nb_sides: 5
                }
            },
            opacity: {
                value: 0.5,
                random: false,
                anim: {
                    enable: false,
                    speed: 1,
                    opacity_min: 0.1,
                    sync: false
                }
            },
            size: {
                value: 3, // Particle boyutu (1-5 arası önerilir)
                random: true,
                anim: {
                    enable: false,
                    speed: 40,
                    size_min: 0.1,
                    sync: false
                }
            },
            line_linked: {
                enable: true, // Çizgileri açmak/kapatmak için true/false
                distance: 150, // Çizgi mesafesi
                color: '#00d4ff', // Çizgi rengi
                opacity: 0.4, // Çizgi opaklığı (0-1 arası)
                width: 1 // Çizgi kalınlığı
            },
            move: {
                enable: true,
                speed: 2, // Hareket hızı (1-5 arası önerilir)
                direction: 'none', // 'none', 'top', 'bottom', 'left', 'right'
                random: false,
                straight: false,
                out_mode: 'out',
                bounce: false,
                attract: {
                    enable: false, // Çekim efekti için true yapın
                    rotateX: 600,
                    rotateY: 1200
                }
            }
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: {
                    enable: true, // Mouse üzerine gelince efekt
                    mode: 'repulse' // 'repulse', 'grab', 'bubble'
                },
                onclick: {
                    enable: true, // Tıklayınca efekt
                    mode: 'push' // 'push', 'remove', 'bubble', 'repulse'
                },
                resize: true
            },
            modes: {
                grab: {
                    distance: 400,
                    line_linked: {
                        opacity: 1
                    }
                },
                bubble: {
                    distance: 400,
                    size: 40,
                    duration: 2,
                    opacity: 8,
                    speed: 3
                },
                repulse: {
                    distance: 200,
                    duration: 0.4
                },
                push: {
                    particles_nb: 4
                },
                remove: {
                    particles_nb: 2
                }
            }
        },
        retina_detect: true
    });
}

// Sayfa yüklendiğinde particles'ı başlat
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // Particles.js yüklenmesini bekle
        setTimeout(initParticles, 100);
    });
} else {
    // Sayfa zaten yüklü
    setTimeout(initParticles, 100);
}
