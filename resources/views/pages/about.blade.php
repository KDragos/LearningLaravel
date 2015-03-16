<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>About</title>
</head>
<body>
	
	<h1>About Me, {{ $notEscaped }}</h1>
	<p>By not escaping the input above, I could be opening my site up to some security issues.</p>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloremque omnis corporis, nemo amet, odio inventore sint vel nulla quasi earum eaque rerum pariatur est alias enim. Sed saepe architecto harum.</p>
	<p>This is unescaped data: {!! $notEscaped !!}</p>
</body>
</html>