<?php
/**
 * @copyright Copyright 2003-2026 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: ZenExpert 2026-04-19 $
 */

class AutoCategoryImages {
    public $lastError = '';

    public function __construct() {

    }

    public function process() {
        global $db;

        // Group messages to sort them easily later
        $errors = [];
        $warnings = [];
        $successes = [];

        // Find categories with missing images
        $sql = "SELECT categories_id FROM " . TABLE_CATEGORIES . "
                WHERE categories_image = '' OR categories_image IS NULL";
        $categories = $db->Execute($sql);

        if ($categories->RecordCount() == 0) {
            return [TEXT_NO_CATEGORIES_TO_PROCESS];
        }

        foreach ($categories as $category) {
            $catId = $category['categories_id'];

            $catName = zen_get_category_name($catId, $_SESSION['languages_id']);
            if (empty($catName)) {
                $catName = 'Unknown/No Name';
            }

            // Reset error tracker for this loop
            $this->lastError = '';
            $image = $this->findRandomProductImage($catId);

            if ($image) {
                $newImagePath = $this->copyImageToCategoryDir($image, $catId);
                if ($newImagePath) {
                    $this->updateCategory($catId, $newImagePath);
                    $successes[] = sprintf(TEXT_SUCCESS_UPDATED, $catName, $catId, $newImagePath);
                } else {
                    $errors[] = sprintf(TEXT_ERROR_COPY, $catName, $catId);
                }
            } else {
                $full_path = zen_get_generated_category_path_rev($category['categories_id']);
                $path_array = explode('_', $full_path);
                $cID = array_pop($path_array);
                $parent_cPath = implode('_', $path_array);
                $link_params = 'cPath=' . $parent_cPath . '&cID=' . $cID;

                $catLink = zen_href_link(FILENAME_CATEGORIES, $link_params . '&action=edit_category');
                $warnings[] = sprintf(TEXT_NO_IMAGE_FOUND, $catName, $catId, $catLink);
            }
        }

        // Return sorted: Errors -> Warnings -> Successes
        return array_merge($errors, $warnings, $successes);
    }

    protected function findRandomProductImage($categoryId) {
        global $db;

        // Check for a direct product in this category
        $sql = "SELECT p.products_image
                FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                JOIN " . TABLE_PRODUCTS . " p ON p.products_id = p2c.products_id
                WHERE p2c.categories_id = " . (int)$categoryId . "
                AND p.products_image != '' AND p.products_image IS NOT NULL
                ORDER BY RAND() LIMIT 10";
        $result = $db->Execute($sql);

        // Loop through results to ensure the file physically exists before returning it
        foreach ($result as $row) {
            $image = $row['products_image'];
            $physicalPath = DIR_FS_CATALOG_IMAGES . $image;

            if (file_exists($physicalPath) && is_file($physicalPath)) {
                return $image;
            }
        }

        // If no product exists, recursively check subcategories
        $sql = "SELECT categories_id FROM " . TABLE_CATEGORIES . "
                WHERE parent_id = " . (int)$categoryId;
        $subCats = $db->Execute($sql);

        $subcatImages = [];
        foreach ($subCats as $subCat) {
            $img = $this->findRandomProductImage($subCat['categories_id']);
            if ($img) {
                $subcatImages[] = $img;
            }
        }

        // Return a random image gathered from subcategories
        if (!empty($subcatImages)) {
            return $subcatImages[array_rand($subcatImages)];
        }

        return false;
    }

    protected function copyImageToCategoryDir($productImage, $catId) {
        $source = DIR_FS_CATALOG_IMAGES . $productImage;
        $targetDir = DIR_FS_CATALOG_IMAGES . 'categories/';

        // Create the categories directory if it doesn't exist
        if (!is_dir($targetDir)) {
            if (!@mkdir($targetDir, 0755, true)) {
                $err = error_get_last();
                $this->lastError = TEXT_FAILED_CREATE_DIRECTORY . ' ' . ($err['message'] ?? TEXT_FAILED_CREATE_DIRECTORY_MESSAGE);
                return false;
            }
        }

        // Add a prefix to ensure unique filenames and prevent naming conflicts
        $extension = pathinfo($source, PATHINFO_EXTENSION);
        $filename = pathinfo($source, PATHINFO_FILENAME);
        $newImageName = 'categories/' . $filename . '_cat' . $catId . '.' . $extension;
        $target = DIR_FS_CATALOG_IMAGES . $newImageName;

        // Try to copy the file
        if (@copy($source, $target)) {
            return $newImageName;
        } else {
            $err = error_get_last();
            $this->lastError = TEXT_COPY_REJECTED . ' ' . ($err['message'] ?? TEXT_COPY_REJECTED_MESSAGE);
            return false;
        }
    }

    protected function updateCategory($catId, $imagePath) {
        global $db;

        $sql = "UPDATE " . TABLE_CATEGORIES . "
                SET categories_image = :imagePath, last_modified = now()
                WHERE categories_id = :catId";

        $sql = $db->bindVars($sql, ':imagePath', $imagePath, 'string');
        $sql = $db->bindVars($sql, ':catId', $catId, 'integer');
        $db->Execute($sql);
    }
}
