# Universal Radio Schedule System for WordPress

A modern, responsive radio schedule system that works with **ANY** radio station website built with WordPress and Elementor. Features real-time updates, beautiful rounded image design, and seamless WordPress integration.

## ✨ Features

- **Universal Compatibility**: Works with any radio station name
- **WordPress Integration**: Properly syncs with WordPress posts and featured images
- **Modern Design**: Rounded image corners, gradient backgrounds, smooth animations
- **Responsive**: Perfect on desktop, tablet, and mobile devices
- **Real-time Updates**: Shows current and upcoming programs
- **Week Navigation**: Browse schedule for any day of the week
- **Live Indicators**: Visual indicators for currently broadcasting shows
- **Elementor Ready**: Easy integration with Elementor page builder
- **SEO Friendly**: Proper post types and meta data
- **Admin Friendly**: Easy-to-use WordPress admin interface

## 🚀 Quick Start

### Step 1: Install the PHP Code

Add the contents of `universal-radio-schedule.php` to your theme's `functions.php` file:

1. Go to **WordPress Admin → Appearance → Theme Editor**
2. Select `functions.php`
3. Scroll to the **bottom** of the file
4. Copy and paste the entire contents of `universal-radio-schedule.php`
5. Click **Update File**

### Step 2: Upload CSS and JavaScript Files

Upload these files to your theme directory:

```
/wp-content/themes/your-theme/css/universal-radio-schedule.css
/wp-content/themes/your-theme/js/universal-radio-schedule.js
```

### Step 3: Create Your First Radio Show

1. Go to **WordPress Admin → Radio Shows → Add New**
2. Fill in the show details:
   - **Title**: Your show name
   - **Content**: Show description
   - **Featured Image**: Upload a show image (will have rounded corners)
   - **Host/DJ Name**: Enter the host name
   - **Show Days**: Select which days it airs
   - **Start/End Time**: Set the broadcast times
   - **Show Type**: Choose category (Music, Talk, News, etc.)

### Step 4: Display the Schedule

Use the shortcode anywhere in WordPress or Elementor:

```
[radio_schedule]
```

## 📋 Shortcode Options

| Parameter | Default | Description |
|-----------|---------|-------------|
| `view` | `current` | `current`, `today`, or `week` |
| `show_header` | `true` | Show/hide the header section |
| `show_upcoming` | `true` | Show/hide upcoming shows |
| `max_shows` | `6` | Maximum number of shows to display |

### Examples:

```
[radio_schedule view="today" max_shows="8"]
[radio_schedule show_header="false" show_upcoming="false"]
[radio_schedule view="week"]
```

## 🎨 Customization

### Change Colors

Edit the CSS variables in `universal-radio-schedule.css`:

```css
:root {
    --primary-color: #ff6b35;        /* Your brand color */
    --secondary-color: #f7931e;      /* Secondary brand color */
    --accent-color: #667eea;         /* Accent color */
    --background-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

### Pre-built Color Themes

Uncomment one of these in the CSS file:

- **Blue Theme**: Professional blue colors
- **Green Theme**: Nature/eco-friendly colors  
- **Purple Theme**: Creative/artistic colors

### Image Styling

Images automatically have:
- **Rounded corners** (20px border-radius)
- **Hover effects** (scale and rotate)
- **Border styling** with your brand colors
- **Fallback images** for shows without featured images

## 🔧 Advanced Features

### WordPress Integration

- **Custom Post Type**: `radio_shows`
- **Meta Fields**: Automatic scheduling fields
- **Featured Images**: Full support with fallbacks
- **Admin Columns**: Enhanced admin interface
- **SEO Ready**: Proper URLs and meta data

### Real-time Features

- **Auto-refresh**: Updates every 2 minutes
- **Live Detection**: Automatically detects current shows
- **Day Navigation**: Click any day to view schedule
- **Mobile Swipe**: Swipe between days on mobile
- **Keyboard Navigation**: Arrow keys to navigate days

### Mobile Optimization

- **Touch Friendly**: Large touch targets
- **Swipe Navigation**: Swipe between days
- **Responsive Grid**: Adapts to any screen size
- **Fast Loading**: Optimized images and code

## 📱 Mobile Features

- **Swipe left/right** to navigate between days
- **Touch-friendly** buttons and controls
- **Responsive design** adapts to all screen sizes
- **Fast loading** with optimized images

## ⌨️ Keyboard Shortcuts

- **Arrow Left/Right**: Navigate between days
- **Ctrl+R**: Refresh schedule
- **Tab**: Navigate through elements

## 🛠️ Troubleshooting

### Shows Not Appearing?

1. **Check WordPress Posts**: Make sure you've created radio shows in WordPress Admin
2. **Verify Times**: Ensure start/end times are set correctly
3. **Check Days**: Confirm the correct days are selected
4. **Featured Images**: Add featured images to shows for best appearance

### Images Not Loading?

1. **Upload Featured Images**: Go to each radio show and set a featured image
2. **Check File Paths**: Ensure CSS/JS files are in the correct theme folders
3. **Clear Cache**: Clear any caching plugins

### Styling Issues?

1. **CSS File**: Make sure `universal-radio-schedule.css` is uploaded correctly
2. **Theme Conflicts**: Some themes may override styles
3. **Elementor Cache**: Clear Elementor cache if using Elementor

## 🎯 Best Practices

### Image Guidelines

- **Size**: 400x400px minimum for best quality
- **Format**: JPG or PNG
- **Content**: Clear, high-contrast images work best
- **Branding**: Include show logos or host photos

### Show Descriptions

- **Length**: Keep descriptions under 100 words
- **Keywords**: Include relevant keywords for SEO
- **Engaging**: Write compelling descriptions to attract listeners

### Scheduling Tips

- **Consistency**: Keep regular show times
- **Overlap**: Avoid overlapping show times
- **Updates**: Keep schedule updated regularly
- **Breaks**: Account for commercial breaks in timing

## 🔄 Updates and Maintenance

### Regular Tasks

1. **Update Show Times**: Keep schedule current
2. **Add New Shows**: Create posts for new programs
3. **Update Images**: Refresh show images periodically
4. **Check Mobile**: Test on mobile devices regularly

### Performance Tips

- **Optimize Images**: Compress show images
- **Cache**: Use caching plugins for better performance
- **CDN**: Consider using a CDN for faster loading

## 🆘 Support

### Common Issues

**Q: Schedule shows "Loading..." forever**
A: Check that the AJAX URL is correct and WordPress is responding

**Q: Images appear square instead of rounded**
A: Ensure the CSS file is loaded and not overridden by theme styles

**Q: Shows don't update automatically**
A: Check that JavaScript is loading and no console errors exist

**Q: Mobile layout looks broken**
A: Verify responsive CSS is loading and test on actual devices

### Getting Help

1. **Check Browser Console**: Look for JavaScript errors
2. **WordPress Debug**: Enable WordPress debug mode
3. **Theme Compatibility**: Test with a default WordPress theme
4. **Plugin Conflicts**: Deactivate other plugins to test

## 📄 File Structure

```
radio-schedule-royal-elementor/
├── universal-radio-schedule.php     # Main WordPress integration
├── universal-radio-schedule.css     # Modern styling with rounded images
├── universal-radio-schedule.js      # Interactive functionality
├── UNIVERSAL-README.md              # This documentation
└── STAFF-GUIDE.md                   # Guide for radio station staff
```

## 🎉 What's New in Universal Version

- ✅ **Works with ANY radio station** (not just Highway Radio)
- ✅ **Proper WordPress integration** with posts and featured images
- ✅ **Rounded image corners** for modern look
- ✅ **Better mobile experience** with swipe navigation
- ✅ **Real-time updates** every 2 minutes
- ✅ **Week navigation** to view any day
- ✅ **Live show detection** with visual indicators
- ✅ **Improved admin interface** with better columns
- ✅ **SEO optimization** with proper post structure
- ✅ **Accessibility features** with keyboard navigation

## 🌟 Pro Tips

1. **Brand Colors**: Customize the CSS variables to match your station's brand
2. **Show Images**: Use consistent image sizes and styles across all shows
3. **Mobile First**: Always test on mobile devices first
4. **Performance**: Optimize images and use caching for better speed
5. **Content**: Write engaging show descriptions to attract more listeners

---

**Made with ❤️ for radio stations worldwide**

*This system is designed to work with any radio station using WordPress and Elementor. Customize the colors, images, and content to match your brand perfectly.*
