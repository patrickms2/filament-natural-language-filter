# Natural Language Filter - Usage Examples

## Query Format
**Basic Structure:** `column_name operation value`

---

## 📋 Basic Examples

### Contains Operations
```
name contains admin
email contains john
email contains @gmail.com
description contains "multiple words"
```

### Equals Operations
```
status equals active
status is pending
name equals "John Doe"
type is admin
```

### Comparison Operations
```
created_at after 2023-01-01
updated_at before 2023-12-31
age greater than 18
price less than 100
score above 85
```

### Between Operations
```
age between 18 and 65
price between 50 and 200
created_at between 2023-01-01 and 2023-12-31
```

---

## 🌐 Mixed Language Support

### English Column with Arabic Values
```
name contains حيدر
email contains أحمد
status equals نشط
description contains "محتوى عربي"
```

### Arabic Column with English Values
```
الاسم يحتوي admin
البريد يشمل john
الحالة تساوي active
الوصف يحتوي "English content"
```

### Mixed Operations
```
name يحتوي حيدر
الاسم contains admin
email يشمل أحمد
البريد contains john
```

---

## 📊 Supported Columns

| Database Column | Supported Aliases |
|-----------------|-------------------|
| **name** | name, username, full name, user name, الاسم, اسم المستخدم |
| **email** | email, mail, e-mail, contact, البريد, ايميل, البريد الالكتروني |
| **created_at** | created, created at, registered, joined, تاريخ_الانشاء, تاريخ التسجيل |
| **updated_at** | updated, modified, changed, edited, تاريخ_التحديث, تاريخ التعديل |
| **status** | status, state, condition, الحالة, حالة, الوضع |

---

## ⚙️ Supported Operations

### English Operations
- **Contains:** contains, has, includes, with
- **Equals:** equals, is, are, =
- **Greater than:** greater than, more than, above, >
- **Less than:** less than, below, under, <
- **Before:** before, until
- **After:** after, since, from
- **Between:** between

### Arabic Operations
- **يحتوي:** يحتوي, يشمل, يتضمن, فيه
- **يساوي:** يساوي, هو, مساو
- **أكبر من:** أكبر من, أكثر من, فوق
- **أقل من:** أقل من, تحت, دون
- **قبل:** قبل, سابق
- **بعد:** بعد, لاحق, من
- **بين:** بين, ما بين

---

## 💡 Tips & Best Practices

### 1. Multi-word Values
Use quotes for values containing spaces:
```
name contains "John Doe"
الاسم يحتوي "أحمد محمد"
description equals "This is a long description"
```

### 2. Date Format
Always use YYYY-MM-DD format for dates:
```
created_at after 2023-01-01
تاريخ_الانشاء بعد 2023-12-31
updated_at between 2023-01-01 and 2023-06-30
```

### 3. Email Searches
Search for partial email addresses:
```
email contains @gmail.com
email contains company
البريد يشمل @example.com
```

### 4. Case Sensitivity
The filter is case-insensitive for most operations:
```
name contains ADMIN    (matches "admin", "Admin", "ADMIN")
status equals Active   (matches "active", "ACTIVE", "Active")
```

### 5. Special Characters
Handle special characters in values:
```
email contains user@domain.com
name contains "O'Connor"
description contains "50% discount"
```

---

## 🚀 Advanced Examples

### Complex Searches
```
name contains admin AND status equals active
email contains @company.com AND created_at after 2023-01-01
age between 25 and 35 AND status equals verified
```

### Mixed Language Queries
```
name contains حيدر AND status equals active
الاسم يحتوي admin AND الحالة تساوي نشط
email contains أحمد AND created_at after 2023-01-01
```

### Pattern Variations
```
# These all work the same way:
name contains admin
name has admin
name includes admin
name with admin

# Arabic equivalents:
الاسم يحتوي أحمد
الاسم يشمل أحمد
الاسم يتضمن أحمد
الاسم فيه أحمد
```

---

## ❌ Common Mistakes to Avoid

### Incorrect Date Format
```
❌ created_at after 01/01/2023
❌ created_at after 2023-1-1
✅ created_at after 2023-01-01
```

### Missing Quotes for Multi-word Values
```
❌ name contains John Doe
✅ name contains "John Doe"
```

### Invalid Column Names
```
❌ user_name contains admin  (if column is actually 'name')
✅ name contains admin
```

### Mixed Language Inconsistency
```
❌ name يحتوي على admin  (extra words)
✅ name يحتوي admin
```

---

## 🔧 Troubleshooting

### No Results Found
1. Check column name spelling
2. Verify the value exists in the database
3. Try different operation (contains vs equals)
4. Check for extra spaces or characters

### Pattern Not Recognized
1. Use the basic format: `column operation value`
2. Check supported operations list
3. Use quotes for multi-word values
4. Ensure proper spacing between elements

### Mixed Language Issues
1. Ensure your application supports the target locale
2. Check that the translation files are properly loaded
3. Verify column mappings for the current language 