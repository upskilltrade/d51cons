<?php

// Thai messages for the rules the site actually uses; other rules fall back to English.
return [
    'accepted' => 'กรุณายอมรับ:attribute',
    'alpha_dash' => ':attribute ใช้ได้เฉพาะตัวอักษรอังกฤษ ตัวเลข - และ _',
    'array' => ':attribute ต้องเป็นรายการ',
    'boolean' => ':attribute ต้องเป็นจริงหรือเท็จ',
    'date' => ':attribute ต้องเป็นวันที่ที่ถูกต้อง',
    'dimensions' => 'ขนาดภาพของ:attribute ไม่อยู่ในช่วงที่กำหนด',
    'email' => 'กรุณากรอก:attribute ให้ถูกต้อง',
    'enum' => ':attribute ที่เลือกไม่ถูกต้อง',
    'exists' => ':attribute ที่เลือกไม่ถูกต้อง',
    'file' => ':attribute ต้องเป็นไฟล์',
    'in' => ':attribute ที่เลือกไม่ถูกต้อง',
    'integer' => ':attribute ต้องเป็นตัวเลขจำนวนเต็ม',
    'max' => [
        'array' => ':attribute ต้องไม่เกิน :max รายการ',
        'file' => ':attribute ต้องมีขนาดไม่เกิน :max KB',
        'numeric' => ':attribute ต้องไม่เกิน :max',
        'string' => ':attribute ต้องยาวไม่เกิน :max ตัวอักษร',
    ],
    'mimes' => ':attribute ต้องเป็นไฟล์ประเภท :values',
    'mimetypes' => ':attribute ต้องเป็นไฟล์ประเภท :values',
    'min' => [
        'array' => ':attribute ต้องมีอย่างน้อย :min รายการ',
        'file' => ':attribute ต้องมีขนาดอย่างน้อย :min KB',
        'numeric' => ':attribute ต้องไม่น้อยกว่า :min',
        'string' => ':attribute ต้องยาวอย่างน้อย :min ตัวอักษร',
    ],
    'regex' => 'รูปแบบของ:attribute ไม่ถูกต้อง',
    'required' => 'กรุณากรอก:attribute',
    'string' => ':attribute ต้องเป็นข้อความ',
    'unique' => ':attribute นี้ถูกใช้แล้ว',
    'url' => ':attribute ต้องเป็น URL ที่ขึ้นต้นด้วย https',
    'password' => [
        'min' => ':attribute ต้องยาวอย่างน้อย :min ตัวอักษร',
    ],

    'attributes' => [],
];
