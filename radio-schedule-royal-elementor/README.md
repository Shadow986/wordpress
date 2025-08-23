# 🎵 HIGHWAY RADIO SCHEDULE - ROYAL ELEMENTOR ADDONS
## Complete Setup Guide (Clean Version)

### 📋 WHAT YOU GET:
- ✅ Auto-updating radio schedule (updates every 60 seconds)
- ✅ Shows 3 current live shows in professional grid
- ✅ Displays upcoming shows for the day
- ✅ Easy admin interface for radio station staff
- ✅ Mobile responsive design
- ✅ Professional radio station look with animations
- ✅ Perfect integration with Royal Elementor Addons

---

## 🚀 INSTALLATION (4 SIMPLE STEPS)

### STEP 1: ADD PHP CODE
1. **WordPress Admin → Appearance → Theme Editor**
2. **Select functions.php**
3. **Scroll to bottom** and paste code from `functions.php`
4. **Click Update File**

### STEP 2: UPLOAD JAVASCRIPT
1. **Access your website files** (FTP/File Manager)
2. **Go to:** `/wp-content/themes/YOUR-THEME-NAME/js/`
3. **Create `js` folder** if it doesn't exist
4. **Upload:** `radio-schedule.js`

### STEP 3: ADD ROYAL ELEMENTOR CSS WIDGET
1. **Edit page with Elementor**
2. **Add "Custom CSS" widget** (Royal Elementor Addons)
3. **Settings:**
   - **CSS Selector:** `.radio-schedule-container`
   - **CSS Code:** Paste from `styles.css`
   - **Apply CSS to:** Current Page

### STEP 4: ADD HTML WIDGET
1. **Add HTML widget** (below CSS widget)
2. **Paste code from:** `schedule.html`
3. **Save and test!**

---

## 📺 CREATE YOUR FIRST SHOW

### QUICK TEST:
1. **WordPress Admin → Radio Shows → Add New**
2. **Fill in:**
   ```
   Title: Test Morning Show
   Days: monday,tuesday,wednesday,thursday,friday
   Start Time: 09:00
   End Time: 12:00
   Host Name: Test DJ
   ```
3. **Add featured image**
4. **Publish and check your page!**

---

## 🎯 WIDGET ORDER (IMPORTANT!)
```
1. Custom CSS Widget (Royal Elementor)
   ├── CSS Selector: .radio-schedule-container
   └── CSS Code: [from styles.css]

2. HTML Widget (Elementor)
   └── HTML Code: [from schedule.html]
```

---

## 📱 FEATURES INCLUDED

### FOR VISITORS:
- **Live shows display** with pulsing "LIVE" indicator
- **Upcoming shows** with times and DJ names
- **Auto-updates** every minute without page refresh
- **Mobile responsive** design
- **Professional animations** and hover effects

### FOR RADIO STAFF:
- **Easy show management** through WordPress admin
- **Simple form fields:** Title, Days, Times, Host, Image
- **No technical knowledge** required
- **Clear instructions** and examples provided

---

## 🔧 TROUBLESHOOTING

### IF SHOWS DON'T APPEAR:
1. **Check CSS Selector:** Must be `.radio-schedule-container`
2. **Check widget order:** CSS widget above HTML widget
3. **Clear cache:** Elementor → Tools → Regenerate CSS
4. **Check browser console** for JavaScript errors

### COMMON ISSUES:
- **Days format:** Use `monday,tuesday,friday` (lowercase, no spaces)
- **Time format:** Use `09:00` not `9:00 AM`
- **File path:** JavaScript must be in `/themes/YOUR-THEME/js/`

---

## 📞 SUPPORT

### QUICK CHECKLIST:
- [ ] PHP code added to functions.php
- [ ] JavaScript uploaded to correct folder
- [ ] Royal Elementor CSS widget with selector
- [ ] HTML widget with schedule code
- [ ] Test show created
- [ ] Page displays schedule

### NEED HELP?
1. **Check all 4 files are properly installed**
2. **Verify Royal Elementor Addons is active**
3. **Test with simple show first**
4. **Check browser console for errors (F12)**

---

**🎵 Ready to get your radio schedule live!**
**📻 Professional, auto-updating, mobile-friendly radio schedule system.**
