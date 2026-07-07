# WooCommerce Billing Email Domain Warning

Shows an admin warning when a WooCommerce billing email uses an uncommon domain.

## Description

WooCommerce Billing Email Domain Warning displays a warning under the billing address on WooCommerce order edit pages when the billing email domain is not in the allowed domain list.

This helps store admins quickly notice uncommon or potentially problematic email domains before relying on order emails.

## Features

- Shows warning on WooCommerce order edit pages
- Checks billing email domain
- Uses an allowed domain list
- Filterable domain list
- Filterable warning message
- HPOS compatible
- No custom database tables
- Single-file plugin

## Requirements

- PHP 7.4+
- WordPress 6.0+
- WooCommerce 7.0+

## Installation

1. Create a folder named `woocommerce-billing-email-domain-warning`.
2. Place `woocommerce-billing-email-domain-warning.php` inside it.
3. Upload the folder to:

```text
wp-content/plugins/
```

4. Activate the plugin from WordPress admin.

## Usage / How it Works

Open a WooCommerce order in the admin panel.

If the billing email domain is not in the allowed list, the plugin shows this warning:

```text
⚠️ ممکن است ایمیل برای کاربر ارسال نشود!
```

## Data Storage

The plugin does not store any data.

It only reads the billing email from the WooCommerce order object.

## Development

Built with:

- WordPress Coding Standards
- Native WordPress APIs
- WooCommerce order object
- Capability checks
- Sanitized domain handling
- Escaped admin output
- Translation-ready strings
- HPOS-compatible architecture

## Hooks

- `before_woocommerce_init`
- `plugins_loaded`
- `woocommerce_admin_order_data_after_billing_address`

## Filters

### `wc_bedw_allowed_domains`

Change the allowed email domain list.

```php
add_filter(
	'wc_bedw_allowed_domains',
	static function ( $domains ) {
		$domains[] = 'example.com';

		return $domains;
	}
);
```

### `wc_bedw_warning_message`

Change the warning message.

```php
add_filter(
	'wc_bedw_warning_message',
	static function () {
		return '⚠️ This email domain may have delivery issues.';
	}
);
```

### `wc_bedw_required_capability`

Change the required capability.

```php
add_filter(
	'wc_bedw_required_capability',
	static function () {
		return 'manage_woocommerce';
	}
);
```

## Future Improvements

- Admin settings page
- Editable allowed domain list
- Optional domain risk levels

## License

GPL-2.0-or-later

## Author

Amirreza Shayesteh Far

GitHub: https://github.com/amirrezashf

---

# هشدار دامنه ایمیل صورتحساب ووکامرس

نمایش هشدار مدیریتی زمانی که ایمیل صورتحساب سفارش از دامنه‌های رایج یا مجاز نباشد.

## توضیحات

افزونه WooCommerce Billing Email Domain Warning در صفحه ویرایش سفارش ووکامرس، دامنه ایمیل صورتحساب را بررسی می‌کند.

اگر دامنه ایمیل در لیست دامنه‌های مجاز نباشد، زیر بخش آدرس صورتحساب یک هشدار نمایش داده می‌شود.

## ویژگی‌ها

- نمایش هشدار در صفحه ویرایش سفارش ووکامرس
- بررسی دامنه ایمیل صورتحساب
- استفاده از لیست دامنه‌های مجاز
- امکان تغییر لیست دامنه‌ها با filter
- امکان تغییر متن هشدار با filter
- سازگار با HPOS
- بدون جدول اختصاصی دیتابیس
- معماری تک‌فایلی

## نیازمندی‌ها

- PHP 7.4+
- WordPress 6.0+
- WooCommerce 7.0+

## نصب

1. یک پوشه با نام `woocommerce-billing-email-domain-warning` بسازید.
2. فایل `woocommerce-billing-email-domain-warning.php` را داخل آن قرار دهید.
3. پوشه را در مسیر زیر آپلود کنید:

```text
wp-content/plugins/
```

4. افزونه را از پنل مدیریت وردپرس فعال کنید.

## نحوه استفاده / عملکرد افزونه

وارد صفحه ویرایش سفارش ووکامرس شوید.

اگر دامنه ایمیل صورتحساب در لیست مجاز نباشد، پیام زیر نمایش داده می‌شود:

```text
⚠️ ممکن است ایمیل برای کاربر ارسال نشود!
```

## ذخیره‌سازی داده

این افزونه هیچ داده‌ای ذخیره نمی‌کند.

فقط ایمیل صورتحساب را از آبجکت سفارش ووکامرس می‌خواند.

## توسعه

توسعه داده‌شده بر اساس:

- WordPress Coding Standards
- Native WordPress APIs
- WooCommerce order object
- بررسی capability
- مدیریت sanitize شده دامنه
- خروجی escape شده در admin
- متن‌های آماده ترجمه
- معماری سازگار با HPOS

## هوک‌ها

- `before_woocommerce_init`
- `plugins_loaded`
- `woocommerce_admin_order_data_after_billing_address`

## فیلترها

### `wc_bedw_allowed_domains`

تغییر لیست دامنه‌های مجاز.

```php
add_filter(
	'wc_bedw_allowed_domains',
	static function ( $domains ) {
		$domains[] = 'example.com';

		return $domains;
	}
);
```

### `wc_bedw_warning_message`

تغییر متن هشدار.

```php
add_filter(
	'wc_bedw_warning_message',
	static function () {
		return '⚠️ ممکن است این دامنه ایمیل مشکل ارسال داشته باشد.';
	}
);
```

### `wc_bedw_required_capability`

تغییر capability لازم.

```php
add_filter(
	'wc_bedw_required_capability',
	static function () {
		return 'manage_woocommerce';
	}
);
```

## بهبودهای آینده

- صفحه تنظیمات
- ویرایش لیست دامنه‌های مجاز از پنل
- سطح‌بندی ریسک دامنه‌ها

## مجوز

GPL-2.0-or-later

## نویسنده

Amirreza Shayesteh Far

GitHub: https://github.com/amirrezashf
