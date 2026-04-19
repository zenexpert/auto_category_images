<?php

$define = [
    'HEADING_TITLE' => 'Automatic Category Images',
    'TEXT_INFO_DESCRIPTION' => 'This tool scans all categories without an assigned image. It will recursively search for a random product image within that category (or its subcategories), copy the image to the <strong>images/categories/</strong> directory, and assign it to the category.',
    'BUTTON_PROCESS' => 'Scan and Assign Images',
    'TEXT_SUCCESS_UPDATED' => '<span class="text-success">SUCCESS:</span> Category "%s" (ID %d) updated with image: %s',
    'TEXT_ERROR_COPY' => '<span class="text-danger">ERROR:</span> Found an image for Category "%s" (ID %d), but failed to copy it to the categories directory.',
    'TEXT_NO_IMAGE_FOUND' => '<span class="text-warning">WARNING:</span> No products found in Category "%s" (ID %d) or its subcategories. Skipped. <a href="%s" target="_blank">(Add Manually)</a>',
    'TEXT_NO_CATEGORIES_TO_PROCESS' => 'INFO: All categories already have images assigned.',
    'TEXT_FAILED_CREATE_DIRECTORY' => 'Failed to create directory.',
    'TEXT_FAILED_CREATE_DIRECTORY_MESSAGE' => 'Check folder permissions on /images/.',
    'TEXT_COPY_REJECTED' => 'Copy command rejected.',
    'TEXT_COPY_REJECTED_MESSAGE' => 'Check write permissions on /images/categories/.',

];

return $define;
