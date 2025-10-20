# Product Images Feature - Setup Guide

## Quick Setup (3 Steps)

### Step 1: Run Database Migration
Execute this SQL query in your MySQL database:

```sql
ALTER TABLE products ADD COLUMN image_url VARCHAR(500) AFTER product_name;
```

**Alternative:** Use phpMyAdmin
1. Open phpMyAdmin
2. Select your database
3. Click SQL tab
4. Paste the above query
5. Click Execute

### Step 2: Verify Files Updated
All necessary files have been updated automatically:

✅ `app/Models/Product.php` - Updated addProduct() and updateProduct() methods  
✅ `app/Controllers/SellerController.php` - Updated addProduct() and editProduct() methods  
✅ `app/Views/seller/add_product.php` - Added image URL input and preview  
✅ `app/Views/seller/edit_product.php` - Added image URL input and preview  
✅ `app/Views/seller/my_products.php` - Added product thumbnail column  
✅ `public/js/seller-dashboard.js` - Added previewImage() function  

### Step 3: Test the Feature

1. **Login** as a seller
2. **Navigate** to Seller Dashboard → Add Product
3. **Enter product details** and an image URL:
   ```
   https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200
   ```
4. **Watch image preview** appear automatically
5. **Submit** to create product with image
6. **View** thumbnail in "My Products" list

## Image URL Examples (Test URLs)

### Electronics
```
https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200
```

### Clothing
```
https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200
```

### Food & Beverage
```
https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=200
```

### Home & Garden
```
https://images.unsplash.com/photo-1578500494198-246f612d03b3?w=200
```

### Books & Media
```
https://images.unsplash.com/photo-1507842217343-583b8c8d1cbc?w=200
```

## Features Overview

### Add Product Form
- ✅ Image URL input field (optional)
- ✅ Real-time preview as you type
- ✅ URL validation (HTTP/HTTPS)
- ✅ Error messages for invalid URLs
- ✅ Preview shows automatically for valid URLs

### Edit Product Form  
- ✅ Update image URL
- ✅ Current image displays
- ✅ Change or remove image
- ✅ Same real-time preview
- ✅ Full form validation

### My Products List
- ✅ Product thumbnail column (first column)
- ✅ 50×50px square thumbnails
- ✅ Rounded corners and proper scaling
- ✅ Placeholder icon for missing images
- ✅ Tooltip showing product name on hover

## Troubleshooting

### Image Preview Not Showing
**Problem:** You entered a URL but preview doesn't appear
**Solutions:**
- Check URL is valid (starts with http:// or https://)
- Try accessing URL in browser address bar
- Image might be blocked by browser (CORS issue)
- Try different image source

### 404 Error on Image
**Problem:** Image was working but now shows broken image
**Solutions:**
- Check if external image is still accessible
- Verify URL hasn't changed
- Use different image hosting service

### Mixed Content Warning
**Problem:** Using HTTP on HTTPS site
**Solutions:**
- Always use HTTPS URLs: `https://` not `http://`
- Request image provider to use HTTPS

### Image Looks Distorted
**Problem:** Image appears stretched or squeezed
**Solutions:**
- Use square images (1:1 aspect ratio)
- Resize image to 200×200px before hosting
- Choose different image

## Database Verification

### Check Migration Applied
```sql
DESCRIBE products;
```
Should show `image_url` column in the result.

### View Image URLs
```sql
SELECT product_id, product_name, image_url FROM products;
```

## Reverting the Feature (If Needed)

To remove the image_url column:
```sql
ALTER TABLE products DROP COLUMN image_url;
```

## FAQ

**Q: Can I upload images directly?**  
A: Currently, you must provide a URL. File upload feature coming soon.

**Q: Are there limits on image URLs?**  
A: URLs can be up to 500 characters long.

**Q: What image formats are supported?**  
A: JPG, PNG, GIF, WebP, SVG - any format modern browsers support.

**Q: Can I use social media image links?**  
A: Only if the URL is permanent. Instagram/Facebook URLs may expire.

**Q: Is image resizing automatic?**  
A: Yes, images are scaled to fit the display area.

**Q: Can sellers bulk update images?**  
A: Not yet, but each product can be edited individually.

**Q: Are images stored on the server?**  
A: No, only the URL is stored. Images hosted externally.

## Performance Notes

- ✅ No server storage needed (external URLs)
- ✅ Minimal database impact (just URL string)
- ✅ Fast image loading with CDN-hosted images
- ✅ No server bandwidth usage for images

## Security Notes

- ✅ URL validation prevents injection attacks
- ✅ No file uploads = no upload vulnerabilities
- ✅ External hosting = isolated from server
- ✅ Proper HTML escaping prevents XSS

## Next Steps

1. Run the database migration
2. Test with sample image URLs
3. Train sellers on feature usage
4. Monitor image URLs for broken links
5. Consider image optimization in future

## Support Resources

- **Full Documentation:** See `PRODUCT_IMAGES_FEATURE.md`
- **Seller Guide:** Users should read feature documentation
- **Code Reference:** Check `app/Controllers/SellerController.php`

---

**Setup Time:** ~5 minutes  
**Difficulty:** Easy  
**Status:** ✅ Production Ready