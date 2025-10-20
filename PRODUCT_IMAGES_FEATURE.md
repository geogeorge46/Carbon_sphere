# Product Images Feature Documentation

## Overview
The Seller Dashboard now supports product images via URL links. Sellers can add, edit, and manage product images by providing image URLs. Images are displayed as thumbnails in the product list and with full preview in forms.

## Features Implemented

### 1. **Database Schema Update**
- **Migration File:** `database/migrations/0008_add_image_url_to_products.sql`
- **New Column:** `image_url VARCHAR(500)` - Stores the product image URL
- **Location:** Added to products table after product_name column

### 2. **Backend Updates**

#### Product Model (`app/Models/Product.php`)
- **Updated `addProduct()` method:**
  - Now accepts and stores `image_url` parameter
  - Uses prepared statements to prevent SQL injection
  
- **Updated `updateProduct()` method:**
  - Now accepts and updates `image_url` parameter
  - Includes category_id in update query

#### SellerController (`app/Controllers/SellerController.php`)
- **`addProduct()` method:**
  - Added `image_url` field to data array
  - Added URL validation using `filter_var($url, FILTER_VALIDATE_URL)`
  - Error message: "Please enter a valid image URL"
  - Image URL is optional but must be valid if provided

- **`editProduct()` method:**
  - Added `image_url` field handling
  - Retrieves and displays existing image URL
  - Supports image URL updates

### 3. **Frontend Updates**

#### Add Product Form (`app/Views/seller/add_product.php`)
- **Image URL Input Field:**
  - Accepts any valid HTTP/HTTPS image URL
  - Placeholder: `https://example.com/image.jpg`
  - Optional field
  - Real-time preview via `oninput` and `onchange` events

- **Image Preview:**
  - Shows preview container when valid image URL is entered
  - Validates image loading before displaying
  - Max width: 200px, Max height: 200px
  - Border-radius: 4px for rounded corners
  - Object-fit: cover for proper scaling

#### Edit Product Form (`app/Views/seller/edit_product.php`)
- **Same features as add product form**
- **Current image:**
  - Displays existing image if URL is already set
  - Preview container shown by default if image exists
  - Allows updating to new image URL

#### My Products List (`app/Views/seller/my_products.php`)
- **New Image Column:**
  - Added as first column in products table
  - Displays product thumbnail (50px × 50px)
  - Shows placeholder icon if no image URL
  - Rounded corners and object-fit: cover
  - Tooltip on hover showing product name

### 4. **JavaScript Functionality**

#### Image Preview Function (`public/js/seller-dashboard.js`)
```javascript
/**
 * Preview image from URL
 */
function previewImage(url) {
  // Shows/hides preview container
  // Validates image before displaying
  // Handles loading errors gracefully
}
```

**Features:**
- Validates if URL is valid and image can be loaded
- Shows preview container only if image loads successfully
- Hides preview if URL is empty
- Handles image load errors silently
- Real-time feedback as user types

## Usage Guide

### For Sellers - Adding Product Image

1. **Navigate** to "Add Product" page
2. **Fill in** product details (name, category, description, price, carbon footprint)
3. **Enter Image URL:**
   - Paste a valid image URL (HTTP or HTTPS)
   - Example: `https://images.example.com/product.jpg`
4. **Preview Image:**
   - Image preview appears automatically below the URL field
   - If image doesn't load, preview remains hidden
5. **Submit Form:**
   - Image URL is saved with the product
   - Form validates URL format before submission

### For Sellers - Editing Product Image

1. **Navigate** to "My Products" → Click "Edit" on a product
2. **Update Image URL:**
   - Change the URL in the "Product Image URL" field
   - Or clear it to remove the image
3. **Preview:**
   - New preview appears as you type
4. **Save Changes:**
   - Click "Save Changes" to update

### For Sellers - Viewing Products

1. **Navigate** to "My Products"
2. **View Thumbnails:**
   - Product images appear in the first column
   - 50×50px thumbnails with proper scaling
   - Placeholder icon if no image set
3. **Hover** over thumbnail to see product name in tooltip

## Validation Rules

### URL Validation
- **Server-side:** `filter_var($url, FILTER_VALIDATE_URL)`
- **Client-side:** HTML5 URL input type validation
- **Image validation:** Image is tested before preview/save

### Requirements
- Must be valid HTTP or HTTPS URL
- Image must be accessible and not blocked by CORS
- Optional field - can be left blank
- No file size limits (depends on external hosting)

## Supported Image Formats
- JPG / JPEG
- PNG
- GIF
- WebP
- SVG
- Any format supported by modern browsers

## Best Practices

### Image URL Sources
1. **Free Image Services:**
   - Unsplash: https://unsplash.com
   - Pixabay: https://pixabay.com
   - Pexels: https://pexels.com
   - Imgur: https://imgur.com

2. **Product Image Hosting:**
   - Use dedicated image hosting services
   - Ensure URLs are permanent
   - Use CDN for faster loading

### Image Recommendations
- **Dimensions:** 200×200px or larger (minimum)
- **Aspect Ratio:** Square (1:1) for best results
- **File Size:** Keep under 500KB for faster loading
- **Quality:** Use high-quality images for better presentation
- **Consistency:** Use similar lighting and backgrounds

### URL Tips
- Avoid shortened URLs (might expire)
- Use direct image URLs (not webpage URLs)
- Test URL in browser before adding to product
- Use HTTPS URLs for better security

## Error Handling

### Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Image preview doesn't show | Invalid URL or URL blocked by CORS | Check URL format, try different image |
| 404 error | Image URL is broken | Verify URL is still active |
| Mixed content warning | HTTP URL on HTTPS site | Use HTTPS image URL instead |
| Image distorted | Wrong aspect ratio | Choose square images for best results |

## Technical Details

### Database Query
```sql
ALTER TABLE products ADD COLUMN image_url VARCHAR(500) AFTER product_name;
```

### Data Storage
- **Column Name:** `image_url`
- **Type:** VARCHAR(500)
- **Nullable:** Yes (can be NULL)
- **Default:** NULL

### API Endpoints
- **Add Product:** POST `/seller/addProduct`
  - Parameter: `image_url` (optional)
  
- **Edit Product:** POST `/seller/editProduct/{id}`
  - Parameter: `image_url` (optional)

### Security Considerations
- **Server-side URL validation** prevents invalid URLs
- **Client-side image loading validation** prevents broken images
- **No file upload** means no server storage risks
- **External URL hosting** keeps database lean
- **XSS prevention** through proper HTML escaping

## Future Enhancements

1. **Image Upload Feature:**
   - Allow direct file uploads
   - Store images on server
   - Automatic image optimization

2. **Image Gallery:**
   - Multiple images per product
   - Carousel/gallery view
   - Lightbox preview

3. **Image Optimization:**
   - Automatic resizing
   - Format conversion
   - Lazy loading

4. **Image Management:**
   - Bulk image updates
   - Image templates
   - Watermarking

## Testing Checklist

- [ ] Add product with valid image URL
- [ ] Add product without image URL
- [ ] Add product with invalid URL (should show error)
- [ ] Preview image appears correctly
- [ ] Preview disappears when URL is cleared
- [ ] Edit product with existing image
- [ ] Update image URL on existing product
- [ ] Remove image by clearing URL field
- [ ] Verify image thumbnail in products list
- [ ] Test with different image sources
- [ ] Test with broken image URL
- [ ] Test on mobile devices
- [ ] Test image preview with slow network
- [ ] Verify form validation before submit

## Support

For issues or questions about the product image feature:
1. Check image URL is accessible
2. Try different image sources
3. Verify HTTPS URLs for secure connections
4. Contact support with error details

---

**Feature Status:** ✅ Active and Production Ready  
**Last Updated:** 2024  
**Version:** 1.0