# Al Ihsan Da'awa College Website Customization

The goal of this project is to recreate the design and structure of the [Jamiathul Hind Al Islamiyya website](https://jamiathulhind.com) and customize it for **Al Ihsan Da'awa College**, using the student association logo provided by the user and applying the specific statistics: **150 students**, **50 alumni**, and **10 faculties**.

The site will be developed as a dynamic PHP project (split into header, footer, index, and subpage templates) so that it runs natively on the user's local XAMPP environment (`c:\xampp\htdocs\alihsan`).

## User Review Required

> [!IMPORTANT]
> **Statistics Alignment**:
> The statistics for Al Ihsan Da'awa College will be updated across the homepage slider and fun-facts section as follows:
> - **Students**: 150 (reduced from 12,000+)
> - **Alumni**: 50 (reduced from 5,000+)
> - **Faculties**: 10 (reduced from 2,000+)

> [!IMPORTANT]
> **Logo Replacement**:
> We will copy the user's uploaded logo (`media__1783049145602.png`) to `assets/images/logo/logo.png` and use it as the main brand logo in the header, footer, and sidebar.

> [!NOTE]
> **Asset Downloader Script**:
> We will create a utility script `download_assets.php` to dynamically fetch all design system files (CSS, JS, fonts, background images) from the live target site `jamiathulhind.com`. This ensures that the cloned website has the exact visual appearance, responsive grids, sliders, and animation libraries as the original, without missing files.

## Proposed Changes

### Assets

#### [NEW] [logo.png](file:///c:/xampp/htdocs/alihsan/assets/images/logo/logo.png)
- Copy of the uploaded college/student association logo image file to be displayed at the top and in the footer.

#### [NEW] [download_assets.php](file:///c:/xampp/htdocs/alihsan/download_assets.php)
- A PHP CLI script to download and structure the original site's assets (Bootstrap, Swiper, custom stylesheets, script files, background graphics, fonts, and relevant images).
- We will execute this script using XAMPP's PHP CLI (`C:\xampp\php\php.exe download_assets.php`).

---

### Core Structure (PHP templates)

#### [NEW] [header.php](file:///c:/xampp/htdocs/alihsan/header.php)
- Shared site header containing meta tags, style links, navigation bar, mobile menu triggers, and the customized **Al Ihsan Da'awa College** brand text and logo.

#### [NEW] [footer.php](file:///c:/xampp/htdocs/alihsan/footer.php)
- Shared footer section containing social media handles, quick links, campus details, contact details (email/phone/address), copyright notices, and scripts.

---

### Website Pages

#### [NEW] [index.php](file:///c:/xampp/htdocs/alihsan/index.php)
- Replicated homepage of Jamiathul Hind with:
  - Hero slider showcasing college statistics (150 students, 10 faculties, 50 alumni).
  - About Al Ihsan Da'awa College introductory text section.
  - Interactive login widgets and resource links tailored to Al Ihsan.
  - Program cards detailing Foundational, Bachelor's, Master's, PG Diploma, and Short-run programs.
  - Campus Life, Events, and News & Updates cards.

#### [NEW] [about.php](file:///c:/xampp/htdocs/alihsan/about.php)
- About us section, adapting Chancellor/Vice-Chancellor/Registrar content to a unified college description or simple placeholders for Al Ihsan Da'awa College.

#### [NEW] [program.php](file:///c:/xampp/htdocs/alihsan/program.php)
- Page rendering details of the academic programs.

#### [NEW] [apply.php](file:///c:/xampp/htdocs/alihsan/apply.php)
- Single-window online admission application form placeholder page.

#### [NEW] [fee.php](file:///c:/xampp/htdocs/alihsan/fee.php)
- College admission fee structure page.

#### [NEW] [news.php](file:///c:/xampp/htdocs/alihsan/news.php) & [news-details.php](file:///c:/xampp/htdocs/alihsan/news-details.php)
- News bulletin archive and dynamic detail rendering page.

#### [NEW] [events.php](file:///c:/xampp/htdocs/alihsan/events.php) & [event-details.php](file:///c:/xampp/htdocs/alihsan/event-details.php)
- Events agenda archive and dynamic detail rendering page.

#### [NEW] [affiliated-institutions.php](file:///c:/xampp/htdocs/alihsan/affiliated-institutions.php)
- List of related campuses or institutions.

#### [NEW] [contact.php](file:///c:/xampp/htdocs/alihsan/contact.php)
- Contact information page with maps and inquiry submission forms.

---

## Verification Plan

### Automated Steps
1. Run the asset download script:
   ```cmd
   C:\xampp\php\php.exe download_assets.php
   ```
2. Verify all downloaded assets are present inside the `assets/` and `uploads/` directories.

### Manual Verification
1. Launch the local Apache server in XAMPP.
2. Open `http://localhost/alihsan/` in the web browser.
3. Validate:
   - Responsive layout alignment matches `jamiathulhind.com`.
   - The logo at the top is replaced by the uploaded Al Ihsan logo.
   - Header shows "AL IHSAN DA'AWA COLLEGE".
   - The stats in both the Slider and Fun Fact section show:
     - 150 Students
     - 50 Alumni
     - 10 Faculties
   - All navigation links load correctly without 404 errors.
