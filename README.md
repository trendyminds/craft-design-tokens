# Design Tokens

## 🤔 What is this?
Design Tokens is a Craft dropdown fieldtype where the options and the values are controllable via JSON files.

If you use Tailwind the JIT process only runs against Tailwind values it finds in your filesystem. If you have a class like `bg-indigo-700` in your database there's nothing Tailwind can do to find that (unless you enable Project Config, but do you _really_ want Tailwind crawling your Project Config?).

Design Tokens allows you to define the values within JSON files on your filesystem, making it possible to use Tailwind's JIT process and provide a simple way to add and edit new values to your dropdowns.

## ⚠️ Careful, though!

Editing these JSON files means it's possible to break the output of your data. For example:

```diff
{
  "standard": "my-12",
+ "tighter": "my-6",
- "tight": "my-6",
  "none": "my-0"
}
```

Changing "tight" to "tighter" would break any entry using "tight"! Now when it tries to locate the value of "tight" it will come up empty until you've changed all of those values.

## 📝 Usage

### A single key/value pair

```json
{
  "standard": "my-12",
  "tight": "my-6",
  "none": "my-0"
}
```

```twig
{{ entry.myTokenField }}
{# Outputs the value of the selected option (my-12, my-6, my-0) #}
```

```twig
{{ entry.myTokenField.key }}
{# Outputs the key of the selected option (standard, tight, none) #}
```

### A nested key/value pair

```json
{
  "red": {
    "text": "text-red-500",
    "bg": "bg-red-100"
  },
  "green": {
    "text": "text-green-500",
    "bg": "bg-green-100"
  },
  "blue": {
    "text": "text-blue-500",
    "bg": "bg-blue-100"
  }
}

```

```twig
{{ entry.myTokenField.key }}
{# Outputs the key of the selected option (red, green, blue) #}
```

```twig
{{ entry.myTokenField.get('text') }}
{# Outputs the nested value of the selected option (text-red-500, text-green-500, text-blue-500) #}

{{ entry.myTokenField.get('bg') }}
{# Outputs the nested value of the selected option (bg-red-100, bg-green-100, bg-blue-100) #}
```

## 📦 Installing

1. Download the zip of this project
2. Move the `designtokens` folder into `modules/`
3. Include the `designtokens` data into `config/app.php` in the `modules` and `bootstrap` sections:

```php
'modules' => [
  'designtokens' => \modules\designtokens\DesignTokens::class,
],
'bootstrap' => [
  'designtokens',
],
```

4. Ensure your `composer.json` is referencing the `designtokens` module:
```json
"autoload": {
  "psr-4": {
    "modules\\designtokens\\": "modules/designtokens/",
  }
}
```

5. Run `composer dump-autoload`
6. Add the "Design Tokens" fieldtype where needed
