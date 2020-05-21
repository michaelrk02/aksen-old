class Captcha {

    static render(selector, text) {
        var canvas = document.querySelector(selector);
        var ctx = canvas.getContext('2d');

        ctx.fillStyle = 'black';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        var width = canvas.width / 32;
        var height = canvas.height / 32;

        for (var y = 0; y < height; y++) {
            for (var x = 0; x < width; x++) {
                var dotX = Math.floor(Math.random() * 32);
                var dotY = Math.floor(Math.random() * 32);

                ctx.fillStyle = 'white';
                ctx.fillRect(x * 32 + dotX, y * 32 + dotY, 2, 2);
            }
        }

        ctx.font = '32px monospace';
        ctx.strokeStyle = 'white';
        ctx.strokeText(text, (canvas.width - 100) / 2, (canvas.height + 32) / 2);
    }

}
