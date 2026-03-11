# Elementor Dynamic Popup

Add Elementor popup content to Loop Grid items, Loop Carousel items, and dynamic templates. Each listing item gets its own popup with content based on that item's data (post title, featured image, custom fields, etc.).

## Requirements

- WordPress 6.0+
- PHP 7.4+
- [Elementor](https://wordpress.org/plugins/elementor/) (free)
- [Elementor Pro](https://elementor.com/pro/) (for Popups)

## Installation

1. Upload the plugin folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Ensure Elementor and Elementor Pro are installed and activated

## How It Works

1. **Create a Popup** in Elementor Pro (Templates → Popups). Design it using dynamic tags (Post Title, Featured Image, Post Content, Custom Fields, etc.).

2. **Create a Loop Template** (Theme Builder → Loop Templates, or add a Loop Grid to a page and create template). Add the **Dynamic Popup Trigger** widget to your loop item template.

3. **Select your Popup** in the widget settings and customize the trigger button text.

4. When users click the trigger on each grid item, the popup opens with content specific to that item. The popup content is rendered with the correct post context, so all dynamic tags resolve to the clicked item's data.

## Usage

### In Loop Grid / Loop Carousel

1. Add the Loop Grid or Loop Carousel widget to your page.
2. Create or edit the loop item template.
3. Add the **Dynamic Popup Trigger** widget where you want the "View Details" (or similar) button.
4. In widget settings, select the popup template and customize the button text.
5. Use dynamic tags in your popup (Post Title, Post Excerpt, Featured Image, etc.)—they will show the correct data for each item when opened.

### In Single Post Template (Theme Builder)

The widget works in Single Post templates too. The popup will display content for the current post being viewed.

## Features

- Uses Elementor's native popup templates—design once, reuse everywhere
- Full dynamic content support (post title, excerpt, featured image, custom fields, etc.)
- Compatible with Loop Grid, Loop Carousel, and Load More
- Works with custom post types
- Accessible (ARIA attributes, focus trap, ESC to close)
- Styled to match Elementor popup appearance
- No page reload—content loads from the loop item's rendered output

## Technical Notes

- The popup content is rendered server-side with the correct post context for each loop item.
- Content is embedded in the page (hidden) and shown in a modal on click—no AJAX required for basic operation.
- Uses `get_builder_content_for_display()` for proper Elementor content rendering.

## License

GPL v2 or later
