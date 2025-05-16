// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize language
  loadLanguage();

  // Initialize animations and effects
  initLoader();
  initNavbar();
  initScrollAnimations();
  initBackToTop();
  initLottieAnimations();
  initParallaxEffects();
  initContactForm();

  // Initialize language buttons
  document.querySelectorAll('.language-btn').forEach(button => {
    button.addEventListener('click', function() {
      const language = this.getAttribute('data-language');
      changeLanguage(language);
    });
  });
});

// Loader animation
function initLoader() {
  const loader = document.querySelector('.loader-container');

  // Hide loader after 2 seconds
  setTimeout(() => {
    loader.classList.add('hidden');
    // Remove loader from DOM after animation completes
    setTimeout(() => {
      loader.style.display = 'none';
    }, 500);
  }, 2000);
}

// Navbar scroll effect
function initNavbar() {
  const navbar = document.querySelector('.navbar');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Active link highlighting
  const navLinks = document.querySelectorAll('.nav-link');
  const sections = document.querySelectorAll('section');

  window.addEventListener('scroll', () => {
    let current = '';

    sections.forEach(section => {
      const sectionTop = section.offsetTop;
      const sectionHeight = section.clientHeight;

      if (window.scrollY >= (sectionTop - 200)) {
        current = section.getAttribute('id');
      }
    });

    navLinks.forEach(link => {
      link.classList.remove('active');
      if (link.getAttribute('href') === `#${current}`) {
        link.classList.add('active');
      }
    });
  });
}

// Scroll animations
function initScrollAnimations() {
  // Initialize ScrollReveal if available
  if (typeof ScrollReveal !== 'undefined') {
    // Common reveal configuration
    const defaultRevealConfig = {
      distance: '50px',
      duration: 800,
      easing: 'ease-in-out',
      interval: 100,
      reset: false,
      viewFactor: 0.2
    };

    // Initialize ScrollReveal
    const sr = ScrollReveal();

    // Reveal left elements
    sr.reveal('.reveal-left', {
      ...defaultRevealConfig,
      origin: 'left'
    });

    // Reveal right elements
    sr.reveal('.reveal-right', {
      ...defaultRevealConfig,
      origin: 'right'
    });

    // Reveal up elements
    sr.reveal('.reveal-up', {
      ...defaultRevealConfig,
      origin: 'bottom',
      interval: 100
    });

    // Reveal list items with delay
    sr.reveal('.reveal-item', {
      ...defaultRevealConfig,
      origin: 'left',
      interval: 200
    });

    // Reveal feature cards with delay based on data-delay attribute
    document.querySelectorAll('.feature-card').forEach(card => {
      const delay = card.getAttribute('data-delay') || 0;
      sr.reveal(card, {
        ...defaultRevealConfig,
        origin: 'bottom',
        delay: parseInt(delay)
      });
    });
  } else {
    // Fallback for scroll animations if ScrollReveal is not available
    const revealElements = document.querySelectorAll('.reveal-left, .reveal-right, .reveal-up, .reveal-item');

    function checkScroll() {
      revealElements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (elementTop < windowHeight - 100) {
          element.classList.add('visible');
        }
      });
    }

    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Check on initial load
  }
}

// Back to top button
function initBackToTop() {
  const backToTopButton = document.querySelector('.back-to-top');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
      backToTopButton.classList.add('active');
    } else {
      backToTopButton.classList.remove('active');
    }
  });

  backToTopButton.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// Initialize Lottie animations for all sections
function initLottieAnimations() {
  console.log('Initializing Lottie animations...');

  // Check if Lottie is available
  if (typeof lottie === 'undefined') {
    console.error('Lottie library is not loaded!');
    return;
  }

  console.log('Lottie library is loaded successfully.');

  // Common animation settings
  const commonSettings = {
    renderer: 'svg',
    loop: true,
    autoplay: true,
    rendererSettings: {
      preserveAspectRatio: 'xMidYMid slice',
      progressiveLoad: true,
      clearCanvas: false
    }
  };

  // Function to add interactivity to animation containers
  function addInteractivity(container, animation) {
    if (!container) return;

    console.log('Adding interactivity to container:', container.id);

    // Add hover effect - pause on hover, resume on mouse out
    container.addEventListener('mouseenter', () => {
      animation.pause();
    });

    container.addEventListener('mouseleave', () => {
      animation.play();
    });

    // Add parallax effect on mouse move
    container.addEventListener('mousemove', (e) => {
      const rect = container.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;

      container.style.transform = `perspective(1000px) rotateY(${x * 5}deg) rotateX(${-y * 5}deg)`;
    });

    container.addEventListener('mouseleave', () => {
      container.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
    });
  }

  // Load hero animation
  const heroContainer = document.getElementById('hero-animation');
  if (heroContainer) {
    console.log('Loading hero animation');
    try {
      const heroAnimation = lottie.loadAnimation({
        container: heroContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'animations/herosecanim.json'
      });

      heroAnimation.addEventListener('error', (error) => {
        console.error('Error loading hero animation:', error);
      });

      heroAnimation.addEventListener('data_ready', () => {
        console.log('Hero animation data ready');
      });

      addInteractivity(heroContainer, heroAnimation);
    } catch (error) {
      console.error('Error creating hero animation:', error);
    }
  } else {
    console.error('Hero animation container not found');
  }

  // Load features animation
  const featuresContainer = document.getElementById('features-animation');
  if (featuresContainer) {
    console.log('Loading features animation');
    try {
      const featuresAnimation = lottie.loadAnimation({
        container: featuresContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'animations/futuresecanim.json'
      });

      featuresAnimation.addEventListener('error', (error) => {
        console.error('Error loading features animation:', error);
      });

      featuresAnimation.addEventListener('data_ready', () => {
        console.log('Features animation data ready');
      });

      addInteractivity(featuresContainer, featuresAnimation);
    } catch (error) {
      console.error('Error creating features animation:', error);
    }
  } else {
    console.error('Features animation container not found');
  }

  // Load showcase animation
  const showcaseContainer = document.getElementById('showcase-animation');
  if (showcaseContainer) {
    console.log('Loading showcase animation');
    try {
      const showcaseAnimation = lottie.loadAnimation({
        container: showcaseContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'animations/herosecanim.json'
      });

      showcaseAnimation.addEventListener('error', (error) => {
        console.error('Error loading showcase animation:', error);
      });

      showcaseAnimation.addEventListener('data_ready', () => {
        console.log('Showcase animation data ready');
      });

      addInteractivity(showcaseContainer, showcaseAnimation);
    } catch (error) {
      console.error('Error creating showcase animation:', error);
    }
  } else {
    console.error('Showcase animation container not found');
  }

  // Load green field animation
  const greenFieldContainer = document.getElementById('green-field-animation');
  if (greenFieldContainer) {
    console.log('Loading green field animation');
    try {
      const greenFieldAnimation = lottie.loadAnimation({
        container: greenFieldContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'animations/futuresecanim.json'
      });

      greenFieldAnimation.addEventListener('error', (error) => {
        console.error('Error loading green field animation:', error);
      });

      greenFieldAnimation.addEventListener('data_ready', () => {
        console.log('Green field animation data ready');
      });

      addInteractivity(greenFieldContainer, greenFieldAnimation);
    } catch (error) {
      console.error('Error creating green field animation:', error);
    }
  } else {
    console.error('Green field animation container not found');
  }

  // Load about animation
  const aboutContainer = document.getElementById('about-animation');
  if (aboutContainer) {
    console.log('Loading about animation');
    try {
      const aboutAnimation = lottie.loadAnimation({
        container: aboutContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'animations/herosecanim.json'
      });

      aboutAnimation.addEventListener('error', (error) => {
        console.error('Error loading about animation:', error);
      });

      aboutAnimation.addEventListener('data_ready', () => {
        console.log('About animation data ready');
      });

      addInteractivity(aboutContainer, aboutAnimation);
    } catch (error) {
      console.error('Error creating about animation:', error);
    }
  } else {
    console.error('About animation container not found');
  }

  // Load contact animation
  const contactContainer = document.getElementById('contact-animation');
  if (contactContainer) {
    console.log('Loading contact animation');
    try {
      const contactAnimation = lottie.loadAnimation({
        container: contactContainer,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: 'animations/futuresecanim.json'
      });

      contactAnimation.addEventListener('error', (error) => {
        console.error('Error loading contact animation:', error);
      });

      contactAnimation.addEventListener('data_ready', () => {
        console.log('Contact animation data ready');
      });

      addInteractivity(contactContainer, contactAnimation);
    } catch (error) {
      console.error('Error creating contact animation:', error);
    }
  } else {
    console.error('Contact animation container not found');
  }
}

// Parallax effects
function initParallaxEffects() {
  // Check if GSAP and ScrollTrigger are available
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    // Parallax for hero section
    gsap.to('#hero-animation', {
      y: 100,
      scrollTrigger: {
        trigger: '.hero-section',
        start: 'top top',
        end: 'bottom top',
        scrub: true
      }
    });

    // Animate features section
    gsap.to('#features-animation', {
      scale: 1.1,
      scrollTrigger: {
        trigger: '#features',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      }
    });

    // Animate showcase section
    gsap.to('#showcase-animation', {
      rotation: 5,
      scrollTrigger: {
        trigger: '#showcase',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      }
    });

    // Animate green field section
    gsap.to('#green-field-animation', {
      y: 50,
      scrollTrigger: {
        trigger: '#green-field',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      }
    });

    // Animate about section
    gsap.to('#about-animation', {
      scale: 1.1,
      rotation: -5,
      scrollTrigger: {
        trigger: '#about',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      }
    });

    // Animate contact section
    gsap.to('#contact-animation', {
      y: 30,
      scrollTrigger: {
        trigger: '#contact',
        start: 'top bottom',
        end: 'bottom top',
        scrub: true
      }
    });

    // Animate section titles
    gsap.utils.toArray('.section-title, .showcase-title, .green-field-title, .about-title, .contact-title').forEach(title => {
      gsap.from(title, {
        y: 50,
        opacity: 0,
        duration: 0.8,
        scrollTrigger: {
          trigger: title,
          start: 'top 80%',
          toggleActions: 'play none none none'
        }
      });
    });
  } else {
    // Fallback for parallax effects
    window.addEventListener('scroll', () => {
      const scrollY = window.scrollY;

      // Simple parallax for animation containers
      const animationContainers = document.querySelectorAll('.animation-container');
      animationContainers.forEach(container => {
        const section = container.closest('section');
        if (section) {
          const sectionTop = section.offsetTop;
          const sectionHeight = section.offsetHeight;
          const windowHeight = window.innerHeight;

          if (scrollY + windowHeight > sectionTop && scrollY < sectionTop + sectionHeight) {
            const scrollPercentage = (scrollY + windowHeight - sectionTop) / (sectionHeight + windowHeight);
            container.style.transform = `translateY(${scrollPercentage * 30}px)`;
          }
        }
      });
    });
  }
}

// Contact form handling - simplified since we removed the form
function initContactForm() {
  // No form to handle in the simplified design
  return;
}
