<?php
#コード内で使われているbase64データをデコードし、その中のjsonもデコードしたたものです。

#下のデーターは実際にコード内でメインに使われているデーターです。
W1sxMTIsMTg1LDI2LDM1LDVdLFsxMTQsNzIsNDEsMzUsMTJdLFsyMzgsMTQxLDE3MiwzNSw2XSxbMTQyLDE0MiwxMzUsMzUsOF0sWzI0OSwxOTgsNDAsMzUsNF0sWzIzNCwyMzYsMjM3LDM1LDBdLFs4NSwxMTAsMjgsMzUsMTNdLFsxMjIsNDIsMTczLDM1LDEwXSxbMjEsMTM4LDE0NSwzNSw5XSxbMTY5LDg4LDMzLDEyLDFdLFsyMzYsMjMzLDIyNiwxNTUsMF0sWzEwNCw3OCw0Nyw1LDFdLFsxOTYsMTc5LDEyMyw1LDJdLFsyMTksMjExLDE2MCwxMiwwXSxbOTgsMjE5LDIxNCw1NywwXSxbMTU0LDExMCw3Nyw1LDNdLFsyMTksMjE5LDIxOSw0MiwwXSxbMTkwLDY5LDE4MCwzNSwyXSxbNjEsNDAsMTgsNSw1XSxbMTYxLDM5LDM1LDM1LDE0XSxbNzQsMTgxLDIxMywyMzcsM10sWzEzMiw1NiwxNzgsMjM3LDEwXSxbMTA0LDExOCw1MywxNTksNV0sWzE0Myw2MSw0NywxNTksMTRdLFs3Nyw1MSwzNiwxNTksMTJdLFs3Niw4Myw0MiwxNTksMTNdLFsxMTgsNzAsODYsMTU5LDEwXSxbMzcsMjMsMTYsMTU5LDE1XSxbNTgsNDIsMzYsMTU5LDddLFs4Nyw5MSw5MSwxNTksOV0sWzk0LDE2OSwyNSwyMzYsNV0sWzIxLDExOSwxMzYsMjM2LDldLFsxNjksNDgsMTU5LDIzNiwyXSxbMTksMTksMTksMTczLDBdLFsxNjksOTIsNTEsNSw0XSxbMjQ5LDIzNiw3OSw0MSwwXSxbNzQsNjAsOTEsMTU5LDExXSxbNDUsNDcsMTQzLDIzNiwxMV0sWzk2LDYwLDMyLDIzNiwxMl0sWzEzNSwxMDcsOTgsMTU5LDhdLFsxNjIsNzgsNzksMTU5LDZdLFs3Myw5MSwzNiwyMzYsMTNdLFs4LDEwLDE1LDIzNiwxNV0sWzIxMCwxNzgsMTYxLDE1OSwwXSxbMTU3LDEyOCw3OSw1LDBdLFszOSw2NywxMzgsMjIsMF0sWzE1OSwxNjQsMTc3LDgyLDBdLFsxMTMsMTA5LDEzOCwxNTksM10sWzIzMywxOTksNTUsMjM3LDRdLFsxNjIsODQsMzgsMTU5LDFdLFs3MCw3MywxNjcsMjM3LDExXSxbMjE0LDEwMSwxNDMsMjM2LDZdLFsxOTMsODQsMTg1LDIzNywyXSxbMjI3LDEzMiwzMiwyMzcsMV0sWzM2LDEzNywxOTksMjM2LDNdLFsxMDAsMzIsMTU2LDIzNiwxMF0sWzEyNiw4NSw1NCwyMzcsMTJdLFs3Nyw4MSw4NSwyMzcsN10sWzIyNiwyMjcsMjI4LDIzNywwXSxbMjI5LDE1MywxODEsMjM3LDZdLFsxNTUsMTU1LDE0OCwyMzcsOF0sWzI0MSwxNzUsMjEsMjM2LDRdLFs1NSw1OCw2MiwyMzYsN10sWzk3LDExOSw0NSwyMzcsMTNdLFsxNjgsNTQsNTEsMjM3LDE0XSxbMjA3LDIxMywyMTQsMjM2LDBdLFsxMjUsMTg5LDQyLDIzNyw1XSxbMjUsMjcsMzIsMjM3LDE1XSxbMTUwLDg4LDEwOSwxNTksMl0sWzM3LDE0OCwxNTcsMjM3LDldLFsyMjQsOTcsMSwyMzYsMV0sWzE0MiwzMywzMywyMzYsMTRdLFsxMjUsMTI1LDExNSwyMzYsOF0sWzIxLDIxLDI2LDM1LDE1XSxbMTI1LDEyNSwxMjUsMSwwXSxbMTgzLDE4MywxODYsMSw0XSxbMTU5LDExNSw5OCwxLDJdLFsyNDEsMTE4LDIwLDM1LDFdLFs1OCwxNzUsMjE3LDM1LDNdLFs2Myw2OCw3MiwzNSw3XSxbNTMsNTcsMTU3LDM1LDExXSxbMTMzLDEzMywxMzUsMSw2XV0=

#説明

array (
  0 => //色番号
  array (
    0 => 112, //赤(red)
    1 => 185, //緑(green)
    2 => 26, //青(blue)
    3 => 35,//minecraft id
    4 => 5,//minecraft ダメージ値
  ),
  1 => 
...

#それではメインのデーターです。

array (
  0 => 
  array (
    0 => 112,
    1 => 185,
    2 => 26,
    3 => 35,
    4 => 5,
  ),
  1 => 
  array (
    0 => 114,
    1 => 72,
    2 => 41,
    3 => 35,
    4 => 12,
  ),
  2 => 
  array (
    0 => 238,
    1 => 141,
    2 => 172,
    3 => 35,
    4 => 6,
  ),
  3 => 
  array (
    0 => 142,
    1 => 142,
    2 => 135,
    3 => 35,
    4 => 8,
  ),
  4 => 
  array (
    0 => 249,
    1 => 198,
    2 => 40,
    3 => 35,
    4 => 4,
  ),
  5 => 
  array (
    0 => 234,
    1 => 236,
    2 => 237,
    3 => 35,
    4 => 0,
  ),
  6 => 
  array (
    0 => 85,
    1 => 110,
    2 => 28,
    3 => 35,
    4 => 13,
  ),
  7 => 
  array (
    0 => 122,
    1 => 42,
    2 => 173,
    3 => 35,
    4 => 10,
  ),
  8 => 
  array (
    0 => 21,
    1 => 138,
    2 => 145,
    3 => 35,
    4 => 9,
  ),
  9 => 
  array (
    0 => 169,
    1 => 88,
    2 => 33,
    3 => 12,
    4 => 1,
  ),
  10 => 
  array (
    0 => 236,
    1 => 233,
    2 => 226,
    3 => 155,
    4 => 0,
  ),
  11 => 
  array (
    0 => 104,
    1 => 78,
    2 => 47,
    3 => 5,
    4 => 1,
  ),
  12 => 
  array (
    0 => 196,
    1 => 179,
    2 => 123,
    3 => 5,
    4 => 2,
  ),
  13 => 
  array (
    0 => 219,
    1 => 211,
    2 => 160,
    3 => 12,
    4 => 0,
  ),
  14 => 
  array (
    0 => 98,
    1 => 219,
    2 => 214,
    3 => 57,
    4 => 0,
  ),
  15 => 
  array (
    0 => 154,
    1 => 110,
    2 => 77,
    3 => 5,
    4 => 3,
  ),
  16 => 
  array (
    0 => 219,
    1 => 219,
    2 => 219,
    3 => 42,
    4 => 0,
  ),
  17 => 
  array (
    0 => 190,
    1 => 69,
    2 => 180,
    3 => 35,
    4 => 2,
  ),
  18 => 
  array (
    0 => 61,
    1 => 40,
    2 => 18,
    3 => 5,
    4 => 5,
  ),
  19 => 
  array (
    0 => 161,
    1 => 39,
    2 => 35,
    3 => 35,
    4 => 14,
  ),
  20 => 
  array (
    0 => 74,
    1 => 181,
    2 => 213,
    3 => 237,
    4 => 3,
  ),
  21 => 
  array (
    0 => 132,
    1 => 56,
    2 => 178,
    3 => 237,
    4 => 10,
  ),
  22 => 
  array (
    0 => 104,
    1 => 118,
    2 => 53,
    3 => 159,
    4 => 5,
  ),
  23 => 
  array (
    0 => 143,
    1 => 61,
    2 => 47,
    3 => 159,
    4 => 14,
  ),
  24 => 
  array (
    0 => 77,
    1 => 51,
    2 => 36,
    3 => 159,
    4 => 12,
  ),
  25 => 
  array (
    0 => 76,
    1 => 83,
    2 => 42,
    3 => 159,
    4 => 13,
  ),
  26 => 
  array (
    0 => 118,
    1 => 70,
    2 => 86,
    3 => 159,
    4 => 10,
  ),
  27 => 
  array (
    0 => 37,
    1 => 23,
    2 => 16,
    3 => 159,
    4 => 15,
  ),
  28 => 
  array (
    0 => 58,
    1 => 42,
    2 => 36,
    3 => 159,
    4 => 7,
  ),
  29 => 
  array (
    0 => 87,
    1 => 91,
    2 => 91,
    3 => 159,
    4 => 9,
  ),
  30 => 
  array (
    0 => 94,
    1 => 169,
    2 => 25,
    3 => 236,
    4 => 5,
  ),
  31 => 
  array (
    0 => 21,
    1 => 119,
    2 => 136,
    3 => 236,
    4 => 9,
  ),
  32 => 
  array (
    0 => 169,
    1 => 48,
    2 => 159,
    3 => 236,
    4 => 2,
  ),
  33 => 
  array (
    0 => 19,
    1 => 19,
    2 => 19,
    3 => 173,
    4 => 0,
  ),
  34 => 
  array (
    0 => 169,
    1 => 92,
    2 => 51,
    3 => 5,
    4 => 4,
  ),
  35 => 
  array (
    0 => 249,
    1 => 236,
    2 => 79,
    3 => 41,
    4 => 0,
  ),
  36 => 
  array (
    0 => 74,
    1 => 60,
    2 => 91,
    3 => 159,
    4 => 11,
  ),
  37 => 
  array (
    0 => 45,
    1 => 47,
    2 => 143,
    3 => 236,
    4 => 11,
  ),
  38 => 
  array (
    0 => 96,
    1 => 60,
    2 => 32,
    3 => 236,
    4 => 12,
  ),
  39 => 
  array (
    0 => 135,
    1 => 107,
    2 => 98,
    3 => 159,
    4 => 8,
  ),
  40 => 
  array (
    0 => 162,
    1 => 78,
    2 => 79,
    3 => 159,
    4 => 6,
  ),
  41 => 
  array (
    0 => 73,
    1 => 91,
    2 => 36,
    3 => 236,
    4 => 13,
  ),
  42 => 
  array (
    0 => 8,
    1 => 10,
    2 => 15,
    3 => 236,
    4 => 15,
  ),
  43 => 
  array (
    0 => 210,
    1 => 178,
    2 => 161,
    3 => 159,
    4 => 0,
  ),
  44 => 
  array (
    0 => 157,
    1 => 128,
    2 => 79,
    3 => 5,
    4 => 0,
  ),
  45 => 
  array (
    0 => 39,
    1 => 67,
    2 => 138,
    3 => 22,
    4 => 0,
  ),
  46 => 
  array (
    0 => 159,
    1 => 164,
    2 => 177,
    3 => 82,
    4 => 0,
  ),
  47 => 
  array (
    0 => 113,
    1 => 109,
    2 => 138,
    3 => 159,
    4 => 3,
  ),
  48 => 
  array (
    0 => 233,
    1 => 199,
    2 => 55,
    3 => 237,
    4 => 4,
  ),
  49 => 
  array (
    0 => 162,
    1 => 84,
    2 => 38,
    3 => 159,
    4 => 1,
  ),
  50 => 
  array (
    0 => 70,
    1 => 73,
    2 => 167,
    3 => 237,
    4 => 11,
  ),
  51 => 
  array (
    0 => 214,
    1 => 101,
    2 => 143,
    3 => 236,
    4 => 6,
  ),
  52 => 
  array (
    0 => 193,
    1 => 84,
    2 => 185,
    3 => 237,
    4 => 2,
  ),
  53 => 
  array (
    0 => 227,
    1 => 132,
    2 => 32,
    3 => 237,
    4 => 1,
  ),
  54 => 
  array (
    0 => 36,
    1 => 137,
    2 => 199,
    3 => 236,
    4 => 3,
  ),
  55 => 
  array (
    0 => 100,
    1 => 32,
    2 => 156,
    3 => 236,
    4 => 10,
  ),
  56 => 
  array (
    0 => 126,
    1 => 85,
    2 => 54,
    3 => 237,
    4 => 12,
  ),
  57 => 
  array (
    0 => 77,
    1 => 81,
    2 => 85,
    3 => 237,
    4 => 7,
  ),
  58 => 
  array (
    0 => 226,
    1 => 227,
    2 => 228,
    3 => 237,
    4 => 0,
  ),
  59 => 
  array (
    0 => 229,
    1 => 153,
    2 => 181,
    3 => 237,
    4 => 6,
  ),
  60 => 
  array (
    0 => 155,
    1 => 155,
    2 => 148,
    3 => 237,
    4 => 8,
  ),
  61 => 
  array (
    0 => 241,
    1 => 175,
    2 => 21,
    3 => 236,
    4 => 4,
  ),
  62 => 
  array (
    0 => 55,
    1 => 58,
    2 => 62,
    3 => 236,
    4 => 7,
  ),
  63 => 
  array (
    0 => 97,
    1 => 119,
    2 => 45,
    3 => 237,
    4 => 13,
  ),
  64 => 
  array (
    0 => 168,
    1 => 54,
    2 => 51,
    3 => 237,
    4 => 14,
  ),
  65 => 
  array (
    0 => 207,
    1 => 213,
    2 => 214,
    3 => 236,
    4 => 0,
  ),
  66 => 
  array (
    0 => 125,
    1 => 189,
    2 => 42,
    3 => 237,
    4 => 5,
  ),
  67 => 
  array (
    0 => 25,
    1 => 27,
    2 => 32,
    3 => 237,
    4 => 15,
  ),
  68 => 
  array (
    0 => 150,
    1 => 88,
    2 => 109,
    3 => 159,
    4 => 2,
  ),
  69 => 
  array (
    0 => 37,
    1 => 148,
    2 => 157,
    3 => 237,
    4 => 9,
  ),
  70 => 
  array (
    0 => 224,
    1 => 97,
    2 => 1,
    3 => 236,
    4 => 1,
  ),
  71 => 
  array (
    0 => 142,
    1 => 33,
    2 => 33,
    3 => 236,
    4 => 14,
  ),
  72 => 
  array (
    0 => 125,
    1 => 125,
    2 => 115,
    3 => 236,
    4 => 8,
  ),
  73 => 
  array (
    0 => 21,
    1 => 21,
    2 => 26,
    3 => 35,
    4 => 15,
  ),
  74 => 
  array (
    0 => 125,
    1 => 125,
    2 => 125,
    3 => 1,
    4 => 0,
  ),
  75 => 
  array (
    0 => 183,
    1 => 183,
    2 => 186,
    3 => 1,
    4 => 4,
  ),
  76 => 
  array (
    0 => 159,
    1 => 115,
    2 => 98,
    3 => 1,
    4 => 2,
  ),
  77 => 
  array (
    0 => 241,
    1 => 118,
    2 => 20,
    3 => 35,
    4 => 1,
  ),
  78 => 
  array (
    0 => 58,
    1 => 175,
    2 => 217,
    3 => 35,
    4 => 3,
  ),
  79 => 
  array (
    0 => 63,
    1 => 68,
    2 => 72,
    3 => 35,
    4 => 7,
  ),
  80 => 
  array (
    0 => 53,
    1 => 57,
    2 => 157,
    3 => 35,
    4 => 11,
  ),
  81 => 
  array (
    0 => 133,
    1 => 133,
    2 => 135,
    3 => 1,
    4 => 6,
  ),
);

#最後まで見てくれてありがとうございます

#この配列はphpプログラムにより自動で生成されています。
#このファイルはドット絵プロジェクトのプログラム内に記載されているbase64データーの内容を明確にするものです。
