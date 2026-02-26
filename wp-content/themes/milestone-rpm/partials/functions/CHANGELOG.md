# Changelog – /functions/

this changelog tracks notable changes to any files in the `/functions/` directory.

## File Notes
- `blocks_copy.php`: tools for duplicating/modifying blocks from one page to another
- `blocks_updater.php`: core block migration engine
- `compiler_css.php`: handles scss-to-css compilation to style.css and admin.css
- `init.php`: creates initial version of files and initializes some global variables
- `theme_updater.php`: update functionality for springboard-based themes
- `ui_articles.php`: creates an articles post type (soon to be deprecated)
- `ui_content_blocks.php`: adds interface elements to content blocks in the wp admin
- `ui_excerpts.php`: creates and manages excerpts sidebar
- `ui_global_info.php`: creates global info options page
- `ui_lead_attribution.php`: captures utm parameters in form leads
- `ui_people_posts.php`: creates field groups for people posts
- `ui_svg_support.php`: enables svg uploads and sanitization for wp
- `ui_taxonomy.php`: manages taxonomy related usage across blocks
- `ui_tinymce.php`: customizes wp tinymce editor toolbar or styles
- `ui_utilities.php`: various admin and ACF helpers, file change detection, and debug utilities
- `ui_wp_menus.php`: adds and manages theme support for wp menus (not fully implemented)

---

## blocks_copy.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## blocks_updater.php

### 2.0.1 - 2025-08-17
- added `$version` variable to top of file

### 2.0.0 - 2025-07-28
- complete rework of block updater in collab with ChatGPT
- implemented snapshots
- implemented intelligent automated field mapping logic from old structuer to new

---

## compiler_css.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0
- initial breakout of functionality from `functions.php` to an isolated file

---

## init.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## theme_updater.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_articles.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_content_blocks.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_excerpts.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_global_info.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_lead_attribution.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_people_posts.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_svg_support.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_svg_support.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_taxonomy.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_tinymce.php

### 1.0.2 - 2025-08-26
- corrected loading location of `admin.js`
- adjusted path to button image in `admin.js`
- modified conditionals for when it will be added

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_utilities.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file

---

## ui_wp_menus.php

### 1.0.1 - 2025-08-17
- added `$version` variable to top of file

### 1.0.0 - 2025-05-05
- initial breakout of functionality from `functions.php` to an isolated file