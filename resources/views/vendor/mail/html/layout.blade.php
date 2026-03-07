<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="margin: 0; padding: 0; background-color: #f5f1e8; color: #2b2418;">
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}

body,
body * {
box-sizing: border-box;
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

.wrapper {
background: radial-gradient(circle at top, #fbf7ef 0%, #f1eadc 50%, #ece3d4 100%);
margin: 0;
padding: 32px 12px;
width: 100%;
}

.content {
width: 100%;
}

.header {
padding-bottom: 18px;
text-align: center;
}

.header a {
color: #6f5628;
font-size: 13px;
font-weight: 700;
letter-spacing: 0.18em;
text-decoration: none;
text-transform: uppercase;
}

.body {
width: 100%;
}

.inner-body {
background: #fffdf8;
border: 1px solid #eadfc9;
border-radius: 24px;
box-shadow: 0 24px 60px rgba(95, 74, 37, 0.12);
margin: 0 auto;
width: 570px;
}

.content-cell {
padding: 40px 40px 32px;
}

.content-cell p,
.content-cell ul,
.content-cell ol,
.content-cell td,
.content-cell th {
color: #4b3d25;
font-size: 15px;
line-height: 1.75;
}

.content-cell h1 {
color: #2b2418;
font-size: 28px;
font-weight: 700;
line-height: 1.25;
margin-top: 0;
margin-bottom: 18px;
}

.content-cell h2 {
color: #2f271a;
font-size: 21px;
font-weight: 700;
margin-top: 28px;
margin-bottom: 14px;
}

.content-cell h3 {
color: #2f271a;
font-size: 17px;
font-weight: 700;
margin-top: 24px;
margin-bottom: 12px;
}

.content-cell a {
color: #ab8949;
}

.subcopy {
border-top: 1px solid #eadfc9;
margin-top: 28px;
padding-top: 20px;
}

.subcopy p {
color: #6d5a37;
font-size: 13px;
line-height: 1.6;
}
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
{{ $header ?? '' }}

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<!-- Body content -->
<tr>
<td class="content-cell">
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>

{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html>
