# Universal Radio Schedule System for WordPress & Elementor

A modern, responsive radio schedule system that works with **ANY** radio station website built with WordPress and Elementor. Features real-time updates, beautiful rounded image design, and seamless WordPress integration.

![Radio Schedule Preview](https://img.shields.io/badge/WordPress-Compatible-blue) ![Elementor Ready](https://img.shields.io/badge/Elementor-Ready-green) ![Mobile Responsive](https://img.shields.io/badge/Mobile-Responsive-orange)

## ✨ Key Features

- **🌍 Universal Compatibility**: Works with any radio station name
- **📱 Mobile Responsive**: Perfect on all devices
- **🔄 Real-time Updates**: Shows current and upcoming programs
- **🎨 Modern Design**: Rounded images, gradient backgrounds, smooth animations
- **⚡ WordPress Integration**: Syncs with WordPress posts and featured images
- **📅 Week Navigation**: Browse schedule for any day
- **🔴 Live Indicators**: Visual indicators for currently broadcasting shows
- **🎯 Elementor Ready**: Easy integration with Elementor page builder

## 🚀 Quick Start

### 1. Install PHP Code
Add the contents of `functions-php-code-only.php` to your theme's `functions.php`:
- WordPress Admin → Appearance → Theme Editor → functions.php
- Scroll to the bottom and paste the code
- Click Update File

### 2. Upload Files
Upload these files to your theme:
```
/wp-content/themes/your-theme/css/universal-radio-schedule.css
/wp-content/themes/your-theme/js/universal-radio-schedule.js
```

### 3. Add to Elementor
- Add HTML widget in Elementor
- Copy contents of `elementor-html-template.html`
- Paste into the HTML widget

### 4. Create Shows
- Go to WordPress Admin → Radio Shows
- Add your first radio show with featured image
- Set schedule times and days

## 📁 File Structure

```
radio-schedule-royal-elementor/
├── functions-php-code-only.php      # WordPress integration (add to functions.php)
├── universal-radio-schedule.css     # Modern styling with rounded images
├── universal-radio-schedule.js      # Interactive functionality
├── elementor-html-template.html     # HTML template for Elementor
├── UNIVERSAL-README.md              # Detailed documentation
├── STAFF-GUIDE.md                   # Guide for radio station staff
└── README.md                        # This file
```

## 🎨 Customization

### Change Brand Colors
Edit CSS variables:
```css
:root {
    --primary-color: #ff6b35;        /* Your brand color */
    --secondary-color: #f7931e;      /* Secondary color */
    --background-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Station-Specific Themes
Pre-built themes available:
- **Blue Theme**: Professional blue colors
- **Green Theme**: Nature/eco-friendly colors  
- **Purple Theme**: Creative/artistic colors

## 📋 Usage

### Basic Shortcode
```
[radio_schedule]
```

### Advanced Options
```
[radio_schedule view="today" max_shows="8" show_header="true"]
```

### Parameters
| Parameter | Default | Description |
|-----------|---------|-------------|
| `view` | `current` | `current`, `today`, or `week` |
| `show_header` | `true` | Show/hide header section |
| `show_upcoming` | `true` | Show/hide upcoming shows |
| `max_shows` | `6` | Maximum shows to display |

## 🎯 Perfect For

- ✅ Community radio stations
- ✅ Commercial radio stations
- ✅ Online radio stations
- ✅ Podcast networks
- ✅ Church radio
- ✅ College radio
- ✅ International stations

## 📱 Mobile Features

- **Swipe Navigation**: Swipe between days
- **Touch Friendly**: Large touch targets
- **Responsive Design**: Adapts to all screen sizes
- **Fast Loading**: Optimized for mobile

## 🛠️ Requirements

- WordPress 5.0+
- Elementor (any version)
- PHP 7.4+
- Modern web browser

## 📖 Documentation

- **UNIVERSAL-README.md**: Complete installation guide
- **STAFF-GUIDE.md**: Guide for radio station staff
- **elementor-html-template.html**: Ready-to-use HTML template

## 🆘 Support

### Common Issues
- **Shows not loading**: Check functions.php installation
- **Images not rounded**: Verify CSS file upload
- **Mobile layout issues**: Clear cache and test

### Getting Help
1. Check browser console for errors
2. Verify all files are uploaded correctly
3. Test with default WordPress theme
4. Check WordPress debug logs

## 🎉 What's New

- ✅ **Universal compatibility** (works with any radio station)
- ✅ **Proper WordPress integration** with posts and images
- ✅ **Rounded image corners** for modern look
- ✅ **Better mobile experience** with swipe navigation
- ✅ **Real-time updates** every 2 minutes
- ✅ **Week navigation** to view any day
- ✅ **Live show detection** with visual indicators
- ✅ **SEO optimization** with proper post structure

## 📄 License

This project is open source and available under the MIT License.

## 🌟 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

**Made with ❤️ for radio stations worldwide**

*Transform your radio station's website with a modern, professional schedule system that works perfectly with WordPress and Elementor.*
