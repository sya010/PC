# Implementation Plan: Kurdish Translations & Tailwind CSS Theme Compliance

## Overview
This document outlines the comprehensive overhaul to:
1. Complete Kurdish (KU) translations for all UI strings
2. Ensure language/theme switcher correctly handles the new locale
3. Standardize all color styling to use Tailwind CSS theme tokens (no hardcoded hex or custom colors)
4. Verify dark/light theme consistency

---

## Phase 1: Translation Audit & Completion

### Current State
- **EN translations**: [lang/en/messages.php](lang/en/messages.php) - COMPLETE (~180+ keys)
- **AR translations**: [lang/ar/messages.php](lang/ar/messages.php) - COMPLETE (~180+ keys)
- **KU translations**: [lang/ku/messages.php](lang/ku/messages.php) - NEEDS AUDIT FOR COMPLETENESS

### Tasks

#### 1.1 Audit Missing Kurdish Translations
- **File**: [lang/ku/messages.php](lang/ku/messages.php)
- **Action**: Compare all keys in EN and AR files against KU
- **Expected Missing Areas** (to be verified):
  - Admin panel translations (`messages.admin.*`)
  - User order translations (`messages.user_orders.*`)
  - Product page translations (`messages.product.*`)
  - Builder/compatibility strings
  - Cart-related strings
  - Error/validation messages
  - Form labels and placeholders

#### 1.2 Complete Missing Kurdish Translations
- Add all missing key-value pairs to [lang/ku/messages.php](lang/ku/messages.php)
- Maintain consistent Kurdish terminology for technical terms
- Use proper Kurdish diacritics (زمانی کوردی)

### Translation Keys to Verify
```
messages.admin.*
messages.user_orders.*
messages.product.*
messages.builder.*
messages.cart.*
messages.validation.*
messages.form.*
messages.errors.*
messages.success.*
messages.confirm.*
```

---

## Phase 2: Language Switcher & Locale Handling

### Current State
- **Component**: [app/Livewire/LanguageSwitcher.php](app/Livewire/LanguageSwitcher.php)
  - ✅ Already has KU locale configured
  - ✅ Has RTL direction set for KU
  - ✅ Properly mounted and switching locales

### Tasks

#### 2.1 Verify Locale Cookie Management
- Check if locale is correctly saved/restored via Cookie
- Ensure KU locale persists across sessions
- **File to check**: [app/Livewire/LanguageSwitcher.php](app/Livewire/LanguageSwitcher.php)

#### 2.2 Verify Middleware Locale Handling
- Ensure locale is set before rendering views
- **Search for**: SetLocale middleware or locale initialization
- **Expected location**: [app/Http/Middleware](app/Http/Middleware)

#### 2.3 Test Language Switcher Functionality
- Switching from EN → KU → AR → EN works correctly
- Cookie persists after page refresh
- Translations load correctly for each locale

### Files Involved
- [app/Livewire/LanguageSwitcher.php](app/Livewire/LanguageSwitcher.php)
- [app/Http/Middleware](app/Http/Middleware) (verify locale middleware exists)
- [bootstrap/app.php](bootstrap/app.php) (verify locale configuration)

---

## Phase 3: Tailwind CSS Theme Token Audit

### Current State
- **Layout**: [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php)
  - ✅ Using theme tokens: `bg-surface-primary`, `text-content-primary`, `dark:bg-brand-dark`
  - ✅ Theme switcher includes dark class handling
  - ✅ Proper Tailwind configuration

### Token Categories to Verify
The app uses these token patterns:
```
Colors:
  - brand-base, brand-light, brand-accent
  - surface-primary, surface-secondary
  - content-primary, content-secondary, content-muted
  - border-subtle
  - dark-* (dark-900, dark-800, dark-700, dark-600, dark-500, dark-400)

Patterns:
  - bg-{token} / dark:bg-{token}
  - text-{token} / dark:text-{token}
  - border-{token} / dark:border-{token}
  - fill-{token}
```

### Tasks

#### 3.1 Scan Blade Views for Hardcoded Colors
- **Locations to scan**:
  - [resources/views](resources/views) - All Blade templates
  - [resources/views/components](resources/views/components) - All components
  - [resources/views/livewire](resources/views/livewire) - All Livewire components

- **Search patterns** for hardcoded colors:
  - Direct hex: `#FF5733`, `#000`, `#fff`
  - HTML color names: `red`, `blue`, `gray`
  - RGB values: `rgb(255, 87, 51)`
  - Tailwind hardcoded: `bg-red-500`, `text-blue-600` (non-token variants)

#### 3.2 Scan CSS Files for Hardcoded Colors
- **Location**: [resources/css/app.css](resources/css/app.css)
- Check for:
  - Direct color declarations outside of theme tokens
  - Custom color definitions not using CSS variables
  - Inline `<style>` blocks in Blade files

#### 3.3 Replace Hardcoded Colors with Theme Tokens
- Map each hardcoded color to appropriate theme token
- Update [resources/css/app.css](resources/css/app.css) if needed
- Ensure `dark:` variants are included for dual-theme support

### Mapping Guide
```
White/Light backgrounds      → bg-surface-primary / dark:bg-dark-900
Secondary backgrounds        → bg-surface-secondary / dark:bg-dark-700/800
Primary text                 → text-content-primary / dark:text-white
Secondary text               → text-content-secondary / dark:text-dark-400
Muted text                   → text-content-muted / dark:text-dark-500
Accent/Primary color         → bg-brand-base / text-brand-base
Borders                      → border-border-subtle / dark:border-dark-700
```

---

## Phase 4: Verification & Testing

### 4.1 Translation Completeness Check
- [ ] All EN keys have KU equivalents
- [ ] No missing translation keys on any page
- [ ] RTL rendering works correctly for KU
- [ ] Special characters (ە، ێ، ۆ) display correctly

### 4.2 Language Switcher Functionality
- [ ] Switch between EN, AR, KU without page errors
- [ ] Locale persists after page refresh
- [ ] RTL/LTR switches correctly
- [ ] UI layout adjusts for RTL (text-right, justify-end, etc.)

### 4.3 Theme Token Compliance
- [ ] No hardcoded hex colors in any Blade files
- [ ] All color classes use theme tokens (brand-*, surface-*, content-*, dark-*)
- [ ] Dark mode works correctly with all theme options
- [ ] No CSS variable references outside theme system

### 4.4 Cross-Browser Testing
- [ ] Chrome (light & dark themes)
- [ ] Firefox (light & dark themes)
- [ ] Safari (light & dark themes)
- [ ] Mobile browsers (iOS Safari, Chrome Mobile)

### 4.5 Locale Testing
- [ ] EN: All content displays correctly in English
- [ ] AR: All content displays correctly in Arabic with RTL
- [ ] KU: All content displays correctly in Kurdish with RTL

---

## Files to Modify Summary

### Translation Files
- [lang/ku/messages.php](lang/ku/messages.php) - Add missing Kurdish translations

### Component & View Files (Tailwind Audit)
- [resources/views/components/navbar.blade.php](resources/views/components/navbar.blade.php)
- [resources/views/components/footer.blade.php](resources/views/components/footer.blade.php)
- [resources/views/components/product-card.blade.php](resources/views/components/product-card.blade.php)
- [resources/views/components/input-iqd.blade.php](resources/views/components/input-iqd.blade.php)
- [resources/views/layouts/admin.blade.php](resources/views/layouts/admin.blade.php)
- All files in [resources/views/livewire](resources/views/livewire)
- All files in [resources/views/pages](resources/views/pages) (if exists)

### CSS Files
- [resources/css/app.css](resources/css/app.css) - Verify theme tokens

### Configuration Files
- [tailwind.config.js](tailwind.config.js) - Verify token definitions
- [config/app.php](config/app.php) - Verify locale config
- [bootstrap/app.php](bootstrap/app.php) - Verify locale initialization

### Middleware (If exists)
- [app/Http/Middleware](app/Http/Middleware) - Verify SetLocale or similar

---

## Implementation Order

1. **Week 1 - Translations**
   - Audit missing KU translations
   - Add missing keys to [lang/ku/messages.php](lang/ku/messages.php)
   - Test language switching

2. **Week 2 - Theme Token Audit**
   - Scan all Blade files for hardcoded colors
   - Document all hardcoded colors found
   - Create mapping document

3. **Week 3 - Hardcoded Color Replacement**
   - Replace hardcoded colors with theme tokens
   - Update CSS files
   - Test light/dark modes

4. **Week 4 - Testing & Verification**
   - Complete translation verification
   - Theme switching tests
   - Cross-browser testing
   - Documentation

---

## Definition of Done

✅ **All Kurdish translations complete** - Every UI string has KU equivalent
✅ **Language switcher functional** - Switching between locales works smoothly
✅ **No hardcoded colors** - All styling uses Tailwind theme tokens
✅ **Theme consistency** - Light and dark modes work correctly
✅ **RTL support** - KU and AR layouts render correctly
✅ **Tests passing** - All verification tests pass
✅ **Documentation updated** - Plan updated with actual findings

---

## Questions Before Proceeding

1. Should we create additional translation files (e.g., `messages_admin.php` for admin-specific translations)?
2. Are there any custom colors in the Tailwind config that should be token-mapped?
3. Should we add keyboard shortcuts for language switching (e.g., Alt+K for Kurdish)?
4. Do we need to support any additional locales besides EN, AR, KU?

---

## Notes
- Current structure indicates good foundation (theme tokens already partially in use)
- LanguageSwitcher component is well-designed
- RTL handling is already implemented
- Main work is completing KU translations and ensuring 100% Tailwind compliance
