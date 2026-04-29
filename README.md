# Super Elem Pro 🚀

**Super Elem Pro** is a high-performance, modular WordPress plugin designed to unlock Elementor Pro-level features using 100% free and open-source resources. This plugin is built for **personal use only** and is specifically tailored for the latest stable version of the free Elementor plugin.

Created and owned by **Rohit**.

---

## 📋 Features & Status

| Category | Feature | Status |
| :--- | :--- | :--- |
| **Theme Builder** | Custom Header, Footer, Single, Archive, 404 & Search Templates | ✅ Active |
| **Popup System** | Advanced Builder with Exit Intent, Scroll, Time, & Click Triggers | ✅ Active |
| **Advanced Forms** | Multi-step, Database Storage, Webhooks, & Email Notifications | ✅ Active |
| **Dynamic Content** | Tags for Post Meta, User Meta, Site Data, ACF, Pods & Meta Box | ✅ Active |
| **Extensions** | Sticky Elements, Motion Effects, Custom CSS & JS, Display Conditions | ✅ Active |
| **Pro Widgets** | Posts Grid, Pricing Table, Timeline, Lottie, Charts, Data Table | ✅ Active |
| **Performance** | Modular Loader (Enable/Disable anything), Conditional Assets | ✅ Active |
| **Automation** | GitHub Actions Auto-Versioning, ZIP Generation & Release | ✅ Active |

---

## 🛠 Installation

1. Go to the **Releases** tab in this GitHub repository.
2. Download the latest `super-elem-pro-vX.X.X.zip` file.
3. Log in to your WordPress Admin.
4. Go to **Plugins > Add New > Upload Plugin**.
5. Select the downloaded ZIP file, install, and click **Activate**.

---

## 🚀 Activation Guide

Once activated:
1. Navigate to the new **Super Elem Pro** menu in your WordPress sidebar.
2. Visit the **Modules** tab.
3. Toggle the features you wish to use (all are enabled by default).
4. Go to **Elementor > Settings** to ensure all standard Elementor features are active.
5. Open any page with Elementor; you will find a new widget category named **Super Elem Pro**.

---

## 💻 Development Setup

If you want to modify the code or add new features:

### Requirements
- **LocalWP**, Laragon, or Docker for local development.
- **Composer** (Installed globally).
- **Node.js** (Optional, for future asset minification).

### Setup Steps
1. Clone this repository into your `wp-content/plugins/` folder.
2. Open your terminal in the plugin folder.
3. Run `composer install` to generate the PSR-4 autoloader.
4. Start building! All classes in `includes/` are automatically mapped to the `SuperElemPro\` namespace.

---

## 🔄 Automatic Versioning & Updates

This plugin uses **GitHub Actions** to handle the heavy lifting.
- **Trigger:** Every time you `git push` to the `main` branch.
- **Automation:**
    1. The script reads the current version in `super-elem-pro.php`.
    2. It automatically bumps the version (e.g., `1.0.5` -> `1.0.6`).
    3. It updates the version number in the main plugin file and `README.md`.
    4. It generates a **Production ZIP** (removing all dev junk like `.git` and `tests`).
    5. It creates a **New Release** and attaches the ZIP as an asset.
    6. It commits the version change back to your repository.

---

## 🔒 Code Protection & Obfuscation

To protect your logic and ensure this remains a personal-use tool:
1. The **GitHub Repository** must remain **Private**.
2. The `release.yml` workflow prepares the code for production.
3. **Obfuscation Instruction:** For deep protection, run a PHP Obfuscator (like YAK Pro) on the `includes/` folder before final production packaging.
4. **Note:** The version pushed to your site via the ZIP is optimized and stripped of development comments.

---

## 📂 Folder Structure

```text
super-elem-pro/
├── .github/workflows/      # GitHub Automation scripts
├── assets/                 # CSS, JS, and Images
├── includes/
│   ├── Admin/              # Admin Settings and Dashboard UI
│   ├── Core/               # Plugin Bootstrap and Autoloader
│   ├── Helpers/            # Reusable utility functions
│   ├── Modules/            # Individual Feature Modules
│   │   ├── ThemeBuilder/   # Header/Footer/Template engine
│   │   ├── PopupBuilder/   # Popup logic and triggers
│   │   ├── Forms/          # Form widgets and submission logic
│   │   ├── Widgets/        # Pro Widget collection
│   │   └── Extensions/     # Sticky, Motion, Custom CSS
├── templates/              # PHP templates for Theme/Popup builders
├── vendor/                 # Composer Autoload files
├── super-elem-pro.php      # Main Plugin Entry
└── composer.json           # PHP Dependency Manager
```

---

## 📝 Changelog

### [1.0.0] - 2024-05-20
- Initial modular architecture release.
- Added Theme Builder for Header/Footer.
- Added Advanced Form widget with Database Action.
- Added Popup Builder with Exit Intent and Scroll triggers.
- Integrated GitHub Actions for Auto-Versioning.

---

## ⚖️ License & Warning

**License:** This project is licensed under the GPL-2.0 License.

⚠️ **WARNING:** This plugin is for **Personal Use Only**.
- Unauthorized distribution, resale, or commercial use is strictly prohibited.
- Created by **Rohit**.
- Use at your own risk. The author is not responsible for any site issues caused by misuse of "Pro" level hooks.

---
*Built with ❤️ by Rohit.*