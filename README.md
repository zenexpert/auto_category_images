# Automatic Category Images for Zen Cart

An admin-side plugin for Zen Cart that automatically assigns images to categories that are missing them. It works by scanning your store for empty categories, finding a random product image within that category (or its subcategories), safely copying the image to your categories image folder, and assigning it to the category.

## Features

* **Encapsulated Plugin:** Zero Core Modifications.
* **Smart Recursion:** If a parent category has no direct products, it will recursively search through its subcategories until it finds a valid product image.
* **Physical File Verification:** Checks that the physical image file actually exists on the server before attempting to assign it, preventing broken "ghost" images.
* **Detailed Reporting:** Returns a clear, color-coded summary of successes, warnings, and file-system errors sorted for easy reading.

## Requirements

* Zen Cart v2.2.2 (should work with 1.5.8+)
* PHP 7.3 - 8.x

## Installation

1. Backup your database and files (always a good practice).
2. Upload the contents of the `zc_plugins` directory to your website's `zc_plugins` directory (or simply upload the entire `zc_plugins` directory to your store root).
3. Install the plugin using Plugin Manager (Admin -> Modules -> Plugin Manager).

## Usage

1. Log into your Zen Cart Admin.
2. Navigate to **Extras -> Automatic Category Images**.
3. Click the **Scan and Assign Images** button.
4. Review the generated log to see which categories were updated.

## Troubleshooting

* **"Failed to copy" errors:** This means your web server does not have the necessary permissions to write files to your `images/` or `images/categories/` directories. Check your folder permissions via FTP or your hosting control panel.

## License

Portions Copyright 2003-2026 Zen Cart Development Team. Released under the **GNU Public License V2.0**.
