// Size text input
const $textarea = document.getElementById('postcontent');
if($textarea) {
	function resizeArea($el) {
		let heightLimit = 500;
		$el.style.height = '';
		$el.style.height = Math.min($el.scrollHeight, heightLimit) + 'px';
	}

	resizeArea($textarea);

	$textarea.addEventListener('input', function(e){
		const $target = e.target || e.srcElement;
		resizeArea($target);
	});
}

// Confirm post deletion
const $adminForms = document.querySelectorAll('.admin');
if($adminForms) {
	$adminForms.forEach(($form) => {
		$form.addEventListener('submit', (e) => {
			if(confirm('Do you really want to delete this post?')) {
				$form.submit();
			} else {
				e.preventDefault();
			}
		});
	});
}

// Sketch
const $sketch = document.getElementById('sketch');
if($sketch) {
	var ctx,
		flag = false,
		prevX = 0,
		currX = 0,
		prevY = 0,
		currY = 0,
		dot_flag = false;

	var color = getComputedStyle(document.documentElement).getPropertyValue('--c-text'),
		size = 4;

	ctx = $sketch.getContext('2d', {
		antialias: true,
		willReadFrequently: true,
	});
	w = $sketch.width;
	h = $sketch.height;

	$sketch.addEventListener('mousemove', function (e) {
		findxy('move', e);
	}, false);
	$sketch.addEventListener('mousedown', function (e) {
		findxy('down', e);
	}, false);
	$sketch.addEventListener('mouseup', function (e) {
		findxy('up', e);
	}, false);
	$sketch.addEventListener('mouseout', function (e) {
		findxy('out', e);
	}, false);

	function draw() {
		ctx.beginPath();
		ctx.moveTo(prevX, prevY);
		ctx.lineTo(currX, currY);
		ctx.strokeStyle = color;
		ctx.lineWidth = size;
		ctx.stroke();
		ctx.closePath();
	}

	function findxy(res, e) {
		if (res == 'down') {
			prevX = currX;
			prevY = currY;
			currX = e.clientX - $sketch.getBoundingClientRect().left;
			currY = e.clientY - $sketch.getBoundingClientRect().top;

			flag = true;
			dot_flag = true;

			if (dot_flag) {
				ctx.beginPath();
				ctx.fillStyle = size;
				ctx.fillRect(currX, currY, 2, 2);
				ctx.closePath();
				dot_flag = false;
			}
		}

		if (res == 'up' || res == 'out') {
			flag = false;
		}

		if (res == 'move') {
			if (flag) {
				prevX = currX;
				prevY = currY;
				currX = e.clientX - $sketch.getBoundingClientRect().left;
				currY = e.clientY - $sketch.getBoundingClientRect().top;
				draw();
			}
		}
	}

	$form = $sketch.closest('form');
	if($form) {
		// Edit sketch
		if((!document.getElementById('postid').value.length == 0) && (!$sketch.dataset.sketch.length == 0)) {
			const $img = new Image();
			$img.src = $sketch.dataset.sketch;

			$img.onload = function(){
				ctx.drawImage($img, 0, 0);
			};

			$form.querySelector('details').open = true;
		}

		// Reset sketch
		const $reset = document.getElementById('reset');
		if($reset) {
			$reset.addEventListener('click', () => {
				ctx.clearRect(0, 0, w, h);
			});
		}

		// Submit sketch
		$form.addEventListener('submit', () => {
			function sketchEmpty(sketch) {
				return !ctx
					.getImageData(0, 0, sketch.width, sketch.height).data
					.some(channel => channel !== 0);
			}

			if(!sketchEmpty($sketch)) {
				document.getElementById('postsketch').value = $sketch.toDataURL('image/png');
			}
		});
	}
}
