
function checkImageSize(img) {
    // const naturalWidth = img.naturalWidth;
    // const renderedWidth = img.clientWidth;
    // const naturalHeight = img.naturalHeight;
    // const renderedHeight = img.clientHeight;
	var { naturalWidth, naturalHeight, width, height } = img;
	renderedWidth = width;
	renderedHeight = height;
    console.log('renderedWidth ' + renderedWidth);
    console.log('naturalWidth ' + naturalWidth);
    console.log('renderedHeight ' + renderedHeight);
    console.log('naturalHeight ' + naturalHeight);
    console.log('');

    if (renderedWidth > naturalWidth || renderedHeight > naturalHeight) {
		const existingOverlays = img.parentElement.querySelectorAll('.pco-mu-overlay');
        existingOverlays.forEach(overlay => {
            overlay.remove();
        });

        // Create a text box
        const infoBox = document.createElement('div');
        infoBox.innerHTML = `<div>Rendered size: ${renderedWidth}x${renderedHeight}</div> <div>Loaded size: ${naturalWidth}x${naturalHeight}</div>`;
        infoBox.style.maxWidth = '200px';
        infoBox.style.width = '100%';
        infoBox.style.color = 'red';
        infoBox.style.position = 'absolute';
		infoBox.style.top = 0;
		infoBox.style.right = 0;
        infoBox.style.backgroundColor = 'white';
        infoBox.style.border = '1px solid red';
        infoBox.style.padding = '2px';
        infoBox.style.fontSize = '12px';
        infoBox.style.textAlign = 'right';

        // Position the text box near the image
        // img.parentNode.insertBefore(infoBox, img.nextSibling);

		// var img = document.getElementById('myImage');
		var overlay = document.createElement('div');
		overlay.className = 'pco-mu-overlay';

		// Position and size the overlay
		overlay.style.left = img.offsetLeft + 'px';
		overlay.style.top = img.offsetTop + 'px';
		overlay.style.width = img.offsetWidth + 'px';
		overlay.style.height = img.offsetHeight + 'px';
		overlay.style.position = 'absolute';
		overlay.style.background = 'rgba(255, 0, 0, 0.05)';
		overlay.style.border = '1px solid rgba(255, 0, 0, 1)';
		overlay.style.color = 'black';
		overlay.style.textAlign = 'center';
		overlay.style.pointerEvents = 'none';

		overlay.appendChild(infoBox)


		// Append the overlay
		img.parentElement.appendChild(overlay);
    }
}

const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            let imgElement = entry.target.querySelector('img');
            if (imgElement) {
                checkImageSize(imgElement);
            }
            // observer.unobserve(entry.target);
        }
    });
// }); // Default was 0.1
}, { threshold:  [0, 0.50] });

const pictures = document.getElementsByTagName('picture');
for (let picture of pictures) {
    observer.observe(picture);
}
const imgages = document.getElementsByTagName('img');
for (let image of imgages) {
    checkImageSize(image);
}

