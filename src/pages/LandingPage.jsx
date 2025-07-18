import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { FaUserClock, FaChartLine, FaMobileAlt, FaShieldAlt, FaArrowRight } from 'react-icons/fa';
import { Carousel } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './LandingPage.css'; // Create this CSS file for custom styles

export default function LandingPage() {
  const navigate = useNavigate();
  const [isVisible, setIsVisible] = useState(false);

  useEffect(() => {
    // Trigger animations after component mounts
    setIsVisible(true);
    
    // Auto-scroll testimonials
    const interval = setInterval(() => {
      setActiveIndex((prevIndex) => (prevIndex + 1) % testimonials.length);
    }, 5000);
    
    return () => clearInterval(interval);
  }, []);

  const [activeIndex, setActiveIndex] = useState(0);

  const features = [
    {
      icon: <FaUserClock size={40} />,
      title: "Real-time Tracking",
      description: "Monitor attendance as it happens with our live dashboard"
    },
    {
      icon: <FaChartLine size={40} />,
      title: "Detailed Reports",
      description: "Generate comprehensive reports with just one click"
    },
    {
      icon: <FaMobileAlt size={40} />,
      title: "Mobile Friendly",
      description: "Access the system from any device, anywhere"
    },
    {
      icon: <FaShieldAlt size={40} />,
      title: "Secure Data",
      description: "Enterprise-grade security for your sensitive information"
    }
  ];

  const testimonials = [
    {
      quote: "This system reduced our admin time by 70%!",
      author: "Sarah Johnson, HR Manager"
    },
    {
      quote: "The easiest attendance system we've ever used.",
      author: "Michael Chen, School Principal"
    },
    {
      quote: "Implementation was seamless and support is excellent.",
      author: "David Wilson, IT Director"
    }
  ];

  const images = [
    "https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80",
    "https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80",
    "https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"
  ];

  return (
    <div className={`landing-page ${isVisible ? 'visible' : ''}`}>
      {/* Hero Section */}
      <section className="hero-section">
        <Carousel controls={false} indicators={false} pause={false} interval={5000}>
          {images.map((img, index) => (
            <Carousel.Item key={index}>
              <div 
                className="hero-image" 
                style={{ backgroundImage: `url(${img})` }}
              >
                <div className="hero-overlay"></div>
              </div>
            </Carousel.Item>
          ))}
        </Carousel>
        
        <div className="hero-content">
          <h1 className="hero-title animate-pop">The Uniform Hub</h1>
          <p className="hero-subtitle animate-fade">Modern Attendance Marking System</p>
          <button 
            onClick={() => navigate('/login')} 
            className="cta-button animate-bounce"
          >
            Get Started <FaArrowRight className="button-icon" />
          </button>
        </div>
      </section>

      {/* Features Section */}
      <section className="features-section">
        <h2 className="section-title">Why Choose Our System</h2>
        <div className="features-grid">
          {features.map((feature, index) => (
            <div 
              key={index} 
              className="feature-card"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <div className="feature-icon" style={{ color: `hsl(${index * 90}, 70%, 50%)` }}>
                {feature.icon}
              </div>
              <h3>{feature.title}</h3>
              <p>{feature.description}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Testimonials Section */}
      <section className="testimonials-section">
        <h2 className="section-title">What Our Clients Say</h2>
        <div className="testimonial-container">
          <div 
            className="testimonial-slider"
            style={{ transform: `translateX(-${activeIndex * 100}%)` }}
          >
            {testimonials.map((testimonial, index) => (
              <div key={index} className="testimonial-slide">
                <blockquote className="testimonial-quote">"{testimonial.quote}"</blockquote>
                <p className="testimonial-author">— {testimonial.author}</p>
              </div>
            ))}
          </div>
          <div className="testimonial-dots">
            {testimonials.map((_, index) => (
              <button
                key={index}
                className={`dot ${index === activeIndex ? 'active' : ''}`}
                onClick={() => setActiveIndex(index)}
              />
            ))}
          </div>
        </div>
      </section>

      {/* add footer */}
      <footer className="footer">
        <div className="footer-content text-center">
          <p>&copy; 2025 The Uniform Hub. All rights reserved.</p>
          <div className="social-icons">
            <a href="#" aria-label="Facebook"><i className="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="Twitter"><i className="fab fa-twitter"></i></a>
            <a href="#" aria-label="LinkedIn"><i className="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </footer>
    </div>
  );
}