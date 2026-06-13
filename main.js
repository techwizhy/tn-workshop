/**
 * Tamil Nadu Youth Political Workshop (TNYPW) Portal
 * Main Javascript Logic for Interactive Features & Accessibility
 *
 * Implements:
 * 1. Font Size Adjustments (A-, A, A+) with LocalStorage persistence
 * 2. Dark/Light Theme Toggling with LocalStorage persistence
 * 3. Mobile Navigation Menu Toggle with ARIA support
 * 4. Scroll Spy for navigation links highlighting
 * 5. Animated Stats Counter (Intersection Observer + prefers-reduced-motion check)
 * 6. Dynamic News & Updates Fetching from content.json
 * 7. Gallery Category Filtering with yw-hidden toggles
 * 8. Image Lightbox modal view with keyboard & click-outside close
 * 9. Donation Presets Selection & Payment Gateways Tab Routing
 * 10. AJAX Form Submissions for Registration, Contacts, & Donations (Astra Compatible)
 * 11. Social Page Sharing via Web Share API or Secure Clipboard Fallback
 */

(function() {
  'use strict';

  // Global Configuration & Localized Fallbacks
  const getAjaxSettings = () => {
    return {
      url: window.ywAjax?.ajaxurl || window.YWConfig?.ajaxUrl || '',
      nonce: window.ywAjax?.nonce || window.YWConfig?.nonce || ''
    };
  };

  // ==========================================================================
  // 1. Accessibility Features: Font Size Adjuster
  // ==========================================================================
  const fontDecBtns = document.querySelectorAll('#yw-font-dec, #yw-font-decrease');
  const fontIncBtns = document.querySelectorAll('#yw-font-inc, #yw-font-increase');
  const fontResetBtns = document.querySelectorAll('#yw-font-reset');

  const setFontSize = (size) => {
    if (['sm', 'md', 'lg'].includes(size)) {
      document.documentElement.setAttribute('data-font', size);
      localStorage.setItem('yw-font-size', size);
    }
  };

  fontDecBtns.forEach(btn => btn.addEventListener('click', () => setFontSize('sm')));
  fontIncBtns.forEach(btn => btn.addEventListener('click', () => setFontSize('lg')));
  fontResetBtns.forEach(btn => btn.addEventListener('click', () => setFontSize('md')));

  // Initialize saved font size
  const savedFont = localStorage.getItem('yw-font-size');
  if (savedFont) {
    setFontSize(savedFont);
  }

  // ==========================================================================
  // 2. Accessibility Features: Theme Mode Toggler
  // ==========================================================================
  const themeToggleBtns = document.querySelectorAll('#yw-theme-toggle');

  const setTheme = (theme) => {
    if (['light', 'dark'].includes(theme)) {
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('yw-theme', theme);
      
      themeToggleBtns.forEach(btn => {
        const darkIcon = btn.querySelector('.yw-theme-icon-dark, .yw-moon-icon');
        const lightIcon = btn.querySelector('.yw-theme-icon-light, .yw-sun-icon');
        
        if (theme === 'dark') {
          btn.setAttribute('aria-label', 'Toggle light mode');
          if (darkIcon) darkIcon.classList.add('yw-hidden');
          if (lightIcon) lightIcon.classList.remove('yw-hidden');
        } else {
          btn.setAttribute('aria-label', 'Toggle dark mode');
          if (darkIcon) darkIcon.classList.remove('yw-hidden');
          if (lightIcon) lightIcon.classList.add('yw-hidden');
        }
      });
    }
  };

  themeToggleBtns.forEach(btn => btn.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
  }));

  // Initialize saved theme
  const savedTheme = localStorage.getItem('yw-theme');
  if (savedTheme) {
    setTheme(savedTheme);
  } else {
    // Media query system preference fallback
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    setTheme(systemPrefersDark ? 'dark' : 'light');
  }

  // ==========================================================================
  // 3. Navigation Controls (Mobile Toggle & Scroll Spy)
  // ==========================================================================
  const navToggle = document.getElementById('yw-nav-toggle');
  const navMenu = document.getElementById('yw-nav-menu');

  if (navToggle && navMenu) {
    navToggle.addEventListener('click', () => {
      const isOpen = navMenu.classList.toggle('yw-open');
      navToggle.setAttribute('aria-expanded', isOpen);
    });

    // Close mobile menu on clicking any navigation link
    const navLinks = navMenu.querySelectorAll('.yw-nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('yw-open');
        navToggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // Active Link Highlight matching current pathname
  const currentPath = window.location.pathname;
  const pageNavLinks = document.querySelectorAll('.yw-nav-link');
  pageNavLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href) {
      if (currentPath.endsWith(href) || (href === 'index.html' && (currentPath === '/' || currentPath.endsWith('/')))) {
        link.classList.add('yw-active');
      } else {
        link.classList.remove('yw-active');
      }
    }
  });

  // Scroll Spy for On-page Hash Anchor sections
  const sections = document.querySelectorAll('section[id]');
  const spyLinks = document.querySelectorAll('.yw-nav-link[href^="#"]');

  if (sections.length > 0 && spyLinks.length > 0) {
    window.addEventListener('scroll', () => {
      let currentSectionId = '';
      const scrollPosition = window.scrollY + 120; // top offset for headers

      sections.forEach(section => {
        const top = section.offsetTop;
        const height = section.offsetHeight;
        if (scrollPosition >= top && scrollPosition < top + height) {
          currentSectionId = section.getAttribute('id');
        }
      });

      spyLinks.forEach(link => {
        link.classList.remove('yw-active');
        if (link.getAttribute('href') === `#${currentSectionId}`) {
          link.classList.add('yw-active');
        }
      });
    });
  }

  // ==========================================================================
  // 4. Back-to-Top Button
  // ==========================================================================
  const backToTopBtn = document.getElementById('yw-back-to-top');

  if (backToTopBtn) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        backToTopBtn.classList.remove('yw-hidden');
      } else {
        backToTopBtn.classList.add('yw-hidden');
      }
    });

    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  // ==========================================================================
  // 5. Stat Counter Animation
  // ==========================================================================
  const statNumbers = document.querySelectorAll('.yw-stat-number');

  if (statNumbers.length > 0) {
    const animateStats = () => {
      const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      
      statNumbers.forEach(stat => {
        const target = parseInt(stat.getAttribute('data-target'), 10) || 0;
        const text = stat.textContent;
        const suffix = text.replace(/[0-9]/g, ''); // Extract %, + etc.
        
        if (prefersReducedMotion) {
          stat.textContent = target + suffix;
          return;
        }

        let current = 0;
        const duration = 1500; // 1.5 seconds
        const steps = 60;
        const stepValue = Math.ceil(target / steps);
        const stepTime = Math.floor(duration / steps);

        const timer = setInterval(() => {
          current += stepValue;
          if (current >= target) {
            stat.textContent = target + suffix;
            clearInterval(timer);
          } else {
            stat.textContent = current + suffix;
          }
        }, stepTime);
      });
    };

    if ('IntersectionObserver' in window) {
      const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            animateStats();
            statsObserver.unobserve(entry.target);
          }
        });
      }, { threshold: 0.3 });

      const statsSection = document.querySelector('.yw-why-women-stats, .yw-why-women-section, .yw-patriotism-strip');
      if (statsSection) {
        statsObserver.observe(statsSection);
      } else {
        animateStats();
      }
    } else {
      animateStats();
    }
  }

  // ==========================================================================
  // 6. Dynamic Updates / Announcements List
  // ==========================================================================
  const updatesGrid = document.querySelector('.yw-updates-grid');

  if (updatesGrid) {
    fetch('content.json')
      .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
      })
      .then(data => {
        if (data.updates && Array.isArray(data.updates.items) && data.updates.items.length > 0) {
          updatesGrid.innerHTML = ''; // Clear fallback hardcoded ones
          
          data.updates.items.forEach(item => {
            const card = document.createElement('article');
            card.className = 'yw-update-card';
            
            const time = document.createElement('time');
            time.className = 'yw-update-date';
            time.textContent = item.date;
            // Extract numbers/dashes for datetime attribute
            const cleanDate = item.date.replace(/[^0-9]/g, '-') || '2026-06-13';
            time.setAttribute('datetime', cleanDate);
            
            const title = document.createElement('h3');
            title.className = 'yw-update-title';
            title.textContent = item.title;
            
            const summary = document.createElement('p');
            summary.className = 'yw-update-excerpt';
            summary.textContent = item.summary;
            
            card.appendChild(time);
            card.appendChild(title);
            card.appendChild(summary);
            updatesGrid.appendChild(card);
          });
        }
      })
      .catch(() => {
        // Fallback: leave hardcoded HTML items untouched in case of JSON fetch failure
      });
  }

  // ==========================================================================
  // 7. Gallery Category Filtering
  // ==========================================================================
  const filterBtns = document.querySelectorAll('.yw-gallery-filter-btn');
  const galleryItems = document.querySelectorAll('.yw-gallery-item');

  if (filterBtns.length > 0 && galleryItems.length > 0) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('yw-active'));
        btn.classList.add('yw-active');
        
        const filter = btn.getAttribute('data-filter');
        
        galleryItems.forEach(item => {
          const category = item.getAttribute('data-category');
          if (filter === 'all' || category === filter) {
            item.classList.remove('yw-hidden');
          } else {
            item.classList.add('yw-hidden');
          }
        });
      });
    });
  }

  // ==========================================================================
  // 8. Image Lightbox Modal View
  // ==========================================================================
  const lightbox = document.getElementById('yw-lightbox');
  const lightboxImg = document.getElementById('yw-lightbox-image');
  const lightboxCaption = document.getElementById('yw-lightbox-caption');
  const lightboxClose = document.getElementById('yw-lightbox-close');

  if (lightbox && lightboxClose && galleryItems.length > 0) {
    galleryItems.forEach(item => {
      item.addEventListener('click', () => {
        const img = item.querySelector('.yw-gallery-img');
        const caption = item.querySelector('.yw-gallery-item-title');
        
        if (img) {
          lightboxImg.src = img.src;
          lightboxImg.alt = img.alt || '';
        }
        if (caption) {
          lightboxCaption.textContent = caption.textContent;
        }
        
        lightbox.style.display = 'flex';
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden'; // Lock background scrolling
      });
    });

    const closeLightbox = () => {
      lightbox.style.display = 'none';
      lightbox.setAttribute('aria-hidden', 'true');
      lightboxImg.src = '';
      lightboxImg.alt = '';
      lightboxCaption.textContent = '';
      document.body.style.overflow = ''; // Restore background scrolling
    };

    lightboxClose.addEventListener('click', closeLightbox);
    
    // Close on overlay clicking
    lightbox.addEventListener('click', (e) => {
      if (e.target === lightbox || e.target.classList.contains('yw-lightbox-content')) {
        closeLightbox();
      }
    });

    // Close on Escape key press
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && lightbox.style.display === 'flex') {
        closeLightbox();
      }
    });
  }

  // ==========================================================================
  // 9. FAQ Accordion Handling
  // ==========================================================================
  const faqItems = document.querySelectorAll('.yw-faq-item');

  if (faqItems.length > 0) {
    faqItems.forEach(item => {
      item.addEventListener('click', () => {
        // Wait slightly for native attributes to update
        setTimeout(() => {
          if (item.hasAttribute('open')) {
            faqItems.forEach(otherItem => {
              if (otherItem !== item) {
                otherItem.removeAttribute('open');
              }
            });
          }
        }, 30);
      });
    });
  }

  // ==========================================================================
  // 10. Form Response Messages Helper
  // ==========================================================================
  const showFormMessage = (form, message, type) => {
    const container = form.querySelector('.yw-form-response-message, #yw-form-message-container');
    if (container) {
      container.textContent = message;
      container.className = `yw-form-response-message yw-${type}`;
      container.classList.remove('yw-hidden');
    }
  };

  const clearFormMessage = (form) => {
    const container = form.querySelector('.yw-form-response-message, #yw-form-message-container');
    if (container) {
      container.textContent = '';
      container.className = 'yw-form-response-message yw-hidden';
    }
  };

  // ==========================================================================
  // 11. Custom Toast Notifications
  // ==========================================================================
  const showToast = (message) => {
    const toast = document.createElement('div');
    toast.className = 'yw-toast';
    toast.style.position = 'fixed';
    toast.style.bottom = '30px';
    toast.style.right = '30px';
    toast.style.backgroundColor = 'var(--yw-primary, #006591)';
    toast.style.color = '#ffffff';
    toast.style.padding = '12px 24px';
    toast.style.borderRadius = 'var(--yw-radius-md, 8px)';
    toast.style.boxShadow = 'var(--yw-shadow-md)';
    toast.style.zIndex = '9999';
    toast.style.fontWeight = '500';
    toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(15px)';
    toast.textContent = message;

    document.body.appendChild(toast);
    
    // Force reflow
    toast.offsetHeight;
    
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(15px)';
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast);
        }
      }, 300);
    }, 2500);
  };

  // ==========================================================================
  // 12. Social Share Trigger
  // ==========================================================================
  const shareBtns = document.querySelectorAll('#yw-share-btn, #yw-share-btn-inline, .yw-share-btn');

  shareBtns.forEach(btn => {
    btn.addEventListener('click', async () => {
      const shareData = {
        title: document.title,
        text: 'தமிழ்நாடு இளைஞர் அரசியல் பயிலரங்கம் இணையதளத்தைப் பார்வையிடுங்கள்!',
        url: window.location.href
      };

      if (navigator.share) {
        try {
          await navigator.share(shareData);
        } catch (err) {
          // Ignore cancellation
        }
      } else {
        // Fallback: Copy URL to clipboard
        try {
          await navigator.clipboard.writeText(window.location.href);
          showToast('இணைய முகவரி வெற்றிகரமாக நகலெடுக்கப்பட்டது!');
        } catch (err) {
          showFormMessage(document.body, 'இணைப்பைப் பகிர முடியவில்லை.', 'error');
        }
      }
    });
  });

  // ==========================================================================
  // 13. Registration Form Submission
  // ==========================================================================
  const registerForm = document.getElementById('yw-registration-form') || document.querySelector('.yw-reg-form');

  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      clearFormMessage(registerForm);

      const name = document.getElementById('yw-field-name')?.value.trim() || '';
      const email = document.getElementById('yw-field-email')?.value.trim() || '';
      const phone = document.getElementById('yw-field-phone')?.value.trim() || '';
      const interest = document.getElementById('yw-field-interest')?.value || '';
      const message = document.getElementById('yw-field-message')?.value.trim() || '';

      // Frontend Validations
      if (!name || !email || !interest) {
        showFormMessage(registerForm, 'தேவையான விவரங்களை முழுமையாக நிரப்பவும்.', 'error');
        return;
      }

      if (name.length < 2) {
        showFormMessage(registerForm, 'பெயர் குறைந்தபட்சம் 2 எழுத்துக்கள் இருக்க வேண்டும்.', 'error');
        return;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showFormMessage(registerForm, 'மின்னஞ்சல் முகவரி தவறானது.', 'error');
        return;
      }

      if (phone) {
        const cleanPhone = phone.replace(/[\s\-()]/g, '');
        if (!/^[6-9]\d{9}$/.test(cleanPhone) && !/^\+?[1-9]\d{6,14}$/.test(cleanPhone)) {
          showFormMessage(registerForm, 'சரியான தொலைபேசி எண்ணை உள்ளிடவும்.', 'error');
          return;
        }
      }

      const settings = getAjaxSettings();

      if (settings.url) {
        try {
          showFormMessage(registerForm, 'அனுப்பப்படுகிறது...', 'info');
          const response = await fetch(settings.url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              action: 'yw_workshop_register',
              nonce: settings.nonce,
              name: name,
              email: email,
              phone: phone,
              interest: interest,
              message: message
            })
          });

          const result = await response.json();
          if (result.success) {
            showFormMessage(registerForm, 'விண்ணப்பம் வெற்றிகரமாகச் சமர்ப்பிக்கப்பட்டது! உறுதிப்படுத்தல் மின்னஞ்சல் அனுப்பப்பட்டுள்ளது.', 'success');
            registerForm.reset();
          } else {
            showFormMessage(registerForm, result.data?.message || 'சமர்ப்பிப்பதில் பிழை ஏற்பட்டது.', 'error');
          }
        } catch (err) {
          showFormMessage(registerForm, 'சேவையகத்துடன் இணைப்பதில் பிழை ஏற்பட்டது.', 'error');
        }
      } else {
        // Mock standalone success
        showFormMessage(registerForm, 'விண்ணப்பம் வெற்றிகரமாகச் சமர்ப்பிக்கப்பட்டது! (மாதிரி வடிவம் சமர்ப்பிக்கப்பட்டது).', 'success');
        registerForm.reset();
      }
    });
  }

  // ==========================================================================
  // 14. Contact Form Submission
  // ==========================================================================
  const contactForm = document.getElementById('yw-contact-form');

  if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      clearFormMessage(contactForm);

      const name = document.getElementById('yw-contact-name')?.value.trim() || '';
      const email = document.getElementById('yw-contact-email')?.value.trim() || '';
      const phone = document.getElementById('yw-contact-phone')?.value.trim() || '';
      const subject = document.getElementById('yw-contact-subject')?.value.trim() || '';
      const message = document.getElementById('yw-contact-message')?.value.trim() || '';
      const consent = document.getElementById('yw-contact-consent')?.checked || false;

      // validations
      if (!name || !email || !subject || !message || !consent) {
        showFormMessage(contactForm, 'தேவையான விவரங்களை முழுமையாக நிரப்பவும்.', 'error');
        return;
      }

      if (name.length < 2) {
        showFormMessage(contactForm, 'பெயர் குறைந்தபட்சம் 2 எழுத்துக்கள் இருக்க வேண்டும்.', 'error');
        return;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        showFormMessage(contactForm, 'மின்னஞ்சல் முகவரி தவறானது.', 'error');
        return;
      }

      if (message.length < 5) {
        showFormMessage(contactForm, 'செய்தி குறைந்தபட்சம் 5 எழுத்துக்கள் இருக்க வேண்டும்.', 'error');
        return;
      }

      const settings = getAjaxSettings();

      if (settings.url) {
        try {
          showFormMessage(contactForm, 'அனுப்பப்படுகிறது...', 'info');
          const response = await fetch(settings.url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              action: 'yw_contact_submit',
              nonce: settings.nonce,
              name: name,
              email: email,
              phone: phone,
              subject: subject,
              message: message
            })
          });

          const result = await response.json();
          if (result.success) {
            showFormMessage(contactForm, 'உங்கள் செய்தி வெற்றிகரமாக அனுப்பப்பட்டது! விரைவில் உங்களைத் தொடர்புகொள்வோம்.', 'success');
            contactForm.reset();
          } else {
            showFormMessage(contactForm, result.data?.message || 'செய்தி அனுப்புவதில் பிழை ஏற்பட்டது.', 'error');
          }
        } catch (err) {
          showFormMessage(contactForm, 'சேவையகத்துடன் இணைப்பதில் பிழை ஏற்பட்டது.', 'error');
        }
      } else {
        // Mock success
        showFormMessage(contactForm, 'உங்கள் செய்தி வெற்றிகரமாக அனுப்பப்பட்டது! (மாதிரி வடிவம் சமர்ப்பிக்கப்பட்டது).', 'success');
        contactForm.reset();
      }
    });
  }

  // ==========================================================================
  // 15. Newsletter Join Subscription
  // ==========================================================================
  const joinForm = document.getElementById('yw-join-form');
  if (joinForm) {
    joinForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const email = document.getElementById('yw-join-email')?.value.trim() || '';
      if (email) {
        showToast('செய்திமடலுக்கு வெற்றிகரமாகப் பதிவு செய்துள்ளீர்கள்!');
        joinForm.reset();
      }
    });
  }

  // ==========================================================================
  // 16. Donation Presets, Gateway Tabs, and Order Creation
  // ==========================================================================
  const donateForm = document.getElementById('yw-donate-form');
  const donationPresets = document.querySelectorAll('.yw-donation-preset-btn');
  const customAmountInput = document.getElementById('yw-field-amount');
  const gatewayInput = document.getElementById('yw-field-gateway') || document.querySelector('input[name="gateway"]');
  const paymentTabs = document.querySelectorAll('.yw-payment-tab-btn');

  // Preset Selection
  if (donationPresets.length > 0 && customAmountInput) {
    donationPresets.forEach(btn => {
      btn.addEventListener('click', () => {
        const amount = btn.getAttribute('data-amount');
        customAmountInput.value = amount;
        donationPresets.forEach(b => b.classList.remove('yw-active'));
        btn.classList.add('yw-active');
      });
    });

    customAmountInput.addEventListener('input', () => {
      const val = parseFloat(customAmountInput.value);
      donationPresets.forEach(btn => {
        const amt = parseFloat(btn.getAttribute('data-amount'));
        if (amt === val) {
          btn.classList.add('yw-active');
        } else {
          btn.classList.remove('yw-active');
        }
      });
    });
  }

  // Payment Tabs Route Selector
  if (paymentTabs.length > 0 && gatewayInput) {
    paymentTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        paymentTabs.forEach(t => t.classList.remove('yw-active'));
        tab.classList.add('yw-active');
        const gateway = tab.getAttribute('data-gateway') || tab.id.replace('yw-tab-', '');
        gatewayInput.value = gateway;
      });
    });
  }

  // Donation Submission (Razorpay / PayU Integrated)
  if (donateForm) {
    donateForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      clearFormMessage(donateForm);

      const amount = parseFloat(customAmountInput?.value || 0);
      const name = document.getElementById('yw-field-name')?.value.trim() || '';
      const email = document.getElementById('yw-field-email')?.value.trim() || '';
      const phone = document.getElementById('yw-field-phone')?.value.trim() || '';
      const gateway = gatewayInput?.value || 'razorpay';

      if (amount <= 0 || !name || !email) {
        showFormMessage(donateForm, 'தேவையான விவரங்களைச் சரியாக நிரப்பவும்.', 'error');
        return;
      }

      const settings = getAjaxSettings();

      if (settings.url) {
        try {
          showFormMessage(donateForm, 'ஆர்டர் உருவாக்கப்படுகிறது...', 'info');
          const response = await fetch(settings.url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              action: 'yw_create_payment_order',
              nonce: settings.nonce,
              amount: amount,
              gateway: gateway,
              name: name,
              email: email,
              phone: phone
            })
          });

          const result = await response.json();
          if (!result.success) {
            showFormMessage(donateForm, result.data?.message || 'ஆர்டர் உருவாக்குவதில் பிழை ஏற்பட்டது.', 'error');
            return;
          }

          const data = result.data;
          
          if (gateway === 'razorpay') {
            // Initiate Razorpay Checkout Window
            const rzOptions = {
              key: data.key,
              amount: data.amount,
              currency: 'INR',
              name: 'TNYPW',
              description: 'Donation for Civic Leadership Program',
              order_id: data.order_id,
              handler: async function (rzpResponse) {
                try {
                  showFormMessage(donateForm, 'சரிபார்க்கப்படுகிறது...', 'info');
                  const verifyResponse = await fetch(settings.url, {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                      action: 'yw_verify_payment',
                      nonce: settings.nonce,
                      razorpay_payment_id: rzpResponse.razorpay_payment_id,
                      razorpay_order_id: rzpResponse.razorpay_order_id,
                      razorpay_signature: rzpResponse.razorpay_signature
                    })
                  });

                  const verifyResult = await verifyResponse.json();
                  if (verifyResult.success) {
                    showFormMessage(donateForm, 'நன்கொடைக்கு நன்றி! உங்கள் கட்டணம் வெற்றிகரமாகச் செலுத்தப்பட்டது.', 'success');
                    donateForm.reset();
                    if (donationPresets.length > 0) {
                      donationPresets.forEach(b => b.classList.remove('yw-active'));
                    }
                  } else {
                    showFormMessage(donateForm, verifyResult.data?.message || 'கட்டணச் சரிபார்ப்பு தோல்வியடைந்தது.', 'error');
                  }
                } catch (err) {
                  showFormMessage(donateForm, 'சரிபார்ப்பதில் இணைப்பு பிழை ஏற்பட்டது.', 'error');
                }
              },
              prefill: {
                name: name,
                email: email,
                contact: phone
              },
              theme: {
                color: '#000080'
              }
            };
            
            // Check if Razorpay script is loaded
            if (window.Razorpay) {
              const rzp = new window.Razorpay(rzOptions);
              rzp.open();
            } else {
              showFormMessage(donateForm, 'Razorpay SDK ஐ ஏற்ற முடியவில்லை. பக்கத்தை மறுஏற்றம் செய்து முயற்சிக்கவும்.', 'error');
            }
          } else if (gateway === 'payu') {
            // Redirect via dynamic form submit for PayU
            const payuRedirectForm = document.createElement('form');
            payuRedirectForm.method = 'POST';
            payuRedirectForm.action = data.action_url;

            for (const key in data.params) {
              if (Object.prototype.hasOwnProperty.call(data.params, key)) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = data.params[key];
                payuRedirectForm.appendChild(hiddenField);
              }
            }
            document.body.appendChild(payuRedirectForm);
            payuRedirectForm.submit();
          }
        } catch (err) {
          showFormMessage(donateForm, 'ஆர்டர் அமைப்பதில் பிழை ஏற்பட்டது.', 'error');
        }
      } else {
        // Mock frontend success
        showFormMessage(donateForm, 'நன்கொடைக்கு நன்றி! (மாதிரி வடிவம் வெற்றிகரமாக செலுத்தப்பட்டது).', 'success');
        donateForm.reset();
        if (donationPresets.length > 0) {
          donationPresets.forEach(b => b.classList.remove('yw-active'));
        }
      }
    });
  }

  // ==========================================================================
  // 17. Export Public Interface for parent window / developers
  // ==========================================================================
  window.YWPortal = {
    setTheme: setTheme,
    setFontSize: setFontSize,
    showToast: showToast,
    getAjaxSettings: getAjaxSettings
  };

})();
